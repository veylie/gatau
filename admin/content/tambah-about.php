<?php
$id = isset($_GET['edit']) ? $_GET['edit'] : null;

if(isset($_GET['edit'])){
    $query = mysqli_query($koneksi, "SELECT * FROM about WHERE id = '$id'");
    $rowEdit = mysqli_fetch_assoc($query);
    
    $title = "Edit Tentang Kami";
}else{
    $title = "Tambah Tentang Kami";
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $queryGambar = mysqli_query($koneksi, "SELECT id, image FROM about WHERE id='$id'");
    $rowGambar = mysqli_fetch_assoc($queryGambar);
    $image_name = $rowGambar['image'];
    unlink("uploads/" . $image_name);
    $delete = mysqli_query($koneksi, "DELETE FROM about WHERE id='$id'");

    if($delete){
        header("location:?page=about&hapus=berhasil");
    }
}

if(isset($_POST['simpan'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image_name = $rowEdit['image'] ?? '';
    $is_active = $_POST['is_active'];

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

    if($id){
        // Update
        $update = mysqli_query($koneksi, "UPDATE about SET title='$title',content='$content', is_active='$is_active', image='$image_name' WHERE id='$id'");
        if($update){
            header("location:?page=about&ubah=berhasil");
        }
    }else{
        // Create
        $insert = mysqli_query($koneksi, "INSERT INTO about (title, content, image, is_active) VALUES ('$title','$content','$image_name','$is_active')");
        if($insert){
            header("location:?page=about&tambah=berhasil");
            exit();
        }
    }
}

?>

<div class="pagetitle">
    <h1><?php echo $title?></h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $title?></h5>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="">Gambar</label>
                            <input type="file" name="image" id=""><br>
                            <small class="text-muted">)* Size: 1920 x 1088</small><br>
                            <img class="mt-3"
                                src="uploads/<?php echo isset($rowEdit['image']) ? $rowEdit['image'] : null ?>" alt=""
                                width="250">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="title" id=""
                                placeholder="Masukkan judul about" required
                                value="<?php echo ($id) ? $rowEdit['title'] : null ?>">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Isi</label>
                            <textarea name="content" id="summernote" rows="5"
                                class='form-control'><?php echo ($id) ? $rowEdit['content'] : null?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Is Active</label>
                            <select name="is_active" id="" class="form-control">
                                <option <?php echo ($id) ? $rowEdit['is_active'] == 1 ? 'selected' : null : null?>
                                    value="1">
                                    Publish
                                </option>
                                <option <?php echo ($id) ? $rowEdit['is_active'] == 0 ? 'selected' : null : null?>
                                    value="0">Draft
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                            <a href="?page=about" class="text-muted btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>