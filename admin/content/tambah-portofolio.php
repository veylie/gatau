<?php
$id = isset($_GET['edit']) ? $_GET['edit'] : null;

$queryCategories = mysqli_query($koneksi, "SELECT * FROM categories WHERE type='portofolio' ORDER BY id DESC");
$rowCategories = mysqli_fetch_all($queryCategories, MYSQLI_ASSOC);

if(isset($_GET['edit'])){
    $query = mysqli_query($koneksi, "SELECT * FROM portofolios WHERE id = '$id'");
    $rowEdit = mysqli_fetch_assoc($query);
    
    $title = "Edit portofolio";
}else{
    $title = "Tambah portofolio";
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $queryGambar = mysqli_query($koneksi, "SELECT id, image FROM portofolios WHERE id='$id'");
    $rowGambar = mysqli_fetch_assoc($queryGambar);
    $image_name = $rowGambar['image'];
    unlink("uploads/" . $image_name);
    $delete = mysqli_query($koneksi, "DELETE FROM portofolios WHERE id='$id'");

    if($delete){
        header("location:?page=portofolio&hapus=berhasil");
    }
}

if(isset($_POST['simpan'])){
    $title = mysqli_real_escape_string($koneksi, $_POST['title']);
    $content = mysqli_real_escape_string($koneksi, $_POST['content']);
    $client_name = $_POST['client_name'];
    $project_date = $_POST['project_date'];
    $project_url = $_POST['project_url'];
    $image_name = $rowEdit['image'] ?? '';
    $is_active = $_POST['is_active'];
    $id_category = $_POST['id_category'];

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
        $update = mysqli_query($koneksi, "UPDATE portofolios SET title='$title',content='$content', is_active='$is_active', image='$image_name', id_category = '$id_category', client_name='$client_name', project_date='$project_date', project_url='$project_url' WHERE id='$id'");
        if($update){
            header("location:?page=portofolio&ubah=berhasil");
        }
    }else{
        // Create
        $insert = mysqli_query($koneksi, "INSERT INTO portofolios (title, content, image, is_active, id_category, client_name, project_date, project_url) VALUES ('$title','$content','$image_name','$is_active', '$id_category', '$client_name', '$project_date', '$project_url')");
        if($insert){
            header("location:?page=portofolio&tambah=berhasil");
            exit();
        }
    }
}

?>

<div class="pagetitle">
    <h1><?php echo $title?></h1>
</div><!-- End Page Title -->

<section class="section">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $title?></h5>
                        <div class="mb-3">
                            <label for="" class="form-label">Gambar</label>
                            <input type="file" name="image" id=""><br>
                            <img class="mt-3"
                                src="uploads/<?php echo isset($rowEdit['image']) ? $rowEdit['image'] : null ?>" alt=""
                                width="250">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Kategori</label>
                            <select name="id_category" id="" class="form-control" required>
                                <option value="" disabled selected hidden>Pilih Kategori</option>
                                <?php foreach ($rowCategories as $val): ?>
                                <option value="<?= $val['id']; ?>"
                                    <?= ($id && $rowEdit['id_category'] == $val['id']) ? 'selected' : '' ?>>
                                    <?= $val['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="title" id=""
                                placeholder="Masukkan Judul Portofolio" required
                                value="<?php echo ($id) ? $rowEdit['title'] : null ?>">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Isi</label>
                            <textarea name="content" id="summernote" rows="5"
                                class='form-control'><?php echo ($id) ? $rowEdit['content'] : null?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Nama Client</label>
                            <input type="text" class="form-control" name="client_name" id=""
                                placeholder="Masukkan Nama Client" required
                                value="<?php echo ($id) ? $rowEdit['client_name'] : null ?>">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Tanggal Project</label>
                            <input type="date" class="form-control" name="project_date" id=""
                                placeholder="Masukkan Tanggal Project" required
                                value="<?php echo ($id) ? $rowEdit['project_date'] : null ?>">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Url Project</label>
                            <input type="url" class="form-control" name="project_url" id=""
                                placeholder="Masukkan Url Project" required
                                value="<?php echo ($id) ? $rowEdit['project_url'] : null ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $title?></h5>
                        <div class="mb-3">
                            <label for="" class="form-label">Status</label>
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
                            <a href="?page=portofolio" class="text-muted btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>