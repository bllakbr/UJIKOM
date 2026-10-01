<?php
include "koneksi.php";

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_siswa ORDER BY nisn ASC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Aplikasi Pembayaran SPP</title>
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
                <a href="siswa.php" class="btn btn-secondary text-start">
                    <i class="bi bi-people"></i> Data Siswa
                </a>
                <a href="cek_pembayaran.php" class="btn btn-dark text-white text-start">
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
                <h5 class="mb-0">Data Siswa</h5>
            </div>

            <!-- JUDUL -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Data Siswa</h3>
                <a href="siswa_tambah.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Siswa
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (isset($_GET['pesan'])) { ?><div class="alert alert-info" role="alert"><?php echo htmlspecialchars($_GET['pesan']); ?></div><?php } ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>ID Kelas</th>
                                    <th>Nama Kelas</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th>ID SPP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($data)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['nisn']; ?></td>
                                    <td><?php echo $row['nis']; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['id_kelas']; ?></td>
                                    <td><?php echo $row['nama_kelas']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>
                                    <td><?php echo $row['no_telp']; ?></td>
                                    <td><?php echo $row['id_spp']; ?></td>
                                    <td>
                                        <a href="siswa_edit.php?nisn=<?php echo $row['nisn']; ?>"
                                           class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="siswa_hapus.php" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            <input type="hidden" name="nisn" value="<?php echo htmlspecialchars($row['nisn']); ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus siswa"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
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