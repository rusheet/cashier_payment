<?php
// Buat koneksi ke database
$conn = new mysqli("localhost", "root", "", "kios");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari formulir
$id_produk = $_GET['id'];

// Ambil data stok berdasarkan ID
$query_select_stok = "SELECT * FROM stok WHERE id=?";
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

    // Pastikan stok cukup untuk dikurangkan
    if ($row_stok['stok_item'] > 0) {
        // Update stok di tabel stok
        $new_stok = $row_stok['stok_item'] - 1;
        $query_update_stok = "UPDATE stok SET stok_item=? WHERE id=?";
        $stmt_update_stok = $conn->prepare($query_update_stok);

        // Periksa apakah prepared statement berhasil dibuat
        if ($stmt_update_stok === FALSE) {
            die("Error in prepared statement (update_stok): " . $conn->error);
        }

        $stmt_update_stok->bind_param("is", $new_stok, $id_produk);
        $stmt_update_stok->execute();

        // Periksa apakah eksekusi prepared statement berhasil
        if ($stmt_update_stok === FALSE) {
            die("Error in execute (update_stok): " . $stmt_update_stok->error);
        }

        // Cek apakah item sudah ada di keranjang
        $query_check_keranjang = "SELECT * FROM keranjang WHERE id=?";
        $stmt_check_keranjang = $conn->prepare($query_check_keranjang);

        // Periksa apakah prepared statement berhasil dibuat
        if ($stmt_check_keranjang === FALSE) {
            die("Error in prepared statement (check_keranjang): " . $conn->error);
        }

        $stmt_check_keranjang->bind_param("s", $id_produk);
        $stmt_check_keranjang->execute();

        // Periksa apakah eksekusi prepared statement berhasil
        if ($stmt_check_keranjang === FALSE) {
            die("Error in execute (check_keranjang): " . $stmt_check_keranjang->error);
        }

        $result_check_keranjang = $stmt_check_keranjang->get_result();

        // Jika item sudah ada di keranjang, update jumlah item dan harga total
        if ($result_check_keranjang->num_rows > 0) {
            $query_update_keranjang = "UPDATE keranjang SET jumlah_item = jumlah_item + 1, harga_total = jumlah_item * ? WHERE id=?";
            $stmt_update_keranjang = $conn->prepare($query_update_keranjang);

            // Periksa apakah prepared statement berhasil dibuat
            if ($stmt_update_keranjang === FALSE) {
                die("Error in prepared statement (update_keranjang): " . $conn->error);
            }

            $harga_satuan = $row_stok['harga_satuan']; // Ambil harga_satuan dari stok
            $stmt_update_keranjang->bind_param("ds", $harga_satuan, $id_produk);
            $stmt_update_keranjang->execute();

            // Periksa apakah eksekusi prepared statement berhasil
            if ($stmt_update_keranjang === FALSE) {
                die("Error in execute (update_keranjang): " . $stmt_update_keranjang->error);
            }
        } else {
            // Jika item belum ada di keranjang, tambahkan item baru ke keranjang
            $query_insert_keranjang = "INSERT INTO keranjang (id, nama_produk, jumlah_item, harga_total) VALUES (?, ?, 1, jumlah_item * ?)";
            $stmt_insert_keranjang = $conn->prepare($query_insert_keranjang);

            // Periksa apakah prepared statement berhasil dibuat
            if ($stmt_insert_keranjang === FALSE) {
                die("Error in prepared statement (insert_keranjang): " . $conn->error);
            }

            $harga_satuan = $row_stok['harga_satuan']; // Ambil harga_satuan dari stok
            $stmt_insert_keranjang->bind_param("ssd", $row_stok['id'], $row_stok['nama_produk'], $harga_satuan);
            $stmt_insert_keranjang->execute();

            // Periksa apakah eksekusi prepared statement berhasil
            if ($stmt_insert_keranjang === FALSE) {
                die("Error in execute (insert_keranjang): " . $stmt_insert_keranjang->error);
            }
        }

        // Redirect kembali ke index.php setelah pemotongan stok dan penambahan ke keranjang
        header("Location: index.php");
        exit();
    } else {
        echo "Stok produk dengan ID $id_produk habis.";
    }
} else {
    echo "Data stok dengan ID $id_produk tidak ditemukan.";
}

// Tutup koneksi setelah selesai menggunakan
$conn->close();
?>
