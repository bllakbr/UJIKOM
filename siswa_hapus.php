<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nisn'])) {
    $nisn = $_POST['nisn'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_siswa WHERE nisn = ?");
    mysqli_stmt_bind_param($stmt, "s", $nisn);
    try {
        mysqli_stmt_execute($stmt);
        $pesan = "Data siswa berhasil dihapus.";
    } catch (mysqli_sql_exception $exception) {
        $pesan = "Data siswa tidak dapat dihapus karena masih memiliki data pembayaran.";
    }
} else {
    $pesan = "Permintaan hapus tidak valid.";
}

header("Location: siswa.php?pesan=" . urlencode($pesan));
exit;