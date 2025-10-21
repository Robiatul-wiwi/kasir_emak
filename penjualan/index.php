<?php
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-jual.php";

$title = "Transaksi - kasir_emak";
require "../template/header.php";
require "../template/navbar.php";
require "../template/sidebar.php";

// ====================
// Ambil data barang
// ====================
$kode = @$_GET['barcode'] ? @$_GET['barcode'] : '';
if ($kode) {
  $tgl = $_GET['tgl'];
  $dataBrg = mysqli_query($koneksi, "SELECT * FROM table_barang WHERE barcode = '$kode'");
  $selectBrg = mysqli_fetch_assoc($dataBrg);
  if (!mysqli_num_rows($dataBrg)) {
    echo "<script>
            alert('Barang dengan barcode tersebut tidak ditemukan!');
            document.location = '?tgl=$tgl';
          </script>";
  }
}

// ====================
// Tambah ke keranjang
// ====================
if (isset($_POST['addbrg'])) {
  $tgl = $_POST['tglNota'];
  if (insert($_POST)) {
    echo "<script>document.location = '?tgl=$tgl';</script>";
  }
}

// ====================
// Simpan transaksi
// ====================
if (isset($_POST['simpan'])) {
  $nota = $_POST['nojual'];
  if (simpan($_POST)) {
    echo "<script>
            alert('Transaksi berhasil disimpan!');
            window.onload = function(){
                let win = window.open('../report/r-struk.php?nota=$nota', 'Struk Belanja','width=260,height=400,left=10,top=10');
                if(win){
                    win.focus();
                    window.location = 'index.php';
                }
            }
          </script>";
  } else {
    echo "<script>alert('Gagal menyimpan transaksi. Pastikan keranjang tidak kosong.');</script>";
  }
}

$nojual = generateNo();

// ====================
// Hapus barang dari keranjang
// ====================
if (isset($_GET['hapus'])) {
  deleteBarang($_GET['hapus']);
  echo "<script>document.location='?tgl=" . date('Y-m-d') . "';</script>";
}
?>

<!-- ==================== -->
<!-- Tampilan Halaman     -->
<!-- ==================== -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Penjualan Barang</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Tambah Penjualan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section>
    <div class="container-fluid">
      <form action="" method="post">
        <div class="row">
          <!-- ================= Header Transaksi ================= -->
          <div class="col-lg-6">
            <div class="card card-outline card-warning p-3">
              <div class="form-group row mb-2">
                <label for="noNota" class="col-sm-2 col-form-label">No Nota</label>
                <div class="col-sm-4">
                  <input type="text" name="nojual" class="form-control" id="noNota" value="<?= $nojual ?>" readonly>
                </div>
                <label for="tglNota" class="col-sm-2 col-form-label">Tgl Nota</label>
                <div class="col-sm-4">
                  <input type="date" name="tglNota" class="form-control" id="tglNota"
                         value="<?= @$_GET['tgl'] ? $_GET['tgl'] : date('Y-m-d') ?>" required>
                </div>
              </div>
              <div class="form-group row mb-2">
                <label for="barcode" class="col-sm-2 col-form-label">Barcode</label>
                <div class="col-sm-10 input-group">
                  <input type="text" name="barcode" id="barcode"
                         value="<?= @$_GET['barcode'] ? $_GET['barcode'] : '' ?>"
                         class="form-control" placeholder="Masukkan barcode barang">
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card card-outline card-danger pt-3 px-3 pb-2">
              <h6 class="font-weight-bold text-right">Total Penjualan</h6>
              <h1 class="font-weight-bold text-right" style="font-size:40px;">
                <?= isset($_SESSION['keranjang_jual']) ? number_format(array_sum(array_column($_SESSION['keranjang_jual'], 'jumlah')),0,',','.') : 0 ?>
              </h1>
              <input type="hidden" name="total" id="total"
       value="<?= isset($_SESSION['keranjang_jual']) ? array_sum(array_column($_SESSION['keranjang_jual'], 'jumlah')) : 0 ?>">
            </div>
          </div>
        </div>

        <!-- ================= Form Tambah Barang ================= -->
        <div class="card pt-1 pb-2 px-3">
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label for="namaBrg">Nama Barang</label>
                <input type="text" name="namaBrg" class="form-control form-control-sm" id="namaBrg"
                       value="<?= @$_GET['barcode'] ? $selectBrg['nama_barang'] : '' ?>" readonly>
              </div>
            </div>
            <div class="col-lg-1">
              <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" class="form-control form-control-sm" id="stok"
                       value="<?= @$_GET['barcode'] ? $selectBrg['stock'] : '' ?>" readonly>
              </div>
            </div>
            <div class="col-lg-1">
              <div class="form-group">
                <label for="satuan">Satuan</label>
                <input type="text" name="satuan" class="form-control form-control-sm" id="satuan"
                       value="<?= @$_GET['barcode'] ? $selectBrg['satuan'] : '' ?>" readonly>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" name="harga" class="form-control form-control-sm" id="harga"
                       value="<?= @$_GET['barcode'] ? $selectBrg['harga_jual'] : '' ?>" readonly>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <label for="qty">Qty</label>
                <input type="number" name="qty" class="form-control form-control-sm" id="qty"
                       value="<?= @$_GET['barcode'] ? 1 : '' ?>">
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group">
                <label for="jmlHarga">Jumlah Harga</label>
                <input type="number" name="jmlHarga" class="form-control form-control-sm" id="jmlHarga"
                       value="<?= @$_GET['barcode'] ? $selectBrg['harga_jual'] : '' ?>" readonly>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-sm btn-info btn-block" name="addbrg">
            <i class="fas fa-cart-plus fa-sm"></i> Tambah Barang
          </button>
        </div>

        <!-- ================= Tabel Keranjang ================= -->
        <div class="card card-outline card-success table-responsive px-2">
          <table class="table table-sm table-hover text-nowrap">
            <thead>
              <tr>
                <th>No</th>
                <th>Barcode</th>
                <th>Nama Barang</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Jumlah Harga</th>
                <th class="text-center" width="10%">Operasi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (isset($_SESSION['keranjang_jual']) && count($_SESSION['keranjang_jual']) > 0) {
                $no = 1;
                foreach ($_SESSION['keranjang_jual'] as $key => $detail) { ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $detail['barcode'] ?></td>
                    <td><?= $detail['nama_barang'] ?></td>
                    <td class="text-right"><?= number_format($detail['harga'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= $detail['qty'] ?></td>
                    <td class="text-right"><?= number_format($detail['jumlah'], 0, ',', '.') ?></td>
                    <td class="text-center">
                      <a href="?hapus=<?= $key ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin hapus barang ini?')">
                        <i class="fas fa-trash"></i>
                      </a>
                    </td>
                  </tr>
              <?php }
              } else { ?>
                <tr><td colspan="7" class="text-center text-muted">Keranjang kosong</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>

        <!-- ================= Form Footer ================= -->
        <div class="row">
          <div class="col-lg-4 p-2">
            <div class="form-group row mb-2">
              <label for="customer" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
              <div class="col-sm-9">
                <select name="customer" id="customer" class="form-control form-control-sm">
                  <option value="">Umum</option>
                  <?php
                  $customers = getData("SELECT * FROM tbl_customer");
                  foreach ($customers as $customer) { ?>
                    <option value="<?= htmlspecialchars($customer['nama']) ?>">
                      <?= htmlspecialchars($customer['nama']) ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group row mb-2">
              <label for="keterangan" class="col-sm-3 col-form-label col-form-label-sm">Keterangan</label>
              <div class="col-sm-9">
                <textarea name="keterangan" id="keterangan" class="form-control form-control-sm"
                          rows="2" placeholder="Masukkan keterangan..."></textarea>
              </div>
            </div>
          </div>

          <div class="col-lg-4 py-2 px-3">
            <div class="form-group row mb-2">
              <label for="bayar" class="col-sm-3 col-form-label col-form-label-sm">Bayar</label>
              <div class="col-sm-9">
                <input type="number" name="bayar" id="bayar"
                       class="form-control form-control-sm text-right">
              </div>
            </div>
            <div class="form-group row mb-2">
              <label for="kembalian" class="col-sm-3 col-form-label col-form-label-sm">Kembalian</label>
              <div class="col-sm-9">
                <input type="number" name="kembalian" id="kembalian"
                       class="form-control form-control-sm text-right" readonly>
              </div>
            </div>
          </div>

          <div class="col-lg-4 p-2">
            <button type="submit" name="simpan" id="simpan"
                    class="btn btn-primary btn-sm btn-block">
              <i class="fa fa-save"></i> Simpan Transaksi
            </button>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!-- ================= Script Perhitungan ================= -->
  <script>
    let barcode = document.getElementById('barcode');
    let tgl = document.getElementById('tglNota');
    let qty = document.getElementById('qty');
    let harga = document.getElementById('harga');
    let jmlHarga = document.getElementById('jmlHarga');
    let bayar = document.getElementById('bayar');
    let kembalian = document.getElementById('kembalian');
    let total = document.getElementById('total');

    barcode.addEventListener('change', function () {
      document.location.href = '?barcode=' + barcode.value + '&tgl=' + tgl.value;
    });

    qty.addEventListener('input', function () {
      jmlHarga.value = qty.value * harga.value;
    });

    bayar.addEventListener('input', function () {
      let totalBelanja = parseFloat(total.value) || 0;
      let bayarUser = parseFloat(bayar.value) || 0;
      let kembali = bayarUser - totalBelanja;
      kembalian.value = kembali > 0 ? kembali : 0;
    });
  </script>

<?php require "../template/footer.php"; ?>
