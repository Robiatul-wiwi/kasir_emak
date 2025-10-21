<?php

if (userlogin()['level'] == 3) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function insert($data){
    global $koneksi;

    $nama       = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon     = mysqli_real_escape_string($koneksi, $data['telpon']);
    $alamat     = mysqli_real_escape_string($koneksi, $data['alamat']);
    $deskripsi  = mysqli_real_escape_string($koneksi, $data['deskripsi']);

    $sqlCustomer = "INSERT INTO tbl_customer (nama, telpon, alamat, deskripsi) 
                    VALUES ('$nama', '$telpon', '$alamat', '$deskripsi')";
    mysqli_query($koneksi, $sqlCustomer) or die("Error Insert: " . mysqli_error($koneksi));

    return mysqli_affected_rows($koneksi);
}

function delete($id){
    global $koneksi;

    $sqlDelete = "DELETE FROM tbl_customer WHERE id_customer = $id";
    mysqli_query($koneksi, $sqlDelete) or die("Error Delete: " . mysqli_error($koneksi));

    return mysqli_affected_rows($koneksi);
}

function update($data){
    global $koneksi;

    $id         = mysqli_real_escape_string($koneksi, $data['id']);
    $nama       = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon     = mysqli_real_escape_string($koneksi, $data['telpon']);
    $alamat     = mysqli_real_escape_string($koneksi, $data['alamat']);
    $deskripsi  = mysqli_real_escape_string($koneksi, $data['deskripsi']);

    $sqlCustomer = "UPDATE tbl_customer SET
                        nama       = '$nama',
                        telpon     = '$telpon',
                        alamat     = '$alamat',
                        deskripsi  = '$deskripsi'
                    WHERE id_customer = $id";
    mysqli_query($koneksi, $sqlCustomer) or die("Error Update: " . mysqli_error($koneksi));

    return mysqli_affected_rows($koneksi);
}
?>
