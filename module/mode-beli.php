<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ==============================
// Generate Nomor Pembelian Baru
// ==============================
function generateNo() {
    global $koneksi;

    $queryNo = mysqli_query($koneksi, "SELECT MAX(no_beli) AS maxno FROM tbl_beli_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row["maxno"];

    if ($maxno) {
        $noUrut = (int) substr($maxno, 2, 4);
        $noUrut++;
    } else {
        $noUrut = 1;
    }

    return 'PJ' . sprintf("%04s", $noUrut);
}



// ==============================
// Insert Detail Pembelian
// ==============================
function insert($data){
    global $koneksi;

    // ✅ Tambahkan pengecekan isset() agar tidak Notice
    $no_beli    = isset($data['no_beli']) ? mysqli_real_escape_string($koneksi, $data['no_beli']) : '';
    $tgl_beli   = isset($data['tgl_beli']) ? mysqli_real_escape_string($koneksi, $data['tgl_beli']) : '';
    $kode_brg   = isset($data['kode_brg']) ? mysqli_real_escape_string($koneksi, $data['kode_brg']) : '';
    $nama_brg   = isset($data['nama_brg']) ? mysqli_real_escape_string($koneksi, $data['nama_brg']) : '';
    $qty        = isset($data['qty']) ? (int)$data['qty'] : 0;
    $harga_beli = isset($data['harga_beli']) ? (int)$data['harga_beli'] : 0;
    $jml_harga  = isset($data['jml_harga']) ? (int)$data['jml_harga'] : 0;

    // ✅ Kalau data wajib kosong, hentikan proses dengan alert
    if (empty($no_beli) || empty($tgl_beli) || empty($kode_brg)) {
        echo "<script>
                alert('Data pembelian belum lengkap!');
                history.back();
              </script>";
        return false;
    }

    // Cek apakah barang sudah ada di detail
    if ($no_beli && $kode_brg) {
        $cekbrg = mysqli_query($koneksi, "SELECT 1 FROM tbl_beli_detail 
                                        WHERE no_beli = '$no_beli' AND kode_brg = '$kode_brg'");
        if ($cekbrg && mysqli_num_rows($cekbrg) > 0) {
            echo "<script>
                    alert('Barang sudah ada di detail, hapus dulu jika ingin ubah qty!');
                    location.replace('../pembelian/index.php?tgl=" . urlencode($tgl_beli) . "');
                </script>";
            return false;
        }
    }

    // Cek qty
    if (empty($qty) || $qty <= 0) {
        echo "<script>
                alert('Qty barang tidak boleh kosong atau nol!');
                location.replace('../pembelian/index.php?tgl=" . urlencode($tgl_beli) . "');
            </script>";
        return false;
    }

    // Insert data pembelian detail
    $sqlbeli = "INSERT INTO tbl_beli_detail 
                    (no_beli, tgl_beli, kode_brg, nama_brg, qty, harga_beli, jml_harga) 
                VALUES 
                    ('$no_beli', '$tgl_beli', '$kode_brg', '$nama_brg', '$qty', '$harga_beli', '$jml_harga')";
    mysqli_query($koneksi, $sqlbeli);

    // Update stok barang
    mysqli_query($koneksi, "UPDATE table_barang 
                            SET stock = stock + $qty 
                            WHERE id_barang = '$kode_brg'");

    return mysqli_affected_rows($koneksi);
}



// ==============================
// Hapus Detail Pembelian
// ==============================
function delete($idbrg, $idbeli, $qty){
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_beli_detail 
               WHERE kode_brg = '$idbrg' AND no_beli = '$idbeli'";
    mysqli_query($koneksi, $sqlDel);

    // rollback stok
    mysqli_query($koneksi, "UPDATE table_barang 
                            SET stock = stock - $qty 
                            WHERE id_barang = '$idbrg'");

    return mysqli_affected_rows($koneksi);
}


// ==============================
// Simpan Header Pembelian
// ==============================
function simpan($data){
    global $koneksi;

    $no_beli     = mysqli_real_escape_string($koneksi, $data['no_beli']);
    $tgl_beli        = mysqli_real_escape_string($koneksi, $data['tgl_beli']);
    $suplier    = mysqli_real_escape_string($koneksi, $data['suplier']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['ketr']);

    // Hitung total otomatis dari keranjang_beli
    $total = 0;
    if (isset($_SESSION['keranjang_beli'])) {
        $total = array_sum(array_column($_SESSION['keranjang_beli'], 'jml_harga'));
    }

    // Simpan ke tabel header
    $cek = mysqli_query($koneksi, "SELECT * FROM tbl_beli_head WHERE no_beli = '$no_beli'");
    if (mysqli_num_rows($cek) == 0) {
        $sqlbeli = "INSERT INTO tbl_beli_head (no_beli, tgl_beli, suplier, total, keterangan) 
                    VALUES ('$no_beli','$tgl_beli','$suplier','$total','$keterangan')";
        mysqli_query($koneksi, $sqlbeli);
    } else {
        $sqlbeli = "UPDATE tbl_beli_head 
                    SET tgl_beli='$tgl_beli', suplier='$suplier', total='$total', keterangan='$keterangan' 
                    WHERE no_beli='$no_beli'";
        mysqli_query($koneksi, $sqlbeli);
    }

    // Simpan semua detail barang dari keranjang_beli
    if (isset($_SESSION['keranjang_beli'])) {
        foreach ($_SESSION['keranjang_beli'] as $item) {
            $kode_brg   = mysqli_real_escape_string($koneksi, $item['id_barang']);
            $nama_brg   = mysqli_real_escape_string($koneksi, $item['nama_barang']);
            $harga_beli  = (int) $item['harga_beli'];
            $qty    = (int) $item['qty'];
            $jml_harga = (int) $item['jml_harga'];

            mysqli_query($koneksi, "INSERT INTO tbl_beli_detail (no_beli, tgl_beli, kode_brg, nama_brg, qty, harga_beli, jml_harga)
                                    VALUES ('$no_beli', '$tgl_beli', '$kode_brg', '$nama_brg', '$qty', '$harga_beli', '$jml_harga')");

            // update stok
            mysqli_query($koneksi, "UPDATE table_barang SET stock = stock + $qty WHERE id_barang = '$kode_brg'");
        }

        unset($_SESSION['keranjang_beli']); // Kosongkan keranjang_beli setelah disimpan
    }

    return mysqli_affected_rows($koneksi);
}


?>
