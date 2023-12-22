<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stok</title>
    <!-- Sertakan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php
// Cek apakah formulir telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Buat koneksi ke database
    $conn = new mysqli("localhost", "root", "", "kios");

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari formulir
    $id = $_POST["id"];
    $nama_produk = $_POST["nama_produk"];
    $stok_item = $_POST["stok_item"];
    $harga_satuan = $_POST["harga_satuan"];

    // Insert data ke tabel stok
    $query_insert = "INSERT INTO stok (id, nama_produk, stok_item, harga_satuan) VALUES ('$id', '$nama_produk', $stok_item, $harga_satuan)";
    $result_insert = $conn->query($query_insert);

    // Tutup koneksi setelah selesai menggunakan
    $conn->close();

    // Redirect kembali ke index.php setelah penyimpanan
    header("Location: index.php");
    exit();
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Tambah Stok</h1>

    <!-- Formulir Tambah Stok -->
    <form action="" method="post">
        <div class="mb-3">
            <label for="id" class="form-label">ID:</label>
            <input type="text" class="form-control" id="id" name="id" required>
        </div>
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk:</label>
            <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
        </div>
        <div class="mb-3">
            <label for="stok_item" class="form-label">Stok Item:</label>
            <input type="number" class="form-control" id="stok_item" name="stok_item" required>
        </div>
        <div class="mb-3">
            <label for="harga_satuan" class="form-label">Harga Satuan:</label>
            <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<!-- Sertakan Bootstrap JS dan Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
