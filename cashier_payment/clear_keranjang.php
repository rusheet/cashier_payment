<?php
$host = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$database = "kios";

// Buat koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus semua data dari tabel keranjang
$query_clear_keranjang = "DELETE FROM keranjang";
$conn->query($query_clear_keranjang);

// Reset nilai AUTO_INCREMENT pada kolom no di tabel keranjang
$query_reset_auto_increment = "ALTER TABLE keranjang AUTO_INCREMENT = 1";
$conn->query($query_reset_auto_increment);

// Tutup koneksi setelah selesai menggunakan
$conn->close();

// Redirect kembali ke index.php
header("Location: index.php");
exit();
?>
