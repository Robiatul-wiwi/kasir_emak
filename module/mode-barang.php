<?php

if (userLogin()['level'] == 3) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

// =====================
// Generate ID Barang
// =====================
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

// =====================
// Fungsi Insert Barang
// =====================
function insert($data)
{
    global $koneksi;

    if (!$data) return false;

    $id         = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode    = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name       = mysqli_real_escape_string($koneksi, $data['name']);
    $satuan     = mysqli_real_escape_string($koneksi, $data['satuan']);
    $harga_beli = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $stock      = mysqli_real_escape_string($koneksi, $data['stock']);
    $stockmin   = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $gambar     = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

    // Cek barcode ganda
    $cekBarcode = mysqli_query($koneksi, "SELECT * FROM table_barang WHERE barcode = '$barcode'");
    if (mysqli_num_rows($cekBarcode)) {
        echo '<script>alert("Kode barcode sudah ada, barang gagal ditambahkan")</script>';
        return false;
    }

    // Upload gambar barang
    if ($gambar != '') {
        $gambar = uploading(null, $id); // panggil fungsi dari functions.php
    } else {
        $gambar = 'default-brg.jpg';
    }

    // Jika gambar gagal divalidasi
    if ($gambar == '') {
        return false;
    }

    // Simpan data ke database
    $sqlBrg = "INSERT INTO table_barang 
               (id_barang, barcode, nama_barang, harga_beli, harga_jual, stock, satuan, stock_minimal, gambar)
               VALUES 
               ('$id', '$barcode', '$name', $harga_beli, $harga_jual, $stock, '$satuan', '$stockmin', '$gambar')";

    mysqli_query($koneksi, $sqlBrg);

    return mysqli_affected_rows($koneksi);
}

// =====================
// Fungsi Delete Barang
// =====================
function delete($id, $gbr)
{
    global $koneksi;

    $sqlDel = "DELETE FROM table_barang WHERE id_barang = '$id'";
    mysqli_query($koneksi, $sqlDel);

    // Hapus gambar barang (kecuali default)
    if ($gbr != 'default-brg.jpg') {
        @unlink('../asset/image/barang/' . $gbr);
    }

    return mysqli_affected_rows($koneksi);
}

// =====================
// Fungsi Update Barang
// =====================
function update($data)
{
    global $koneksi;

    if (!$data) return false;

    $id         = mysqli_real_escape_string($koneksi, $data['kode']);
    $barcode    = mysqli_real_escape_string($koneksi, $data['barcode']);
    $name       = mysqli_real_escape_string($koneksi, $data['name']);
    $satuan     = mysqli_real_escape_string($koneksi, $data['satuan']);
    $harga_beli = mysqli_real_escape_string($koneksi, $data['harga_beli']);
    $harga_jual = mysqli_real_escape_string($koneksi, $data['harga_jual']);
    $stock      = mysqli_real_escape_string($koneksi, $data['stock']);
    $stockmin   = mysqli_real_escape_string($koneksi, $data['stock_minimal']);
    $gbrLama    = mysqli_real_escape_string($koneksi, $data['oldImg']);
    $gambar     = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

    // Cek barcode lama
    $queryBarcode = mysqli_query($koneksi, "SELECT * FROM table_barang WHERE id_barang = '$id'");
    $dataBrg      = mysqli_fetch_assoc($queryBarcode);
    $curBarcode   = $dataBrg['barcode'];

    // Cek barcode baru
    $cekBarcode = mysqli_query($koneksi, "SELECT * FROM table_barang WHERE barcode = '$barcode' AND id_barang != '$id'");
    if ($barcode !== $curBarcode && mysqli_num_rows($cekBarcode)) {
        echo '<script>alert("Kode barcode sudah ada, barang gagal diperbarui")</script>';
        return false;
    }

    // Upload gambar baru jika ada
    if ($gambar != '') {
        $imgBrg = uploading(null, $id);
        if ($gbrLama != 'default-brg.jpg') {
            @unlink('../asset/image/barang/' . $gbrLama);
        }
    } else {
        $imgBrg = $gbrLama;
    }

    // Update data barang
    $sqlUpdate = "UPDATE table_barang SET 
                    barcode        = '$barcode',
                    nama_barang    = '$name',
                    harga_beli     = '$harga_beli',
                    harga_jual     = '$harga_jual',
                    stock          = '$stock',
                    satuan         = '$satuan',
                    stock_minimal  = '$stockmin',
                    gambar         = '$imgBrg'
                  WHERE id_barang = '$id'";

    mysqli_query($koneksi, $sqlUpdate);

    return mysqli_affected_rows($koneksi);
}

?>
