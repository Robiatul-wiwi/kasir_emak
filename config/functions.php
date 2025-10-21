<?php

function uploading($url = null, $name = null)
{
    $namafile   = $_FILES['image']['name'];
    $ukuran     = $_FILES['image']['size'];
    $tmp        = $_FILES['image']['tmp_name'];

    // validasi file gambar yg boleh diupload
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensiGambar      = explode('.', $namafile);
    $ekstensiGambar      = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        if ($url != null) {
            echo '<script>
                alert("File yang anda upload bukan gambar, data gagal diupdate!");
                document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
                alert("File yang anda upload bukan gambar, data gagal ditambahkan!");
            </script>';
            return false;
        }
    }

    // validasi ukuran gambar max 1 MB
    if ($ukuran > 1000000) {
        if ($url != null) {
            echo '<script>
                alert("Ukuran gambar melebihi 1 MB, data gagal diupdate!");
                document.location.href = "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
                alert("Ukuran gambar tidak boleh melebihi 1 MB!");
            </script>';
            return false;
        }
    }

    // penamaan file baru
    if ($name != null) {
        $namaFileBaru = $name . '.' . $ekstensiGambar;
    } else {
        $namaFileBaru = rand(10, 1000) . '-' . $namafile;
    }

    // folder tujuan upload
    $targetDir = '../asset/image/barang/';

    // buat folder jika belum ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // pindahkan file ke folder tujuan
    move_uploaded_file($tmp, $targetDir . $namaFileBaru);

    return $namaFileBaru;
}


// ========================================================
// Fungsi umum lainnya
// ========================================================

function getData($sql)
{
    global $koneksi;

    $result = mysqli_query($koneksi, $sql);
    $rows   = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }

    return $rows;
}


function userLogin()
{
    global $koneksi;

    if (!isset($_SESSION["ssUserPOS"])) {
        return null; // session belum ada
    }

    $userActive = mysqli_real_escape_string($koneksi, $_SESSION["ssUserPOS"]);
    $dataUser   = getData("SELECT * FROM tbl_user WHERE username = '$userActive'")[0];

    return $dataUser;
}


function userMenu()
{
    $uri_path     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments = explode('/', $uri_path);
    $menu         = $uri_segments[2];
    return $menu;
}


// ========================================================
// Menu Aktif
// ========================================================

function menuHome()
{
    return (userMenu() == 'dashboard.php') ? 'active' : null;
}

function menuSetting()
{
    return (userMenu() == 'user') ? 'menu-is-opening menu-open' : null;
}

function menuMaster()
{
    if (in_array(userMenu(), ['supplier', 'customer', 'barang'])) {
        return 'menu-is-opening menu-open';
    }
    return null;
}

function menuUser()
{
    return (userMenu() == 'user') ? 'active' : null;
}

function menuSupplier()
{
    return (userMenu() == 'supplier') ? 'active' : null;
}

function menuCustomer()
{
    return (userMenu() == 'customer') ? 'active' : null;
}

function menuBarang()
{
    return (userMenu() == 'barang') ? 'active' : null;
}

function menuBeli()
{
    return (userMenu() == 'pembelian') ? 'active' : null;
}

function menuJual()
{
    return (userMenu() == 'penjualan') ? 'active' : null;
}

function laporanStock()
{
    return (userMenu() == 'stock') ? 'active' : null;
}

function laporanBeli()
{
    return (userMenu() == 'laporan-pembelian') ? 'active' : null;
}


// ========================================================
// Fungsi tambahan
// ========================================================

function in_date($tgl)
{
    $tg  = substr($tgl, 8, 2);
    $bln = substr($tgl, 5, 2);
    $thn = substr($tgl, 0, 4);
    return $tg . "-" . $bln . "-" . $thn;
}

function omzet()
{
    global $koneksi;

    $queryOmzet = mysqli_query($koneksi, "SELECT SUM(total) AS omzet FROM tbl_jual_head");
    $data       = mysqli_fetch_assoc($queryOmzet);
    $omzet      = number_format($data['omzet'], 0, ',', '.');

    return $omzet;
}

?>
