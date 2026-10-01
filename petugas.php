<?php
include "koneksi.php";

$pesan = "";
if (isset($_POST['simpan'])) {
    $id_petugas = trim($_POST['id_petugas'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama_petugas = trim($_POST['nama_petugas'] ?? '');
    $level = $_POST['level'] ?? '';

    if ($id_petugas === '' || $username === '' || $password === '' || $nama_petugas === '' || !in_array($level, ['admin', 'petugas'], true)) {
        $pesan = "Lengkapi seluruh data petugas dengan benar.";
    } else {
        $cek_username = mysqli_prepare($koneksi, "SELECT id_petugas FROM tb_petugas WHERE username = ?");
        mysqli_stmt_bind_param($cek_username, "s", $username);
        mysqli_stmt_execute($cek_username);
        if (mysqli_fetch_assoc(mysqli_stmt_get_result($cek_username))) {
            $pesan = "Username sudah digunakan.";
        } else {
            $stmt = mysqli_prepare($koneksi, "INSERT INTO tb_petugas (id_petugas, username, password, nama_petugas, level) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $id_petugas, $username, $password, $nama_petugas, $level);
            try {
                mysqli_stmt_execute($stmt);
                header("Location: petugas.php");
                exit;
            } catch (mysqli_sql_exception $exception) {
                $pesan = "Gagal menyimpan petugas. Pastikan ID petugas belum digunakan.";
            }
        }
    }
}

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_petugas ORDER BY id_petugas ASC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas - Aplikasi Pembayaran SPP</title>
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
                <a href="bukti_pembayaran.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-receipt"></i> Bukti Pembayaran
                </a>
                <a href="petugas.php" class="btn btn-secondary text-start">
                    <i class="bi bi-person"></i> Data Petugas
                </a>
            </div>
        </div>

        <!-- KONTEN -->
        <div class="col-md-9 col-lg-10 bg-light p-4">

            <!-- HEADER -->
            <div class="bg-white border rounded p-3 mb-4">
                <h5 class="mb-0">Data Petugas SPP</h5>
            </div>

            <!-- JUDUL -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Data Petugas</h3>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPetugas">
                    <i class="bi bi-plus-circle"></i> Tambah Data Petugas
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if ($pesan !== "") { ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($pesan); ?></div><?php } ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>ID Petugas</th>
                                    <th>Username</th>
                                    <th>Nama Petugas</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($data)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['id_petugas']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['nama_petugas']; ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo $row['level']; ?></span>
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

<div class="modal fade" id="modalTambahPetugas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">ID Petugas</label><input class="form-control" name="id_petugas" required></div>
                    <div class="mb-3"><label class="form-label">Username</label><input class="form-control" name="username" required></div>
                    <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
                    <div class="mb-3"><label class="form-label">Nama Petugas</label><input class="form-control" name="nama_petugas" required></div>
                    <div class="mb-3"><label class="form-label">Level</label><select class="form-select" name="level" required><option value="">Pilih level</option><option value="admin">Admin</option><option value="petugas">Petugas</option></select></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan Petugas</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>