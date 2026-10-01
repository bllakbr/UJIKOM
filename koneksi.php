<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "pembayaran_spp_siswa_db";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

?>