<?php
// Ambil data setting dari database (hanya ambil 1 baris saja)
$querySetting = mysqli_query($koneksi, "SELECT * FROM settings LIMIT 1");
$row = mysqli_fetch_assoc($querySetting);

// Cek apakah tombol submit ditekan
if (isset($_POST['submit'])) {

    // Ambil data dari form
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $ig       = $_POST['ig'];
    $fb       = $_POST['fb'];
    $twitter  = $_POST['twitter'];
    $linkedin = $_POST['linkedin'];

    // Gunakan logo lama sebagai default jika tidak upload baru
    $logo_name = $row['logo'] ?? '';

    // ====================
    // PROSES UPLOAD LOGO
    // ====================
    if (!empty($_FILES['logo']['name'])) {
        $logo = $_FILES['logo']['name'];  // Nama file asli
        $path = "uploads/";               // Folder penyimpanan

        // Buat folder jika belum ada
        if (!is_dir($path)) mkdir($path);

        // Rename file dengan timestamp agar unik
        $logo_name = time() . "_" . basename($logo);
        $target_file = $path . $logo_name;

        // Pindahkan file dari temporary ke folder tujuan
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            // Hapus logo lama jika ada
            if (!empty($row['logo']) && file_exists($path . $row['logo'])) {
                unlink($path . $row['logo']);
            }
        }
    }

    // ====================
    // PROSES UPDATE / INSERT DATA
    // ====================
    if ($row) {
        // Jika data sudah ada → UPDATE
        $id_setting = $row['id'];

        $update = mysqli_query($koneksi, "UPDATE settings SET 
            email = '$email',
            phone = '$phone',
            address = '$address',
            ig = '$ig',
            fb = '$fb',
            twitter = '$twitter',
            logo = '$logo_name',
            linkedin = '$linkedin'
            WHERE id = '$id_setting'");

        // Jika berhasil update, redirect ke halaman setting
        if ($update) {
            header("location:?page=setting&ubah=berhasil");
            exit;
        }
    } else {
        // Jika belum ada data → INSERT
        $insert = mysqli_query($koneksi, "INSERT INTO settings 
            (email, phone, address, ig, fb, twitter, linkedin) VALUES 
            ('$email', '$phone', '$address', '$ig', '$fb', '$twitter', '$linkedin')");

        // Jika berhasil insert, redirect ke halaman setting
        if ($insert) {
            header("location:?page=setting&tambah=berhasil");
            exit;
        }
    }
}
?>

<!-- ==================================== -->
<!-- BAGIAN FORM HTML -->
<!-- ==================================== -->

<div class="pagetitle">
    <h1>Setting</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Pages</li>
            <li class="breadcrumb-item active">Setting</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">Setting</h5>

                    <!-- Form harus pakai enctype untuk upload file -->
                    <form action="" method="post" enctype="multipart/form-data">

                        <!-- Email -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">Email</label>
                            <div class="col-sm-6">
                                <input type="email" name="email" class="form-control"
                                    value="<?= $row['email'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">No Telpon</label>
                            <div class="col-sm-6">
                                <input type="number" name="phone" class="form-control"
                                    value="<?= $row['phone'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">Alamat</label>
                            <div class="col-sm-6">
                                <textarea name="address" class="form-control"><?= $row['address'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <!-- Logo -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">Logo</label>
                            <div class="col-sm-6">
                                <input type="file" name="logo" class="form-control">
                                <?php if (!empty($row['logo'])): ?>
                                    <img class="mt-2" src="uploads/<?= $row['logo'] ?>" width="100">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Twitter -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">Twitter</label>
                            <div class="col-sm-6">
                                <input type="url" name="twitter" class="form-control"
                                    value="<?= $row['twitter'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Facebook -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">Facebook</label>
                            <div class="col-sm-6">
                                <input type="url" name="fb" class="form-control"
                                    value="<?= $row['fb'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">Instagram</label>
                            <div class="col-sm-6">
                                <input type="url" name="ig" class="form-control"
                                    value="<?= $row['ig'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- LinkedIn -->
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label fw-bold">LinkedIn</label>
                            <div class="col-sm-6">
                                <input type="url" name="linkedin" class="form-control"
                                    value="<?= $row['linkedin'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="mb-3 row">
                            <div class="col-sm-2">
                                <button class="btn btn-primary" type="submit" name="submit">Simpan</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>