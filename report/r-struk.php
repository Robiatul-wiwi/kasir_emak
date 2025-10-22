<?php
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";

$nota = isset($_GET['nota']) ? $_GET['nota'] : '';

$dataJual = [];
$itemJual = [];

if ($nota != '') {
  // Ambil data header transaksi
  $dataJualList = getData("SELECT * FROM tbl_jual_head WHERE no_jual = '$nota'");
  $itemJual = getData("SELECT * FROM tbl_jual_detail WHERE no_jual = '$nota'");

  if (!empty($dataJualList)) {
    $dataJual = $dataJualList[0];
  }
}

// Gabungkan item duplikat (barang sama)
$uniqueItems = [];
foreach ($itemJual as $item) {
  $key = $item['nama_brg'] . '-' . $item['harga_jual'];
  if (!isset($uniqueItems[$key])) {
    $uniqueItems[$key] = $item;
  } else {
    $uniqueItems[$key]['qty'] += $item['qty'];
    $uniqueItems[$key]['jml_harga'] += $item['jml_harga'];
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Belanja</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
      margin: 0;
      padding: 0;
    }
    table {
      width: 240px;
      border-collapse: collapse;
      margin: 0 auto;
    }
    td {
      padding: 2px;
    }
    .center {
      text-align: center;
    }
    .right {
      text-align: right;
    }
    .bold {
      font-weight: bold;
    }
  </style>
</head>
<body>

  <table style="border-bottom: solid 2px;">
    <tr>
      <td class="center"><b>Kasir Emak</b></td>
    </tr>
    <tr>
      <td class="center">Jl.Jati Tanjakan, Ds.Jatiwaringin, Kec.Mauk</td>
    </tr>
    <tr>
      <td class="center"><?= 'No Nota : ' . htmlspecialchars($nota) ?></td>
    </tr>
    <tr>
      <td class="center">
        <?= isset($dataJual['tgl_jual']) ? date('d-m-Y H:i:s', strtotime($dataJual['tgl_jual'])) : '-' ?>
      </td>
    </tr>
    <tr>
      <td class="center">Kasir: <?= htmlspecialchars(userLogin()['username'] ?? '-') ?></td>
    </tr>
    <?php if (!empty($dataJual['customer'])) { ?>
    <tr>
      <td class="center">Customer: <?= htmlspecialchars($dataJual['customer']) ?></td>
    </tr>
    <?php } ?>
  </table>

  <!-- Daftar Barang -->
  <table style="border-bottom: dotted 2px;">
    <?php if (!empty($uniqueItems)) { ?>
      <?php foreach ($uniqueItems as $item) { ?>
        <tr>
          <td colspan="6"><?= htmlspecialchars($item['nama_brg']) ?></td>
        </tr>
        <tr>
          <td colspan="2">Qty:</td>
          <td class="right"><?= $item['qty'] ?></td>
          <td class="right">x <?= number_format($item['harga_jual'], 0, ',', '.') ?></td>
          <td class="right bold" colspan="2"><?= number_format($item['jml_harga'], 0, ',', '.') ?></td>
        </tr>
      <?php } ?>
    <?php } else { ?>
      <tr><td colspan="6" class="center">Tidak ada data transaksi</td></tr>
    <?php } ?>
  </table>

  <!-- Total, Bayar, Kembali -->
  <table style="border-bottom: dotted 2px;">
    <tr>
      <td colspan="3"></td>
      <td>Total</td>
      <td class="right bold"><?= number_format($dataJual['total'] ?? 0, 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td>Bayar</td>
      <td class="right"><?= number_format($dataJual['jml_bayar'] ?? 0, 0, ',', '.') ?></td>
    </tr>
    <tr>
      <td colspan="3"></td>
      <td>Kembali</td>
      <td class="right"><?= number_format($dataJual['kembalian'] ?? 0, 0, ',', '.') ?></td>
    </tr>
  </table>

  <?php if (!empty($dataJual['keterangan'])) { ?>
  <table style="margin-top: 5px;">
    <tr>
      <td><i>Keterangan: <?= htmlspecialchars($dataJual['keterangan']) ?></i></td>
    </tr>
  </table>
  <?php } ?>

  <table class="center" style="margin-top: 10px;">
    <tr>
      <td>Terima kasih sudah berbelanja 🙏</td>
    </tr>
  </table>

  <script>
    // Cetak otomatis setelah 0.3 detik
    setTimeout(function() {
      window.print();
    }, 300);
  </script>

</body>
</html>
