<?php
include "koneksi.php";

$pesan = "";

if (isset($_POST['simpan'])) {
    $id_pembayaran = trim($_POST['id_pembayaran'] ?? '');
    $status = $_POST['status'] ?? '';
    $nisn = $_POST['nisn'] ?? '';
    $tgl_bayar = $_POST['tgl_bayar'] ?? '';
    $tgl_terakhir_bayar = $_POST['tgl_terakhir_bayar'] ?? '';
    $batas_pembayaran = $_POST['batas_pembayaran'] ?? '';
    $jumlah_bulan = filter_var($_POST['jumlah_bulan'] ?? '', FILTER_VALIDATE_INT);
    $nominal_bayar = $_POST['nominal_bayar'] ?? '';
    $jumlah_bayar = $_POST['jumlah_bayar'] ?? '';

    if ($id_pembayaran === '' || $tgl_bayar === '' || !$jumlah_bulan || $jumlah_bulan < 1 ||
        !is_numeric($jumlah_bayar) || $jumlah_bayar < 0 ||
        !in_array($status, ['Sudah Lunas', 'Belum Lunas'], true)) {
        $pesan = "Lengkapi data pembayaran dengan nilai yang valid.";
    } else {
        $siswa_stmt = mysqli_prepare($koneksi, "SELECT s.id_spp, spp.nominal FROM tb_siswa s LEFT JOIN tb_spp spp ON s.id_spp = spp.id_spp WHERE s.nisn = ?");
        mysqli_stmt_bind_param($siswa_stmt, "s", $nisn);
        mysqli_stmt_execute($siswa_stmt);
        $siswa = mysqli_fetch_assoc(mysqli_stmt_get_result($siswa_stmt));

        if (!$siswa || !is_numeric($siswa['nominal'])) {
            $pesan = "Siswa tidak ditemukan atau belum memiliki nominal SPP yang valid.";
        } else {
            $id_spp = $siswa['id_spp'];
            $nominal_bayar = (float)$siswa['nominal'];
            $jumlah_bayar = (float)$jumlah_bayar;
            $kembalian = max(0, $jumlah_bayar - ($nominal_bayar * $jumlah_bulan));
            $tgl_terakhir_bayar = $tgl_terakhir_bayar !== '' ? $tgl_terakhir_bayar : null;
            $batas_pembayaran = $batas_pembayaran !== '' ? $batas_pembayaran : null;
            $stmt = mysqli_prepare($koneksi, "INSERT INTO tb_pembayaran (id_pembayaran, status, nisn, tgl_bayar, tgl_terakhir_bayar, batas_pembayaran, jumlah_bulan, id_spp, nominal_bayar, jumlah_bayar, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssssssddd", $id_pembayaran, $status, $nisn, $tgl_bayar, $tgl_terakhir_bayar, $batas_pembayaran, $jumlah_bulan, $id_spp, $nominal_bayar, $jumlah_bayar, $kembalian);

            try {
                mysqli_stmt_execute($stmt);
                header("Location: pembayaran.php");
                exit;
            } catch (mysqli_sql_exception $exception) {
                $pesan = "Pembayaran gagal disimpan. Pastikan ID pembayaran belum digunakan dan periksa kembali data.";
            }
        }
    }
}

$daftar_siswa = mysqli_query($koneksi, "SELECT s.nisn, s.nama, s.id_spp, spp.nominal FROM tb_siswa s LEFT JOIN tb_spp spp ON s.id_spp = spp.id_spp ORDER BY s.nama ASC");
$data = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_pembayaran ORDER BY id_pembayaran ASC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Aplikasi Pembayaran SPP</title>
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
                <a href="pembayaran.php" class="btn btn-secondary text-start">
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
                <h5 class="mb-0">Pembayaran SPP</h5>
            </div>

            <!-- JUDUL -->
            <div class="mb-4">
                <h3>Pembayaran</h3>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <?php if ($pesan !== "") { ?><div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($pesan); ?></div><?php } ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ID Pembayaran</label>
                                <input type="text" name="id_pembayaran" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">NISN</label>
                                <select name="nisn" id="nisn" class="form-select" required>
                                    <option value="">Pilih siswa</option>
                                    <?php while ($siswa_row = mysqli_fetch_assoc($daftar_siswa)) { ?>
                                        <option value="<?php echo htmlspecialchars($siswa_row['nisn']); ?>" data-spp="<?php echo htmlspecialchars($siswa_row['id_spp']); ?>" data-nominal="<?php echo htmlspecialchars($siswa_row['nominal']); ?>"><?php echo htmlspecialchars($siswa_row['nisn'] . ' - ' . $siswa_row['nama']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Belum Lunas">Belum Lunas</option>
                                    <option value="Sudah Lunas">Sudah Lunas</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Bayar</label>
                                <input type="date" name="tgl_bayar" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Terakhir Bayar</label>
                                <input type="date" name="tgl_terakhir_bayar" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Batas Pembayaran</label>
                                <input type="date" name="batas_pembayaran" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jumlah Bulan</label>
                                <input type="number" name="jumlah_bulan" id="jumlah_bulan" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ID SPP</label>
                                <input type="text" name="id_spp" id="id_spp" class="form-control" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Nominal Bayar</label>
                                <input type="number" name="nominal_bayar" id="nominal_bayar" class="form-control" min="0" step="0.01" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Jumlah Bayar</label>
                                <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kembalian</label>
                                <input type="number" name="kembalian" id="kembalian" class="form-control" readonly>
                            </div>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-primary mt-2">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Data Pembayaran</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Pembayaran</th>
                                    <th>Status</th>
                                    <th>NISN</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah Bulan</th>
                                    <th>ID SPP</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                                <tr>
                                    <td><?php echo $row['id_pembayaran']; ?></td>
                                    <td>
                                        <?php if($row['status'] == 'Sudah Lunas') { ?>
                                            <span class="badge bg-success"><?php echo $row['status']; ?></span>
                                        <?php } else { ?>
                                            <span class="badge bg-warning text-dark"><?php echo $row['status']; ?></span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $row['nisn']; ?></td>
                                    <td><?php echo $row['tgl_bayar']; ?></td>
                                    <td><?php echo $row['jumlah_bulan']; ?></td>
                                    <td><?php echo $row['id_spp']; ?></td>
                                    <td><?php echo $row['nominal_bayar']; ?></td>
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

<script>
const nisnInput = document.getElementById('nisn');
const idSppInput = document.getElementById('id_spp');
const nominalInput = document.getElementById('nominal_bayar');
const jumlahInput = document.getElementById('jumlah_bayar');
const bulanInput = document.getElementById('jumlah_bulan');
const kembalianInput = document.getElementById('kembalian');

nisnInput.addEventListener('change', () => {
    const selected = nisnInput.selectedOptions[0];
    idSppInput.value = selected.dataset.spp || '';
    nominalInput.value = selected.dataset.nominal || '';
    hitungKembalian();
});

function hitungKembalian() {
    const tagihan = Number(nominalInput.value || 0) * Number(bulanInput.value || 0);
    kembalianInput.value = Math.max(0, Number(jumlahInput.value || 0) - tagihan).toFixed(2);
}

[nominalInput, jumlahInput, bulanInput].forEach((input) => input.addEventListener('input', hitungKembalian));
</script>
</body>
</html>