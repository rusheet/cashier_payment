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

// Ambil data dari tabel keranjang
$query_keranjang = "SELECT * FROM keranjang";
$result_keranjang = $conn->query($query_keranjang);

$total_harga = 0; // Inisialisasi total harga

// Ambil data nama kasir (gantilah ini dengan cara sesuai dengan form yang digunakan)
$kasirName = isset($_POST['kasirName']) ? $_POST['kasirName'] : 'Nama Kasir Tidak Tersedia';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Resi</title>
    <style>
        /* Atur tampilan cetak */
        @media print {
            body {
                font-family: Arial, sans-serif;
                width: 58mm; /* Lebar sesuai dengan kertas termal */
                margin: 0; /* Hilangkan margin agar sesuai dengan lebar kertas */
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            th {
                background-color: #f2f2f2;
            }
            h2, h4, p {
                margin: 0;
            }
            h2 {
                margin-bottom: 10px;
            }
            h4 {
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>

    <h2>Keranjang Belanja</h2>
    <p>Kasir: <?php echo $kasirName; ?></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Jumlah Item</th>
                <th>Harga Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row_keranjang = $result_keranjang->fetch_assoc()) {
                echo "<tr>
                        <td>{$row_keranjang['id']}</td>
                        <td>{$row_keranjang['nama_produk']}</td>
                        <td>{$row_keranjang['jumlah_item']}</td>
                        <td>{$row_keranjang['harga_total']}</td>
                    </tr>";

                // Tambahkan harga total ke total harga
                $total_harga += $row_keranjang['harga_total'];
            }
            ?>
        </tbody>
    </table>

    <!-- Tampilkan Total Harga -->
    <h4>Total Harga: <?php echo $total_harga; ?></h4>

    <!-- Tampilkan pesan Terima Kasih -->
    <p>Terima Kasih Sudah Berbelanja</p>

    <script>
        // Secara otomatis memunculkan dialog cetak saat halaman dimuat
        window.onload = function () {
            window.print();
        };
    </script>

</body>
</html>

<?php
// Tutup koneksi setelah selesai menggunakan
$conn->close();
?>
