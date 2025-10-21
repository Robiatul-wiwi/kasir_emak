<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/config.php";
require_once "../config/functions.php";

// ========================================
// Fungsi untuk Generate Nomor Penjualan
// ========================================
function generateNo()
{
    global $koneksi;
    $queryNo = mysqli_query($koneksi, "SELECT MAX(no_jual) AS maxno FROM tbl_jual_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row['maxno'];

    if ($maxno) {
        $noUrut = (int) substr($maxno, 2, 4);
        $noUrut++;
    } else {
        $noUrut = 1;
    }

    return 'PJ' . sprintf("%04s", $noUrut);
}

// ========================================
// Tambahkan Barang ke Keranjang Penjualan
// ========================================
function insert($data)
{
    global $koneksi;

    if (!isset($_SESSION['keranjang_jual'])) {
        $_SESSION['keranjang_jual'] = [];
    }

    $kode   = mysqli_real_escape_string($koneksi, $data['barcode']);
    $nama   = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $qty    = (int)$data['qty'];
    $harga  = (int)$data['harga'];
    $stok   = (int)$data['stok'];
    $jumlah = $harga * $qty;

    // Validasi stok
    if ($qty <= 0) {
        echo "<script>alert('Qty tidak boleh nol atau kosong!');</script>";
        return false;
    } elseif ($qty > $stok) {
        echo "<script>alert('Stok barang tidak mencukupi!');</script>";
        return false;
    }

    // Tambahkan ke keranjang (gabungkan jika sudah ada barang yang sama)
    $found = false;
    foreach ($_SESSION['keranjang_jual'] as &$item) {
        if ($item['barcode'] == $kode) {
            $item['qty'] += $qty;
            $item['jumlah'] = $item['qty'] * $harga;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['keranjang_jual'][] = [
            'barcode'     => $kode,
            'nama_barang' => $nama,
            'harga'       => $harga,
            'qty'         => $qty,
            'jumlah'      => $jumlah
        ];
    }

    return true;
}

// ========================================
// Simpan Transaksi Penjualan ke Database
// ========================================
function simpan($data)
{
    global $koneksi;

    if (empty($_SESSION['keranjang_jual'])) {
        echo "<script>alert('Keranjang masih kosong!');</script>";
        return false;
    }

    $nojual     = mysqli_real_escape_string($koneksi, $data['nojual']);
    $tgl        = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $customer   = mysqli_real_escape_string($koneksi, $data['customer']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['ketr']);
    $bayar      = (int)$data['bayar'];
    $kembalian  = (int)$data['kembalian'];
    $total      = array_sum(array_column($_SESSION['keranjang_jual'], 'jumlah'));

    // Simpan ke tabel tbl_jual_head
    $queryHead = "INSERT INTO tbl_jual_head 
        (no_jual, tgl_jual, customer, total, keterangan, jml_bayar, kembalian) 
        VALUES 
        ('$nojual', '$tgl', '$customer', '$total', '$keterangan', '$bayar', '$kembalian')";
    mysqli_query($koneksi, $queryHead);

    // Simpan ke tabel tbl_jual_detail + update stok
    foreach ($_SESSION['keranjang_jual'] as $item) {
        $kode   = mysqli_real_escape_string($koneksi, $item['barcode']);
        $nama   = mysqli_real_escape_string($koneksi, $item['nama_barang']);
        $harga  = (int)$item['harga'];
        $qty    = (int)$item['qty'];
        $jumlah = (int)$item['jumlah'];

        mysqli_query($koneksi, "INSERT INTO tbl_jual_detail 
            (no_jual, tgl_jual, barcode, nama_brg, qty, harga_jual, jml_harga)
            VALUES
            ('$nojual', '$tgl', '$kode', '$nama', '$qty', '$harga', '$jumlah')");

        // Kurangi stok barang
        mysqli_query($koneksi, "UPDATE table_barang SET stock = stock - $qty WHERE barcode = '$kode'");
    }

    unset($_SESSION['keranjang_jual']); // Kosongkan keranjang
    return true;
}

// ========================================
// Hapus Barang dari Keranjang
// ========================================
function deleteBarang($index)
{
    if (isset($_SESSION['keranjang_jual'][$index])) {
        unset($_SESSION['keranjang_jual'][$index]);
        $_SESSION['keranjang_jual'] = array_values($_SESSION['keranjang_jual']);
    }
}
?>
