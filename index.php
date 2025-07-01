   <?php
    session_start();
  
   ?>
 

   <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <title>Login - Waprasta Global Teknologi</title>

        <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(120deg,rgb(45, 46, 47),rgb(64, 68, 72));
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        background: #ffffff;
        padding: 1.8rem;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        text-align: center;
        width: 400px;
    }

    h1 {
        color: #222;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    select, input {
        width: 90%;
        padding: 14px;
        margin: 12px 0;
        border: 1px solid #ccc;
        border-radius: 12px;
        font-size: 1rem;
        background: #f1f5f9;
        transition: 0.3s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
    }
    input{
        width: 83%;
        padding: 14px;
    }

    select:focus, input:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 7px rgba(0, 123, 255, 0.5);
        background: #e3f2fd;
    }

    button {
        width: 90%;
        padding: 15px;
        background: linear-gradient(90deg,rgb(64, 65, 65),rgb(78, 79, 80));
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        cursor: pointer;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    button:hover {
        background: linear-gradient(90deg, #0056b3, #004080);
        transform: scale(1.05);
    }

    footer {
        margin-top: 2rem;
        font-size: 0.95rem;
        color: #444;
    }

    .error-message {
        background-color: #ffcccc;
        color: #cc0000;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    @media (max-width: 400px) {
    .container {
        width: 80%; /* Lebih kecil dari 90% agar tetap pas */
        max-width: 270px; /* Batasi ukuran agar tidak terlalu besar */
        padding: 1rem; /* Kurangi padding agar tetap nyaman */
    }

    h1 {
        font-size: 1.5rem; /* Kecilkan ukuran teks */
    }

    select, input {
        padding: 12px; /* Kurangi padding input */
        font-size: 0.9rem; /* Kecilkan teks input */
    }

    button {
        padding: 12px;
        font-size: 1rem;
    }
}
        </style>
    </head>

    <body>
        <div class="container">
            <h1>Waprasta Global Teknologi</h1>
                    <!-- Pesan Kesalahan -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']); // Hapus pesan setelah ditampilkan
                        ?>
                    </div>
                <?php endif; ?>
            <form action="process/login.php" method="POST">
                <br>
                <select id="role" name="role">
                    <option value="teknisi">Teknisi</option>
                    <option value="operator">Operator</option>
                </select>
                <br>

                <input type="text" id="name" name="name" placeholder="Enter your name" />
                <br>

                <input type="text" id="nik" name="nik" placeholder="Enter your NIK" />

                <button type="submit">Login</button>
            </form>
            <footer>
                <p>&copy; 2025 Waprasta. All rights reserved.</p>
            </footer>
        </div>

    </body>

    </html>