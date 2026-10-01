<?php
include "koneksi.php";

// ==========================================
// LOGIKA PROSES CRUD
// ==========================================

// 1. PROSES TAMBAH DATA
if (isset($_POST['tambah'])) {
    $id_kelas = trim($_POST['id_kelas'] ?? '');
    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $kompetensi = trim($_POST['kompetensi'] ?? '');

    $query = mysqli_prepare($koneksi, "INSERT INTO tb_kelas (id_kelas, nama_kelas, kompetensi) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($query, "sss", $id_kelas, $nama_kelas, $kompetensi);
    try {
        $berhasil = $id_kelas !== '' && $nama_kelas !== '' && $kompetensi !== '' && mysqli_stmt_execute($query);
    } catch (mysqli_sql_exception $exception) {
        $berhasil = false;
    }
    
    if ($berhasil) {
        echo "<script>alert('Data Kelas berhasil ditambahkan!'); window.location='kelas.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!'); window.location='kelas.php';</script>";
    }
}

// 2. PROSES EDIT DATA
if (isset($_POST['edit'])) {
    $id_kelas = $_POST['id_kelas'] ?? '';
    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $kompetensi = trim($_POST['kompetensi'] ?? '');

    $query = mysqli_prepare($koneksi, "UPDATE tb_kelas SET nama_kelas = ?, kompetensi = ? WHERE id_kelas = ?");
    mysqli_stmt_bind_param($query, "sss", $nama_kelas, $kompetensi, $id_kelas);
    $berhasil = false;
    try {
        $berhasil = mysqli_stmt_execute($query);
    } catch (mysqli_sql_exception $exception) {
        $berhasil = false;
    }
    
    if ($berhasil) {
        echo "<script>alert('Data Kelas berhasil diubah!'); window.location='kelas.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data!'); window.location='kelas.php';</script>";
    }
}

// 3. PROSES HAPUS DATA
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_kelas = $_GET['id'] ?? '';

    $query = mysqli_prepare($koneksi, "DELETE FROM tb_kelas WHERE id_kelas = ?");
    mysqli_stmt_bind_param($query, "s", $id_kelas);
    $berhasil = false;
    try {
        $berhasil = mysqli_stmt_execute($query);
    } catch (mysqli_sql_exception $exception) {
        $berhasil = false;
    }
    
    if ($berhasil) {
        echo "<script>alert('Data Kelas berhasil dihapus!'); window.location='kelas.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location='kelas.php';</script>";
    }
}

// ==========================================
// MENGAMBIL DATA UNTUK DITAMPILKAN (READ)
// ==========================================
$data = mysqli_query($koneksi, "SELECT * FROM tb_kelas ORDER BY id_kelas ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kelas - Pembayaran SPP</title>

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
                <a href="index.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a href="kelas.php" class="btn btn-secondary text-start">
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
                <a href="petugas.php" class="btn btn-dark text-white text-start">
                    <i class="bi bi-person"></i> Data Petugas
                </a>
            </div>
        </div>

        <!-- KONTEN -->
        <div class="col-md-9 col-lg-10 bg-light p-4">

            <!-- HEADER -->
            <div class="bg-white border rounded p-3 mb-4">
                <h5 class="mb-0">Halaman Data Kelas</h5>
            </div>

            <!-- JUDUL & TOMBOL TAMBAH -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Data Kelas</h3>
                <!-- Tombol Trigger Modal Tambah -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle"></i> Tambah Data
                </button>
            </div>

            <!-- TABEL KELAS -->
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>ID Kelas</th>
                                    <th>Nama Kelas</th>
                                    <th>Kompetensi</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($data)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['id_kelas']; ?></td>
                                    <td><?php echo $row['nama_kelas']; ?></td>
                                    <td><?php echo $row['kompetensi']; ?></td>
                                    <td class="text-center">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row['id_kelas']; ?>">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <!-- Tombol Hapus -->
                                        <a href="kelas.php?aksi=hapus&id=<?php echo $row['id_kelas']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kelas <?php echo $row['nama_kelas']; ?>?');">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>

                                <!-- MODAL EDIT DATA (Di-generate untuk setiap baris) -->
                                <div class="modal fade" id="modalEdit<?php echo $row['id_kelas']; ?>" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditLabel">Edit Data Kelas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="kelas.php">
                                                <div class="modal-body">
                                                    <!-- Input hidden untuk menyimpan ID kelas yang sedang di-edit -->
                                                    <input type="hidden" name="id_kelas" value="<?php echo $row['id_kelas']; ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Kelas</label>
                                                        <input type="text" class="form-control" name="nama_kelas" value="<?php echo $row['nama_kelas']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kompetensi</label>
                                                        <input type="text" class="form-control" name="kompetensi" value="<?php echo $row['kompetensi']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="edit" class="btn btn-warning">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Akhir Modal Edit -->

                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA (Cukup dibuat 1 kali di luar loop) -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Data Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="kelas.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Kelas</label>
                        <input type="text" class="form-control" name="id_kelas" placeholder="Contoh: KLS001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" placeholder="Contoh: XII RPL 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kompetensi Keahlian</label>
                        <input type="text" class="form-control" name="kompetensi" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Akhir Modal Tambah -->

<!-- Wajib tambahkan script Bootstrap Bundle untuk menjalankan Modals -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>