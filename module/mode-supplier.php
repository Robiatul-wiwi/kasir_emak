<?php

if (userlogin()['level'] == 3){
    header("location:" . $main_url . "error-page.php" );
    exit();
}

function insert($data){
    global $koneksi;

    $nama   = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);
    $deskripsi   = mysqli_real_escape_string($koneksi, $data['deskripsi']);

    $sqlSupplier = "INSERT INTO tbl_supplier VALUES (null, '$nama', '$telpon', '$deskripsi', '$alamat')";
    mysqli_query($koneksi, $sqlSupplier) or die("Error Insert: " . mysqli_error($koneksi));

    return mysqli_affected_rows($koneksi);
}

function delete($id){
    global $koneksi;

    $sqlDelete = "DELETE FROM tbl_supplier WHERE id_supplier = $id";
    mysqli_query($koneksi, $sqlDelete) or die("Error Delete: " . mysqli_error($koneksi));

    return mysqli_affected_rows($koneksi);
}

function update($data){
    global $koneksi;

    $id     = mysqli_real_escape_string($koneksi, $data['id']);
    $nama   = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);
    $alamat = mysqli_real_escape_string($koneksi, $data['alamat']);
    $deskripsi   = mysqli_real_escape_string($koneksi, $data['deskripsi']);

    $sqlSupplier = "UPDATE tbl_supplier SET
                        nama      = '$nama',
                        telpon    = '$telpon',
                        deskripsi     = '$deskripsi',
                        alamat    = '$alamat'
                    WHERE id_supplier = $id";
    mysqli_query($koneksi, $sqlSupplier) or die("Error Update: " . mysqli_error($koneksi));

    return mysqli_affected_rows($koneksi);
}
