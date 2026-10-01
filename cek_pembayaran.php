<?php
include "koneksi.php";

$hasil = null;
$sudah_dicek = false;

if (isset($_POST['cek'])) {
    $sudah_dicek = true;
    $cari = trim($_POST['nisn'] ?? '');
    $kata_cari = "%" . $cari . "%";
    $query = mysqli_prepare(
        $koneksi,
        "SELECT
            s.nisn, s.nis, s.nama, s.no_telp,
            p.status, p.tgl_bayar, p.tgl_terakhir_bayar,
            p.batas_pembayaran, p.jumlah_bulan,
            p.nominal_bayar, p.jumlah_bayar, p.kembalian
        FROM tb_siswa s
        LEFT JOIN tb_pembayaran p
            ON s.nisn = p.nisn
        WHERE s.nisn = ? OR s.nama LIKE ?
        ORDER BY (s.nisn = ?) DESC,
            p.tgl_bayar DESC
        LIMIT 1"
    );
    mysqli_stmt_bind_param($query, "sss", $cari, $kata_cari, $cari);
    mysqli_stmt_execute($query);
    $hasil = mysqli_fetch_assoc(mysqli_stmt_get_result($query));
}

$lunas = mysqli_query(
    $koneksi,
    "SELECT s.nisn, s.nama, p.tgl_bayar, p.tgl_terakhir_bayar, p.jumlah_bulan, p.status
    FROM tb_siswa s
    INNER JOIN tb_pembayaran p ON s.nisn = p.nisn
    WHERE p.status = 'Sudah Lunas'"
);

$belum = mysqli_query(
    $koneksi,
    "SELECT s.nisn, s.nama, p.tgl_bayar, p.tgl_terakhir_bayar, p.jumlah_bulan, p.status
    FROM tb_siswa s
    INNER JOIN tb_pembayaran p ON s.nisn = p.nisn
    WHERE p.status = 'Belum Lunas'"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pembayaran - Aplikasi Pembayaran SPP</title>
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
                <a href="cek_pembayaran.php" class="btn btn-secondary text-start">
                    <i class="bi bi-search"></i> Cek Pembayaran
                </a>
                <a href="pembayaran.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-credit-card"></i> Pembayaran
                </a>
                <a href="bukti_pembayaran.php" class="btn btn-dark text-white text-start">
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
                <h5 class="mb-0">Cek Pembayaran</h5>
            </div>

            <!-- JUDUL -->
            <div class="mb-4">
                <h3>Cek Pembayaran</h3>
            </div>

            <!-- PENCARIAN -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="POST">
                        <label class="form-label fw-bold">Cari pembayaran berdasarkan nama atau NISN</label>
                        <div class="input-group mb-3 w-50">
                            <input type="text" name="nisn" class="form-control" placeholder="Masukkan nama atau NISN" required>
                            <button type="submit" name="cek" class="btn btn-secondary">
                                <i class="bi bi-search"></i> Cek Pembayaran
                            </button>
                        </div>
                    </form>

                    <?php if ($hasil) { ?>
                        <hr>
                        <h6 class="fw-bold text-center mb-3">Data Hasil Pencarian</h6>
                        <table class="table table-bordered table-striped w-75 mx-auto">
                            <tr>
                                <th width="30%">NISN</th>
                                <td><?php echo htmlspecialchars($hasil['nisn']); ?></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td><?php echo htmlspecialchars($hasil['nama']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if ($hasil['status'] === null) { ?>
                                        <span class="badge bg-secondary">Belum ada pembayaran</span>
                                    <?php } elseif($hasil['status'] == 'Sudah Lunas') { ?>
                                        <span class="badge bg-success"><?php echo htmlspecialchars($hasil['status']); ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($hasil['status']); ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Bayar</th>
                                <td><?php echo htmlspecialchars($hasil['tgl_bayar'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th>Jumlah Bulan</th>
                                <td><?php echo htmlspecialchars($hasil['jumlah_bulan'] ?? '-'); ?></td>
                            </tr>
                        </table>
                    <?php } elseif ($sudah_dicek) { ?>
                        <div class="alert alert-warning mb-0">Siswa dengan nama atau NISN tersebut tidak ditemukan.</div>
                    <?php } ?>
                </div>
            </div>

            <!-- DATA LUNAS & BELUM LUNAS -->
            <div class="row g-4">
                
                <!-- SUDAH LUNAS -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="mb-3 text-center fw-bold">
                                <i class="bi bi-check-circle text-success"></i> Siswa Yang Sudah Lunas
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-striped text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NISN</th>
                                            <th>Nama</th>
                                            <th>Tgl Bayar</th>
                                            <th>Bulan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($lunas)) { ?>
                                        <tr>
                                            <td><?php echo $row['nisn']; ?></td>
                                            <td><?php echo $row['nama']; ?></td>
                                            <td><?php echo $row['tgl_bayar']; ?></td>
                                            <td><?php echo $row['jumlah_bulan']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BELUM LUNAS -->
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="mb-3 text-center fw-bold">
                                <i class="bi bi-clock text-warning"></i> Siswa Yang Belum Lunas
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-striped text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NISN</th>
                                            <th>Nama</th>
                                            <th>Tgl Bayar</th>
                                            <th>Bulan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($belum)) { ?>
                                        <tr>
                                            <td><?php echo $row['nisn']; ?></td>
                                            <td><?php echo $row['nama']; ?></td>
                                            <td><?php echo $row['tgl_bayar']; ?></td>
                                            <td><?php echo $row['jumlah_bulan']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- AKHIR KONTEN -->
    </div>
</div>

</body>
</html>