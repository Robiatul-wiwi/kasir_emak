<?php

if (userLogin()['level'] == 3) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

function generateId()
{
    global $koneksi;

    $queryId = mysqli_query($koneksi, "SELECT MAX(id_barang) as maxid FROM table_barang");
    if (!$queryId) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_array($queryId);
    $maxid = $data['maxid'];

    // Kalau tabel masih kosong
    if ($maxid == null) {
        $noUrut = 1;
    } else {
        $noUrut = (int) substr($maxid, 4, 3); // ambil angka setelah BRG-
        $noUrut++;
    }

    $newId = "BRG-" . sprintf("%03s", $noUrut);
    return $newId;
}

function insert($data)
{
    global $koneksi;

    $id         = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode    = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name       = mysqli_real_escape_string($koneksi, $data['name']);
    $satuan     = mysqli_real_escape_string($koneksi, $data['satuan']);
    $harga_beli = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $stockmin   = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $gambar     = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);

    // cek barcode
    $cekBarcode = mysqli_query($koneksi, "SELECT * FROM table_barang WHERE barcode = '$barcode'");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>alert("Kode barcode sudah ada, barang gagal ditambahkan")</script>';
        return false;
    }

    // upload gambar barang
    if ($gambar != null) {
        $gambar = uploading(null, $id);
    } else {
        $gambar = 'default-brg.jpg';
    }

    // gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    // query insert
    $sqlBrg = "INSERT INTO table_barang 
               (id_barang, barcode, nama_barang, harga_beli, harga_jual, stock, satuan, stock_minimal, gambar)
               VALUES 
               ('$id', '$barcode', '$name', $harga_beli, $harga_jual, 0, '$satuan', '$stockmin', '$gambar')";

    mysqli_query($koneksi, $sqlBrg);

    return mysqli_affected_rows($koneksi);
}
