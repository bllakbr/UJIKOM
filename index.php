<?php
include "koneksi.php";

$query_lunas = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM tb_pembayaran 
     WHERE status = 'Sudah Lunas'"
);

$data_lunas = mysqli_fetch_assoc($query_lunas);

$query_belum = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM tb_pembayaran 
     WHERE status = 'Belum Lunas'"
);

$data_belum = mysqli_fetch_assoc($query_belum);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Pembayaran SPP Siswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row min-vh-100">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark text-white p-3">

            <div class="text-center mb-4">
                <h5>
                    <i class="bi bi-person-circle"></i>
                    Menu Admin
                </h5>
            </div>

            <div class="d-grid gap-2">

                <a href="index.php"
                   class="btn btn-secondary text-start">
                    <i class="bi bi-house"></i>
                    Dashboard
                </a>

                <a href="kelas.php"
                   class="btn btn-dark text-white text-start">
                    <i class="bi bi-grid"></i>
                    Data Kelas
                </a>

                <a href="siswa.php"
                   class="btn btn-dark text-white text-start">
                    <i class="bi bi-people"></i>
                    Data Siswa
                </a>

                <a href="cek_pembayaran.php"
                   class="btn btn-dark text-white text-start">
                    <i class="bi bi-search"></i>
                    Cek Pembayaran
                </a>

                <a href="pembayaran.php"
                   class="btn btn-dark text-white text-start">
                    <i class="bi bi-credit-card"></i>
                    Pembayaran
                </a>

                <a href="bukti_pembayaran.php"
                   class="btn btn-dark text-white text-start">
                    <i class="bi bi-receipt"></i>
                    Bukti Pembayaran
                </a>

                <a href="petugas.php"
                   class="btn btn-dark text-white text-start">
                    <i class="bi bi-person"></i>
                    Data Petugas
                </a>

            </div>

        </div>


        <!-- KONTEN -->
        <div class="col-md-9 col-lg-10 bg-light p-4">

            <!-- HEADER -->
            <div class="bg-white border rounded p-3 mb-4">
                <h5 class="mb-0">
                    Selamat Datang, Bilal
                </h5>
            </div>


            <!-- JUDUL -->
            <div class="mb-4">
                <h3>
                    Dashboard
                </h3>
            </div>


            <!-- KARTU JUMLAH DATA -->
            <div class="row g-4">

                <!-- SUDAH LUNAS -->
                <div class="col-md-6">

                    <div class="card shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h6 class="text-muted">
                                        Siswa Yang Sudah Lunas
                                    </h6>

                                    <small class="text-muted">
                                        Total:
                                    </small>

                                    <h3 class="mb-0">
                                        <?php echo $data_lunas['total']; ?>
                                        Siswa
                                    </h3>

                                </div>

                                <div>
                                    <i class="bi bi-check-circle text-success fs-1"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- BELUM LUNAS -->
                <div class="col-md-6">

                    <div class="card shadow-sm h-100">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <h6 class="text-muted">
                                        Siswa Yang Belum Lunas
                                    </h6>

                                    <small class="text-muted">
                                        Total:
                                    </small>

                                    <h3 class="mb-0">
                                        <?php echo $data_belum['total']; ?>
                                        Siswa
                                    </h3>

                                </div>

                                <div>
                                    <i class="bi bi-clock text-warning fs-1"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- BAGIAN BAWAH -->
            <div class="card shadow-sm mt-5">

                <div class="card-body text-center py-5">

                    <h4 class="fw-bold">
                        APLIKASI PEMBAYARAN
                    </h4>

                    <h4 class="fw-bold">
                        SPP SEKOLAH
                    </h4>

                </div>

            </div>


            <!-- TOMBOL LOGOUT -->
            <div class="text-end mt-4">

                <a href="logout.php" class="btn btn-danger">

                    <i class="bi bi-box-arrow-right"></i>

                    Logout

                </a>

            </div>


        </div>
        <!-- AKHIR KONTEN -->

    </div>
</div>

</body>
</html>