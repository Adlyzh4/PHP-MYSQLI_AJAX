<?php

session_start();
include '../config/koneksi.php';

if (!$_SESSION['id_user']) {
    echo '<script>
    alert("anda mesti login dulu");
    window.location.href = "index.php";
    </script>
    ';
}
// jika tombol simpan/add di klik
if (isset($_POST['addSiswa'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $nis = $_POST['nis'];
    $alamat = $_POST['alamat'];
    
    // esktensi yang diperbolehkan
    $ekstensi_diperbolehkan = array('png', 'jpg');
    $gambar = $_FILES['gambar']['name'];
    $x = explode('.', $gambar);
    $ekstensi = strtolower(end($x));
    $file_tmp = $_FILES['gambar']['tmp_name'];
    if (!empty($gambar)) {
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            //upload gambarnya
            move_uploaded_file($file_tmp, '../assets/images/siswa/' . $gambar);
        }
    }

    $q = "INSERT INTO tbl_siswa(nama_lengkap, nis, alamat, gambar) VALUES (' $nama_lengkap  ', '  $nis  ', '  $alamat  ', '$gambar')";
    $connection->query($q);
}

//jika tombol hapus di klik
if (isset($_POST['hapusSiswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $q = "DELETE FROM tbl_siswa WHERE id_siswa = '$id_siswa'";
    $connection->query($q);
    echo "<script>alert('data berhasil dihapus')</script>";
}

// jika tombol edit diklik
if (isset($_POST['editSiswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $nis = $_POST['nis'];
    $alamat = $_POST['alamat'];
    $q = "UPDATE tbl_siswa SET nama_lengkap='$nama_lengkap', nis='$nis', alamat='$alamat' where id_siswa='$id_siswa'";
    $connection->query($q);
    echo "<script>alert('data berhasil diedit')</script>";
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <title>Dashboard</title>
</head>

<body>

    <div class="container" style="margin-top: 50px">
        <div class="row">

            <?php include '../assets/menu.php';
            // echo $_SERVER['SERVER_NAME'];
            ?>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <label>SISWA</label>
                        <br>
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalAdd">+ tambah data</button>
                        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAddLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">Tambah Data</div>
                                    <div class="modal-body">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Nama Lengkap</label>
                                                <input type="text" placeholder="masukan nama anda" name="nama_lengkap" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>NIS</label>
                                                <input type="text" placeholder="masukan nama NIS" name="nis" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Alamat</label>
                                                <input type="text" placeholder="masukan alamat" name="alamat" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <input type="file" placeholder="masukan foto" name="gambar" id="" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <button name="addSiswa" type="submit" class="form-control btn btn-success">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>nama lengkap</th>
                                    <th>nis</th>
                                    <th>alamat</th>
                                    <th>Foto</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = "SELECT * FROM tbl_siswa";
                                $r = $connection->query($q);
                                while ($d = mysqli_fetch_object($r)) { ?>
                                    <tr>
                                        <td>
                                            
                                            <?= $d->nama_lengkap ?> 
                                            
                                        </td>
                                        <td>
                                            <?= $d->nis ?>
                                        </td>
                                        <td>
                                            <?= $d->alamat ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($d->gambar)) { ?>
                                                <img class="img-thumbnail" style="max-width:100px" src="../assets/images/siswa/<?= $d->gambar ?>" alt="">
                                            <?php } else { ?>
                                                <img class="img-thumbnail" style="max-width:100px" src="../assets/images/siswa/no-foto.png" alt="">
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($_SESSION['level'] == 1) { ?>
                                                <form action="" method="post">
                                                    <input type="hidden" name="id_siswa" value="<?= $d->id_siswa ?>">
                                                    <button name="hapusSiswa" class="btn btn-danger">hapus
                                                </form> 
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modaledit<?= $d->id_siswa ?>">edit</button>
                                            <div class="modal fade" id="modaledit<?= $d->id_siswa ?>" tabindex="-1" role="dialog" aria-labelledby="modaleditLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">Edit data</div>
                                                        <div class="modal-body">
                                                            <form action="" method="post">
                                                                <div class="form-group">
                                                                    <label>Nama Lengkap</label>
                                                                    <input type="text" value="<?= $d->nama_lengkap ?>" placeholder="masukan nama anda" name="nama_lengkap" id="nama_lengkap" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>NIS</label>
                                                                    <input type="text" value="<?= $d->nis ?>" placeholder="masukan nama NIS" name="nis" id="nis" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Alamat</label>
                                                                    <input type="text" value="<?= $d->alamat ?>" placeholder="masukan alamat" name="alamat" id="alamat" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="hidden" name="id_siswa" value="<?= $d->id_siswa ?>">
                                                                    <button name="editSiswa" type="submit" class="form-control btn btn-success">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>