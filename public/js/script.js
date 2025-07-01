// Fokuskan input saat modal tampil
$("#modalMesin").on("shown.bs.modal", function () {
  // Fokus ke input pertama yang kosong, atau default ke carline
  const firstEmpty = Array.from(
    document.querySelectorAll("#form-mesin input")
  ).find((input) => input.value.trim() === "");

  if (firstEmpty) {
    firstEmpty.focus();
  } else {
    document.getElementById("carline").focus();
  }
});

document.addEventListener("DOMContentLoaded", function () {
  document.addEventListener("keydown", function (event) {
    if (event.key !== "Enter") return;

    // Cek apakah modal aktif
    const modalVisible = $("#modalMesin").hasClass("show");

    if (modalVisible && document.activeElement.closest("#form-mesin")) {
      event.preventDefault();

      const inputs = Array.from(
        document.querySelectorAll("#form-mesin input, #form-mesin select")
      ).filter((el) => el.offsetParent !== null);

      const index = inputs.indexOf(document.activeElement);

      if (index !== -1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      } else {
        document.getElementById("form-mesin").requestSubmit();
      }
    }

    // Jika tidak di modal, dan berada di form-noproc
    else if (document.activeElement.closest("#form-noproc")) {
      event.preventDefault();

      setTimeout(() => {
        let inputs = Array.from(
          document.querySelectorAll("#form-noproc input.form-input")
        ).filter((input) => input.offsetParent !== null);

        let index = inputs.indexOf(document.activeElement);

        if (index !== -1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        } else if (index === inputs.length - 1) {
          document.getElementById("form-noproc").requestSubmit();
        }
      }, 50);
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-mesin");
  const submitButton = document.getElementById("submit-mesin");
  const mesinInput = document.getElementById("mesin");
  const carlineInput = document.getElementById("carline");
  const formNoproc = document.getElementById("form-noproc");
  const hiddenMesin = document.getElementById("hidden-mesin");

  // ✅ Cek apakah mesin sudah dipilih sebelumnya
  let savedMesin = sessionStorage.getItem("mesin");
  let savedCarline = sessionStorage.getItem("carline");

  if (savedMesin && savedCarline) {
    console.log("✅ Mesin sudah dipilih sebelumnya:", savedMesin);

    // ✅ Set nilai input tersembunyi
    hiddenMesin.value = savedMesin;

    // ✅ Isi input dan nonaktifkan agar tidak bisa diedit ulang
    mesinInput.value = savedMesin;
    carlineInput.value = savedCarline;
    mesinInput.disabled = true;
    carlineInput.disabled = true;

    // ✅ Sembunyikan form-mesin dan langsung tampilkan form-noproc
    form.style.display = "none";
    formNoproc.style.display = "block";

    return; // Stop eksekusi lebih lanjut
  }

  // ✅ Jika mesin belum dipilih, jalankan validasi input
  mesinInput.addEventListener("input", validateForm);
  carlineInput.addEventListener("input", validateForm);

  async function validateInput(input, type) {
    try {
      let response = await $.ajax({
        url: "../validate/val_system.php",
        method: "POST",
        data: {
          [type]: input.value.trim(),
        },
        dataType: "json",
      });

      if (!response.valid) {
        input.classList.add("is-invalid");
        return false;
      } else {
        input.classList.remove("is-invalid");
        return true;
      }
    } catch {
      alert("Terjadi kesalahan validasi. Coba lagi.");
      return false;
    }
  }

  async function validateForm() {
    let mesinValid = await validateInput(mesinInput, "mesin");
    let carlineValid = await validateInput(carlineInput, "carline");
    submitButton.disabled = !(mesinValid && carlineValid);
  }

  form.addEventListener("submit", async function (event) {
    event.preventDefault(); // ✅ Cegah halaman refresh

    await validateForm();
    if (!submitButton.disabled) {
      let mesinValue = mesinInput.value;
      let carlineValue = carlineInput.value;

      // ✅ Simpan ke sessionStorage
      sessionStorage.setItem("mesin", mesinValue);
      sessionStorage.setItem("carline", carlineValue);
      console.log("Mesin:", sessionStorage.getItem("mesin"));
      console.log("Carline:", sessionStorage.getItem("carline"));

      let formData = {
        shift: document.getElementById("shift").value,
        mesin: mesinValue,
        carline: carlineValue,
      };

      $.ajax({
        url: "../validate/save_session.php",
        method: "POST",
        data: formData,
        success: function (response) {
          console.log("Data berhasil disimpan:", response);
        },
        error: function () {
          alert("Gagal menyimpan data.");
        },
      });

      // ✅ Simpan ke input hidden
      hiddenMesin.value = mesinValue;

      // ✅ Sembunyikan form-mesin dan tampilkan form-noproc
      form.style.display = "none";
      formNoproc.style.display = "block";

      // ✅ Tutup modal setelah sukses
      let modalElement = document.getElementById("modalMesin");
      let modalInstance = bootstrap.Modal.getInstance(modalElement);
      modalInstance.hide();
    }
  });
});
$(document).ready(function () {
  // Tangani perubahan jumlah input
  $("#jumlahInput").change(function () {
    let jumlah = parseInt($(this).val());

    $(".process-input").hide();

    for (let i = 1; i <= jumlah; i++) {
      $("#input-" + i).show();
    }

    setTimeout(() => {
      $(`#input-1 input`).focus();
    }, 50);

    let urlParams = new URLSearchParams(window.location.search);
    urlParams.set("jumlah", jumlah);
    const newUrl = "?" + urlParams.toString();
    window.history.replaceState(null, "", newUrl);

    // Tambahkan reload jika kamu memang ingin langsung reload
    window.location.search = urlParams.toString(); // Hapus komentar untuk pakai
  });

  // Tangani submit form tanpa reload dan dengan validasi
  $("#form-noproc").submit(function (event) {
    event.preventDefault(); // Mencegah reload halaman

    let jumlah = parseInt($("#jumlahInput").val());
    let firstFiveDigits = {};
    let originalValues = {};
    let isValid = true;
    let errorMessages = [];

    for (let i = 1; i <= jumlah; i++) {
      let inputField = $("#noproc" + i);
      let value = inputField.val().trim();

      if (!value || value.length < 5) {
        isValid = false;
        errorMessages.push(
          `Nomor Proses ${i} harus memiliki minimal 5 karakter.`
        );
        inputField.addClass("is-invalid");
      } else {
        inputField.removeClass("is-invalid");

        let npg = value.substring(0, 5); // Ambil 5 digit pertama
        originalValues["noproc" + i] = value;
        firstFiveDigits["noproc" + i] = npg;
      }
      console.log("NPG:", value);
    }

    if (!isValid) {
      $("#error-message").html(errorMessages.join("<br>")).show();
      return;
    } else {
      $("#error-message").hide();
    }

    // **Validasi npg dalam satu request**
    $.ajax({
      url: "../validate/validate_npg.php",
      type: "POST",
      data: { firstFive: firstFiveDigits },
      dataType: "json",
      success: function (response) {
        console.log("Response dari validate_npg.php:", response); // Debugging

        if (!response.valid) {
          $("#error-message").html(response.error).show();
          return;
        }

        // Jika valid, lanjutkan proses
        $.ajax({
          url: "process2.php",
          type: "POST",
          data: { original: originalValues, firstFive: firstFiveDigits },
          success: function (response) {
            $("#result").html(response);
          },
          error: function (xhr, status, error) {
            console.log("Error process2.php:", xhr.responseText); // Debugging
            $("#result").html(
              "<p style='color:red;'>Terjadi kesalahan saat mencari data.</p>"
            );
          },
        });
      },
      error: function (xhr, status, error) {
        console.log("Error validate_npg.php:", xhr.responseText); // Debugging
        $("#error-message")
          .html("<p style='color:red;'>Gagal memvalidasi data.</p>")
          .show();
      },
    });
  });

  // Tangani pemilihan Side A/B tanpa reload
  $(document).on("submit", "#side-selection-form", function (event) {
    event.preventDefault();

    $.ajax({
      url: "save_selection.php",
      type: "POST",
      data: $(this).serialize(),
      success: function () {
        window.location.href = "app_term.php";
      },
      error: function () {
        $("#result").html("<p style='color:red;'>Gagal menyimpan pilihan.</p>");
        const lastResults = sessionStorage.getItem(
          "savedSearchResults_applicator-term"
        );
        if (lastResults) {
          saveSearchResults(JSON.parse(lastResults), "applicator-term");
        }
      },
    });
  });

  function saveSearchResults(data, type) {
    if (!data || data.length === 0 || !type) {
      console.error("Data atau type tidak valid.");
      return;
    }

    // Simpan data ke sessionStorage sebelum mengirim ke server
    sessionStorage.setItem(`savedSearchResults_${type}`, JSON.stringify(data));
    console.log(`Data ${type} disimpan ke sessionStorage.`, data);

    // Kirim data ke server
    $.ajax({
      url: "../process/save_search_results.php",
      method: "POST",
      data: {
        results: JSON.stringify(data),
        type: type,
      },
      success: function (response) {
        console.log(`Data ${type} berhasil disimpan ke server:`, response);
      },
      error: function (xhr, status, error) {
        console.error(`Gagal menyimpan data ${type} ke server:`, error);
      },
    });
  }
});
