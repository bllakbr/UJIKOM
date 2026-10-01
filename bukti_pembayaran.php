<?php
include "koneksi.php";

$data = mysqli_query(
    $koneksi,
    "SELECT
        p.*,
        s.nama
     FROM tb_pembayaran p
     LEFT JOIN tb_siswa s
        ON p.nisn = s.nisn
     ORDER BY p.id_pembayaran DESC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - Aplikasi Pembayaran SPP</title>
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
                <h5><i class="bi bi-person-circle"></i> Menu Admin</h5>
            </div>
            <div class="d-grid gap-2">
                <a href="index.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a href="kelas.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-grid"></i> Data Kelas
                </a>
                <a href="siswa.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-people"></i> Data Siswa
                </a>
                <a href="cek_pembayaran.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-search"></i> Cek Pembayaran
                </a>
                <a href="pembayaran.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-credit-card"></i> Pembayaran
                </a>
                <a href="bukti_pembayaran.php" class="btn btn-secondary text-start">
                    <i class="bi bi-receipt"></i> Bukti Pembayaran
                </a>
                <a href="petugas.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-person"></i> Data Petugas
                </a>
            </div>
        </div>

        <!-- KONTEN -->
        <div class="col-md-9 col-lg-10 bg-light p-4">

            <!-- HEADER -->
            <div class="bg-white border rounded p-3 mb-4">
                <h5 class="mb-0">Bukti Pembayaran SPP</h5>
            </div>

            <!-- JUDUL -->
            <div class="mb-4">
                <h3>Bukti Pembayaran</h3>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pembayaran</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah Bulan</th>
                                    <th>Jumlah Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                                <tr>
                                    <td><?php echo $row['id_pembayaran']; ?></td>
                                    <td><?php echo $row['nisn']; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td>
                                        <?php if($row['status'] == 'Sudah Lunas') { ?>
                                            <span class="badge bg-success"><?php echo $row['status']; ?></span>
                                        <?php } else { ?>
                                            <span class="badge bg-warning text-dark"><?php echo $row['status']; ?></span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $row['tgl_bayar']; ?></td>
                                    <td><?php echo $row['jumlah_bulan']; ?></td>
                                    <td><?php echo $row['jumlah_bayar']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- AKHIR KONTEN -->
    </div>
</div>

</body>
</html>