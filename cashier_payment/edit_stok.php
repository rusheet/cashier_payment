<?php
$host = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$database = "kios"; // Ganti dengan nama database Anda

// Buat koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi variabel
$id_produk = $_GET['id'];
$error_message = "";

// Cek apakah form dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id_produk = $_POST["id"];
    $nama_produk = $_POST["nama_produk"];
    $stok_item = $_POST["stok_item"];
    $harga_satuan = $_POST["harga_satuan"];

    // Update data stok
    $query_update = "UPDATE stok SET nama_produk='$nama_produk', stok_item=$stok_item, harga_satuan=$harga_satuan WHERE id='$id_produk'";

    if ($conn->query($query_update) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}

// Ambil data stok berdasarkan ID
$query_select = "SELECT * FROM stok WHERE id='$id_produk'";
$result_select = $conn->query($query_select);

// Periksa hasil query
if ($result_select === FALSE) {
    die("Error: " . $conn->error);
}

// Periksa apakah ada baris data yang ditemukan
if ($result_select->num_rows > 0) {
    // Ambil data stok
    $row_stok = $result_select->fetch_assoc();
} else {
    $error_message = "Data stok dengan ID $id_produk tidak ditemukan.";
}

// Tutup koneksi setelah selesai menggunakan
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stok</title>
    <!-- Sertakan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h1 class="mb-4">Edit Stok Barang</h1>

    <!-- Formulir Edit Stok -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id_produk; ?>">
        <div class="mb-3">
            <label for="id" class="form-label">ID:</label>
            <input type="text" class="form-control" id="id" name="id" value="<?php echo $row_stok['id']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk:</label>
            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?php echo $row_stok['nama_produk']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="stok_item" class="form-label">Stok Item:</label>
            <input type="number" class="form-control" id="stok_item" name="stok_item" value="<?php echo $row_stok['stok_item']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="harga_satuan" class="form-label">Harga Satuan:</label>
            <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" value="<?php echo $row_stok['harga_satuan']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary ms-2">Batal</a>
    </form>

    <!-- Tampilkan pesan kesalahan jika ada -->
    <?php
    if ($error_message != "") {
        echo "<div class='alert alert-danger mt-3' role='alert'>$error_message</div>";
    }
    ?>
</div>

<!-- Sertakan Bootstrap JS dan Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
