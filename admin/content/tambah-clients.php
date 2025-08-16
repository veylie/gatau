<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : '';

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = mysqli_query($koneksi, "SELECT * FROM clients WHERE id = '$id'");
    $rowEdit = mysqli_fetch_assoc($query);

    $title = "Edit Clients";
} else {
    $title = "Tambah Clients";
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryGambar = mysqli_query($koneksi, "SELECT * FROM clients WHERE id = '$id'");
    $rowGambar = mysqli_fetch_assoc($queryGambar);
    $image_name = $rowGambar['image'];
    unlink("uploads/" . $image_name);
    $delete = mysqli_query($koneksi, "DELETE FROM clients WHERE id = '$id'");
    if ($delete) {
        header("location:?page=clients&hapus=berhasil");
    }
}

if (isset($_POST['simpan'])) {
    $name = $_POST['name'];
    $is_active = $_POST['is_active'];
    $image_name = $rowEdit['image'] ?? '';

    // jika gambar terupload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $path = "uploads/";

        if (!is_dir($path)) mkdir($path);

        $image_name = md5($image);
        $target_files = $path . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_files)) {
            // jika gambarnya ada maka gambar sebelumnya akan diganti oleh gambar baru
            if (!empty($rowEdit['image'])) {
                unlink($path . $rowEdit['image']);
            }
        }
    }

    if ($id) {
        // ini query update
        $update = mysqli_query($koneksi, "UPDATE clients SET image = '$image_name', name = '$name', is_active = '$is_active' WHERE id = '$id'");
        if ($update) {
            header("location:?page=clients&ubah=berhasil");
        }
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO clients ( image, name, is_active) VALUES ('$image_name', '$name', '$is_active')");
        if ($insert) {
            header("location:?page=clients&tambah=berhasil");
        }
    }
}


?>

<div class="pagetitle">
    <h1><?php echo $title; ?></h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title "><?php echo $title; ?></h5>
                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="title" class="form-label">Name</label>
                            <input type="text" class="form-control" id="title" name="name" placeholder="Masukkan Nama Anda" value="<?php echo ($id) ? $rowEdit['name'] : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="">Gambar</label>
                            <input type="file" name="image" placeholder="Masukkan Image Anda"><br>
                            <img class="mt-3"
                                src="uploads/<?php echo isset($rowEdit['image']) ? $rowEdit['image'] : null ?>" alt=""
                                width="250">
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Is Active</label>
                            <select name="is_active" id="" class="">
                                <option <?php echo ($id) ? $rowEdit['is_active'] == 1 ? 'selected' : '' : '' ?> value="1">Publish</option>
                                <option <?php echo ($id) ? $rowEdit['is_active'] == 0 ? 'selected' : '' : '' ?> value="0">Draft</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                            <a href="?page=clients" class="btn btn-secondary" onclick="history.back()">
                                ← Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>