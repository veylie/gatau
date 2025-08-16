<?php

$id = isset($_GET['edit']) ? $_GET['edit'] : '';

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = mysqli_query($koneksi, "SELECT * FROM sliders WHERE id = '$id'");
    $rowEdit = mysqli_fetch_assoc($query);

    $title = "Edit Slider";
} else {
    $title = "Tambah Slider";
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $queryGambar = mysqli_query($koneksi, "SELECT * FROM sliders WHERE id = '$id'");
    $rowGambar = mysqli_fetch_assoc($queryGambar);
    $image_name = $rowGambar['image'];
    unlink("uploads/" . $image_name);
    $delete = mysqli_query($koneksi, "DELETE FROM sliders WHERE id = '$id'");
if ($delete){
    header("location:?page=slider&hapus=berhasil");
}

}

if (isset($_POST['simpan'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
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
            if (!empty($row['image'])) {
                unlink($path . $row['image']);
            }
        }
    }

    if ($id) {
        // ini query update
        $update = mysqli_query($koneksi, "UPDATE sliders SET title = '$title', description = '$description', image = '$image_name' WHERE id = '$id'");
        if ($update) {
            header("location:?page=slider&ubah=berhasil");
        }
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO sliders (title, description, image) VALUES ('$title', '$description', '$image_name')");
        if ($insert) {
            header("location:?page=slider&tambah=berhasil");
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
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan Title Anda" value="<?php echo ($id) ? $rowEdit['title'] : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea type="text" class="form-control" id="description" name="description" placeholder="Masukkan Description Anda"><?php echo ($id) ? $rowEdit['description'] : '' ?></textarea>
                            <!-- <small>- Isi Description jika ingin mengubah description</small> -->
                        </div>
                        <div class="mb-3">
                            <label for="">Gambar</label>
                            <input type="file" name="image" placeholder="Masukkan Image Anda"><br>
                            <img class="mt-3"
                                src="uploads/<?php echo isset($rowEdit['image']) ? $rowEdit['image'] : null ?>" alt=""
                                width="250">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                            <a href="?page=slider" class="btn btn-secondary" onclick="history.back()">
                                ← Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</section>