<?php
// Buat koneksi ke database
$conn = new mysqli("localhost", "root", "", "kios");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari formulir
$id_produk = $_GET['id'];

// Ambil data keranjang berdasarkan ID
$query_select_keranjang = "SELECT * FROM keranjang WHERE id=?";
$stmt_select_keranjang = $conn->prepare($query_select_keranjang);

// Periksa apakah prepared statement berhasil dibuat
if ($stmt_select_keranjang === FALSE) {
    die("Error in prepared statement (select_keranjang): " . $conn->error);
}

$stmt_select_keranjang->bind_param("s", $id_produk);
$stmt_select_keranjang->execute();

// Periksa apakah eksekusi prepared statement berhasil
if ($stmt_select_keranjang === FALSE) {
    die("Error in execute (select_keranjang): " . $stmt_select_keranjang->error);
}

$result_select_keranjang = $stmt_select_keranjang->get_result();

// Periksa apakah ada baris data yang ditemukan di keranjang
if ($result_select_keranjang->num_rows > 0) {
    $row_keranjang = $result_select_keranjang->fetch_assoc();

    // Ambil harga satuan dari tabel stok
    $query_select_stok = "SELECT harga_satuan FROM stok WHERE id=?";
    $stmt_select_stok = $conn->prepare($query_select_stok);

    // Periksa apakah prepared statement berhasil dibuat
    if ($stmt_select_stok === FALSE) {
        die("Error in prepared statement (select_stok): " . $conn->error);
    }

    $stmt_select_stok->bind_param("s", $id_produk);
    $stmt_select_stok->execute();

    // Periksa apakah eksekusi prepared statement berhasil
    if ($stmt_select_stok === FALSE) {
        die("Error in execute (select_stok): " . $stmt_select_stok->error);
    }

    $result_select_stok = $stmt_select_stok->get_result();

    // Periksa apakah ada baris data yang ditemukan di stok
    if ($result_select_stok->num_rows > 0) {
        $row_stok = $result_select_stok->fetch_assoc();

        // Update keranjang dengan harga_satuan yang diambil dari stok
        $query_update_keranjang = "UPDATE keranjang SET jumlah_item = jumlah_item - 1, harga_total = jumlah_item * ? WHERE id=?";
        $stmt_update_keranjang = $conn->prepare($query_update_keranjang);

        // Periksa apakah prepared statement berhasil dibuat
        if ($stmt_update_keranjang === FALSE) {
            die("Error in prepared statement (update_keranjang): " . $conn->error);
        }

        $harga_satuan = $row_stok['harga_satuan'];
        $stmt_update_keranjang->bind_param("ds", $harga_satuan, $id_produk);
        $stmt_update_keranjang->execute();

        // Periksa apakah eksekusi prepared statement berhasil
        if ($stmt_update_keranjang === FALSE) {
            die("Error in execute (update_keranjang): " . $stmt_update_keranjang->error);
        }

        // Update stok dengan menambahkan 1
        $query_update_stok = "UPDATE stok SET stok_item = stok_item + 1 WHERE id=?";
        $stmt_update_stok = $conn->prepare($query_update_stok);

        // Periksa apakah prepared statement berhasil dibuat
        if ($stmt_update_stok === FALSE) {
            die("Error in prepared statement (update_stok): " . $conn->error);
        }

        $stmt_update_stok->bind_param("s", $id_produk);
        $stmt_update_stok->execute();

        // Periksa apakah eksekusi prepared statement berhasil
        if ($stmt_update_stok === FALSE) {
            die("Error in execute (update_stok): " . $stmt_update_stok->error);
        }

        // Jika jumlah item di keranjang menjadi 0, hapus item dari keranjang
        if ($row_keranjang['jumlah_item'] == 0) {
            // Hapus item dari keranjang
            $query_delete_keranjang = "DELETE FROM keranjang WHERE id=?";
            $stmt_delete_keranjang = $conn->prepare($query_delete_keranjang);

            // Periksa apakah prepared statement berhasil dibuat
            if ($stmt_delete_keranjang === FALSE) {
                die("Error in prepared statement (delete_keranjang): " . $conn->error);
            }

            $stmt_delete_keranjang->bind_param("s", $id_produk);
            $stmt_delete_keranjang->execute();

            // Periksa apakah eksekusi prepared statement berhasil
            if ($stmt_delete_keranjang === FALSE) {
                die("Error in execute (delete_keranjang): " . $stmt_delete_keranjang->error);
            }
        }

        // Redirect kembali ke index.php setelah pemindahan item dari keranjang ke stok
        header("Location: index.php");
        exit();
    } else {
        echo "Data stok dengan ID $id_produk tidak ditemukan.";
    }
} else {
    echo "Data keranjang dengan ID $id_produk tidak ditemukan.";
}

// Tutup koneksi setelah selesai menggunakan
$conn->close();
?>
