<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Ceria</title>
    <!-- Sertakan Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

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

    // Ambil data dari tabel stok
    $query_stok = "SELECT * FROM stok";
    $result_stok = $conn->query($query_stok);

    // Ambil data dari tabel keranjang
    $query_keranjang = "SELECT * FROM keranjang";
    $result_keranjang = $conn->query($query_keranjang);
    ?>

    <div class="container mt-4">
        <h1 class="mb-4 text-center">Kasir Ceria</h1>

        <div class="row">
            <!-- Tabel Stok -->
            <div class="col-md-6 mb-4">
                <h2>Stok Barang</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Stok Item</th>
                            <th>Harga Satuan</th>
                            <th>Aksi</th> <!-- Kolom Aksi -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row_stok = $result_stok->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row_stok['no']}</td>";
                            echo "<td>{$row_stok['id']}</td>";
                            echo "<td>{$row_stok['nama_produk']}</td>";
                            echo "<td>{$row_stok['stok_item']}</td>";
                            echo "<td>{$row_stok['harga_satuan']}</td>";
                            // Tombol Masukkan ke Keranjang
                            echo "<td>";
                            echo "<a href='add_to_cart.php?id={$row_stok['id']}' class='btn btn-success btn-sm'>+</a>";
                            // Tombol Edit
                            echo "<a href='edit_stok.php?id={$row_stok['id']}' class='btn btn-warning btn-sm ms-2'>Edit</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Tombol Tambah Stok -->
                <button class="btn btn-primary" onclick="location.href='tambah_stok.php'">Tambah Stok</button>
            </div>

            <!-- Tabel Keranjang -->
            <div class="col-md-6">
                <h2>Keranjang Belanja</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Jumlah Item</th>
                            <th>Harga Total</th>
                            <th>Aksi</th> <!-- Kolom Aksi -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_harga = 0; // Inisialisasi total harga

                        while ($row_keranjang = $result_keranjang->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row_keranjang['no']}</td>";
                            echo "<td>{$row_keranjang['id']}</td>";
                            echo "<td>{$row_keranjang['nama_produk']}</td>";
                            echo "<td>{$row_keranjang['jumlah_item']}</td>";
                            echo "<td>{$row_keranjang['harga_total']}</td>";
                            // Tombol Back to Stok
                            echo "<td><a href='back_to_stok.php?id={$row_keranjang['id']}' class='btn btn-danger btn-sm'>Back to Stok</a></td>";
                            echo "</tr>";

                            // Tambahkan harga total ke total harga
                            $total_harga += $row_keranjang['harga_total'];
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Tampilkan Total Harga -->
                <h4 class="mt-4">Total Harga: <?php echo $total_harga; ?></h4>
                <!-- Tombol Clear Keranjang -->
    <form action="clear_keranjang.php" method="post" class="mt-2">
        <button type="submit" class="btn btn-danger">Clear Keranjang</button>
    </form>
    
               <!-- Form Input Kasir's Name -->
<form action="cetak_resi.php" method="post" class="mt-4">
    <div class="mb-3">
        <label for="kasirName" class="form-label">Nama Kasir:</label>
        <input type="text" class="form-control" id="kasirName" name="kasirName" required>
    </div>
    <button type="submit" class="btn btn-primary">Cetak Resi</button>
</form>


                <!-- Tombol Cetak Resi
                <button class="btn btn-info mt-2" onclick="location.href='cetak_resi.php'" target="_blank">Cetak Resi</button> -->
            </div>
        </div>
    </div>

    <!-- Sertakan Bootstrap JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Tutup koneksi setelah selesai menggunakan
$conn->close();
?>
