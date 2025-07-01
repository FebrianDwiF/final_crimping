document.addEventListener("keydown", function (event) {
  if (event.key === "Enter") {
    event.preventDefault(); // Mencegah form submit otomatis

    let inputs = document.querySelectorAll('input[type="text"]'); // Ambil semua input teks
    let index = Array.from(inputs).indexOf(document.activeElement); // Temukan input yang aktif

    if (index !== -1 && index < inputs.length - 1) {
      inputs[index + 1].focus(); // Pindah ke input berikutnya
    } else {
      inputs[0].focus(); // Jika di input terakhir, kembali ke input pertama
    }
  }
});
$(document).ready(function () {
  $("#form-applicator-term").on("submit", function (e) {
    e.preventDefault();

    const applicator = $("#applicator").val();
    const term = $("#term").val();

    var app1 = "<?= addslashes(str_replace('-', '', $app1)) ?>";  // Hapus strip dari database
    var terminal1 = "<?= addslashes(str_replace('-', '', $terminal1)) ?>";
    
    // Pastikan string memiliki minimal 36 karakter sebelum mengambil substring
    if (applicator.length < 36 || term.length < 36) {
        alert("Input tidak valid! Pastikan panjang karakter cukup.");
        event.preventDefault();
        return;
    }
    
    // Ambil karakter ke-27 hingga ke-36 (indeks 26 sampai 35)
    var appExtract = applicator.substring(26, 36);
    var termExtract = term.substring(26, 36);
    console.log("appExtract: ", appExtract);
    console.log("termExtract: ", termExtract);
    console.log("app1 (DB): ", app1);
    console.log("terminal1 (DB): ", terminal1);
    console.log("Perbandingan appExtract == app1:", appExtract === app1);
    console.log("Perbandingan termExtract == terminal1:", termExtract === terminal1);
    
    
    if (!appExtract || !termExtract) {
        alert("Applicator dan Terminal harus diisi!");
        event.preventDefault();
        return;
    }
    
    if (appExtract !== app1) {
        alert("Applicator harus sesuai dengan: " + app1);
        event.preventDefault();
        return;
    }
    
    if (termExtract !== terminal1) {
        alert("Terminal harus sesuai dengan: " + terminal1);
        event.preventDefault();
        return;
    }
    
    $.ajax({
      url: "../process/get_term.php",
      method: "GET",
      data: { applicator, term },
      dataType: "json",
      success: function (response) {
        $("#error-message").hide();

        console.log("Raw response dari server:", response);
        console.log("Object.keys(response):", Object.keys(response));
        console.log("Object.values(response):", Object.values(response));

        // **Pastikan semua tabel memiliki data**
        const allTables = [
          "data_kanban",
          "data_cfm",
          "data_crimping",
          "data_stroke",
        ];

        const allTablesHaveData = allTables.every(
          (table) =>
            Array.isArray(response[table]) && response[table].length > 0
        );

        if (!allTablesHaveData) {
          alert(
            "Data Tidak Valid! Periksa kembali Applicator atau Term yang dimasukkan."
          );
          console.log("❌ Ada tabel yang kosong, tetap di halaman ini.");
          return;
        }

        let tablesHtml = "";
        for (const [tableName, rows] of Object.entries(response)) {
          tablesHtml += `<h3 style="margin-top: 20px;">${tableName}</h3>`;
          tablesHtml += `<table border="1" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                          <thead><tr>`;

          if (Array.isArray(rows) && rows.length > 0) {
            const columns = Object.keys(rows[0]);
            columns.forEach((column) => {
              tablesHtml += `<th style="padding: 10px; border: 1px solid #ddd;">${column}</th>`;
            });

            tablesHtml += "<tbody>";
            rows.forEach((row) => {
              tablesHtml += "<tr>";
              columns.forEach((column) => {
                tablesHtml += `<td>${row[column] || "-"}</td>`;
              });
              tablesHtml += "</tr>";
            });
            tablesHtml += "</tbody>";
          } else {
            tablesHtml += `<tbody>
                              <tr>
                                <td colspan="100%" style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                  Data tidak ditemukan
                                </td>
                              </tr>
                            </tbody>`;
          }
          tablesHtml += "</table>";
        }

        $("#search-results").html(tablesHtml);
        saveSearchResults(response, "applicator-term");

        console.log("✅ Semua tabel ada datanya, pindah ke pengukuran.php");
        window.location.href = "pengukuran.php";
      },
      error: function (xhr, status, error) {
        $("#error-message").text(`Error: ${xhr.status} - ${error}`).show();
      },
    });
  });

  function saveSearchResults(data, type) {
    if (!data || Object.keys(data).length === 0 || !type) {
      console.error("Data atau type tidak valid.");
      return;
    }

    try {
      sessionStorage.setItem(
        `savedSearchResults_${type}`,
        JSON.stringify(data)
      );
      console.log(`Data ${type} disimpan ke sessionStorage.`, data);

      $.ajax({
        url: "../process/save_search_results.php",
        method: "POST",
        data: { results: JSON.stringify(data), type },
        success: function (response) {
          console.log(`Data ${type} berhasil disimpan ke server:`, response);
        },
        error: function (xhr, status, error) {
          console.error(`Gagal menyimpan data ${type} ke server:`, error);
        },
      });
    } catch (error) {
      console.error("Gagal menyimpan ke sessionStorage:", error);
    }
  }
});
