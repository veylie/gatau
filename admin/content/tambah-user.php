<?php
$id = isset($_GET['edit']) ? $_GET['edit'] : '';
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id'");
    $rowEdit = mysqli_fetch_assoc($query);

    $title = "Edit user";
} else {
    $title = "Tambah user";
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM users WHERE id = '$id'");

    if ($delete) {
        header("location:?page=user&hapus=berhasil");
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['nama'];
    $email = $_POST['email'];
    $password = ($_POST['password']) ? $_POST['password'] : $rowEdit['password'];

    if ($id) {
        // ini query update
        $update = mysqli_query($koneksi, "UPDATE users SET name='$name',email='$email', password='$password' WHERE id='$id'");
        if ($update) {
            header("location:?page=user&ubah=berhasil");
        }
    } else {


        $insert = mysqli_query($koneksi, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");

        if ($insert) {
            header("location:?page=user&tambah=berhasil");
        }
    }
}



?>

<div class="pagetitle">
    <h1><?php echo $title ?></h1>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $title ?></h5>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="">Nama</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Anda" required value="<?php echo ($id) ? $rowEdit['name'] : '' ?>">
                        </div>
                        <div class=" mb-3">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan Email Anda" required value="<?php echo ($id) ? $rowEdit['name'] : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan Password Anda" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>