<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .container {
            width : 450px;
            margin : 40px auto;
            background-color: white;
            padding: 25px;
            border-radius: 10 px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #222;
        }
        .deskripsi {
        text-align: center;
        color: #666;
        font-size: 14px;
        margin-bottom: 25px;
        }
        .form-group {
        margin-bottom: 15px;
        }
        label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        color: #333;
        }
        input,
        select,
        textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #bbb;
        border-radius: 6px;
        font-size: 14px;
        }
        input:focus,
        select:focus,
        textarea:focus {
        outline: none;
        border-color: #007bff;
        }
        .pilihan {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        }
        .pilihan input {
        width: auto;
        margin-right: 8px;
        }
        .pilihan label {
        margin: 0;
        font-weight: normal;
        }
        button {
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        background-color: #007bff;
        color: white;
        font-size: 14px;
        cursor: pointer;
        }
        button:hover {
        background-color: #0056b3;
        }
        .reset {
        background-color: #dc3545;
        margin-left: 8px;
        }
        .reset:hover {
        background-color: #a71d2a;
        }

    </style>
</head>
<body>
    <?php include '../assets/header.php';?>
    <?php include '../assets/navbar.php';?>
        <div class="container">
            <h1>Data Mahasiswa</h1>
            <p class="deskripsi">Silakan isi data diri dengan lengkap dan benar.</p>

            <form action="#" method="post">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label for="nim">NIM</label>
                    <input type="text" id="nim" name="nim" placeholder="Masukkan NIM" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                </div>
                <div class="form-group">
                    <label for="jurusan">Jurusan</label>
                    <select id="jurusan" name="jurusan" required>
                        <option value="">Pilih Jurusan</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen Informatika">Manajemen Informatika</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <div class="pilihan">
                        <input type="radio" id="laki" name="jenis_kelamin" value="Laki-laki" required>
                        <label for="laki">Laki-laki</label>
                    </div>
                    <div class="pilihan">
                        <input type="radio" id="perempuan" name="jenis_kelamin" value="Perempuan" required>
                        <label for="perempuan">Perempuan</label>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat lengkap" required></textarea>
                    </div>
                    <div class="from-group">
                        <label for="Minat">Minat Pemrograman</label>
                        <div class="pilihan">
                            <input type="checkbox" id="CSS" name="css" value="css" required>
                            <label for="CSS">CSS</label>
                        </div>
                        <div class="pilihan">
                            <input type="checkbox" id="JS" name="js" value="js" required>
                            <label for="JS">JavaScript</label>
                        </div>
                        <div class="pilihan">
                            <input type="checkbox" id="PHP" name="php" value="php" required>
                            <label for="PHP">PHP</label>
                        </div>
                        <div class="pilihan">
                            <input type="checkbox" id="HTML" name="html" value="html" required>
                            <label for="HTML">HTML</label>
                        </div>
                    </div>
                    <div>
                        <button type="submit">Submit</button>
                        <button type="reset" class="reset">Reset</button>
                    </div>
            </form>
        </div>


    <?php include '../assets/footer.php';?>


</body>
</html>