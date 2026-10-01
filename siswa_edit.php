<?php
include "koneksi.php";

$nisn_lama = $_POST['nisn_lama'] ?? $_GET['nisn'] ?? '';
$stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_siswa WHERE nisn = ?");
mysqli_stmt_bind_param($stmt, "s", $nisn_lama);
mysqli_stmt_execute($stmt);
$siswa = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

$pesan = "";
$kelas = mysqli_query($koneksi, "SELECT id_kelas, nama_kelas FROM tb_kelas ORDER BY nama_kelas");
$daftar_spp = mysqli_query($koneksi, "SELECT id_spp, tahun, nominal FROM tb_spp ORDER BY tahun DESC");
if (isset($_POST['simpan'])) {
    $nisn = trim($_POST['nisn'] ?? '');
    $nis = trim($_POST['nis'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $id_kelas = $_POST['id_kelas'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $id_spp = trim($_POST['id_spp'] ?? '');
    $kelas_stmt = mysqli_prepare($koneksi, "SELECT nama_kelas FROM tb_kelas WHERE id_kelas = ?");
    mysqli_stmt_bind_param($kelas_stmt, "s", $id_kelas);
    mysqli_stmt_execute($kelas_stmt);
    $data_kelas = mysqli_fetch_assoc(mysqli_stmt_get_result($kelas_stmt));

    if (!$data_kelas) {
        $pesan = "Kelas yang dipilih tidak ditemukan.";
    } else {
        $spp_stmt = mysqli_prepare($koneksi, "SELECT id_spp FROM tb_spp WHERE id_spp = ?");
        mysqli_stmt_bind_param($spp_stmt, "s", $id_spp);
        mysqli_stmt_execute($spp_stmt);
        if (!mysqli_fetch_assoc(mysqli_stmt_get_result($spp_stmt))) {
            $pesan = "Pilih ID SPP yang terdaftar.";
        } else {
            $nama_kelas = $data_kelas['nama_kelas'];
            $update = mysqli_prepare($koneksi, "UPDATE tb_siswa SET nisn = ?, nis = ?, nama = ?, id_kelas = ?, nama_kelas = ?, alamat = ?, no_telp = ?, id_spp = ? WHERE nisn = ?");
            mysqli_stmt_bind_param($update, "sssssssss", $nisn, $nis, $nama, $id_kelas, $nama_kelas, $alamat, $no_telp, $id_spp, $nisn_lama);
            try {
                if (mysqli_stmt_execute($update)) {
                    header("Location: siswa.php");
                    exit;
                }
            } catch (mysqli_sql_exception $exception) {
                $pesan = "Gagal mengubah data. Pastikan NISN/NIS belum digunakan.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="card mx-auto" style="max-width: 760px">
        <div class="card-header"><h1 class="h5 mb-0">Edit Data Siswa</h1></div>
        <div class="card-body">
            <?php if ($pesan !== "") { ?><div class="alert alert-danger"><?= htmlspecialchars($pesan) ?></div><?php } ?>
            <form method="post">
                <input type="hidden" name="nisn_lama" value="<?= htmlspecialchars($nisn_lama) ?>">
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">NISN</label><input class="form-control" name="nisn" value="<?= htmlspecialchars($siswa['nisn']) ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">NIS</label><input class="form-control" name="nis" value="<?= htmlspecialchars($siswa['nis']) ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Nama</label><input class="form-control" name="nama" value="<?= htmlspecialchars($siswa['nama']) ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Kelas</label><select class="form-select" name="id_kelas" required><?php while ($row = mysqli_fetch_assoc($kelas)) { ?><option value="<?= htmlspecialchars($row['id_kelas']) ?>" <?= (string)$row['id_kelas'] === (string)$siswa['id_kelas'] ? 'selected' : '' ?>><?= htmlspecialchars($row['nama_kelas']) ?></option><?php } ?></select></div>
                    <div class="col-12 mb-3"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" required><?= htmlspecialchars($siswa['alamat']) ?></textarea></div>
                    <div class="col-md-6 mb-3"><label class="form-label">No. Telp</label><input class="form-control" name="no_telp" value="<?= htmlspecialchars($siswa['no_telp']) ?>" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">SPP</label><select class="form-select" name="id_spp" required><?php while ($row = mysqli_fetch_assoc($daftar_spp)) { ?><option value="<?= htmlspecialchars($row['id_spp']) ?>" <?= (string)$row['id_spp'] === (string)$siswa['id_spp'] ? 'selected' : '' ?>><?= htmlspecialchars($row['id_spp'] . ' - ' . $row['tahun'] . ' - Rp ' . $row['nominal']) ?></option><?php } ?></select></div>
                </div>
                <button class="btn btn-primary" type="submit" name="simpan">Simpan Perubahan</button>
                <a href="siswa.php" class="btn btn-outline-secondary">Kembali</a>
            </form>
        </div>
    </div>
</main>
</body>
</html>