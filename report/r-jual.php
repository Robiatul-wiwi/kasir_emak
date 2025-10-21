<?php 
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php"; 
require "../config/functions.php";

// Cek parameter tanggal
if (!isset($_GET['tgl1']) || !isset($_GET['tgl2'])) {
    echo "Parameter tanggal tidak lengkap!";
    exit();
}

$tgl1  = $_GET['tgl1'];
$tgl2  = $_GET['tgl2'];

// Ambil data penjualan berdasarkan rentang tanggal
$dataJual = getData("SELECT * FROM tbl_jual_head WHERE tgl_jual BETWEEN '$tgl1' AND '$tgl2'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
</head>
<body>
    
   <div style="text-align: center;">
     <h2 style="margin-bottom: -15px;">Rekap Laporan Penjualan</h2>
     <h2 style="margin-bottom: 15px;">kasir_emak</h2>
     <p>Periode: <?= in_date($tgl1) ?> s.d <?= in_date($tgl2) ?></p>
   </div>

   <table width="100%" cellspacing="0" cellpadding="4" border="0">
    <thead>
        <tr>
            <td colspan="5" style="height: 5px;">
                <hr style="margin-bottom: 2px; margin-left: -5px;" size="3" color="grey">
            </td>
        </tr>
        <tr>
            <th>No</th>
            <th style="width: 120px;">Tgl Penjualan</th>
            <th style="width: 120px;">No Nota</th>
            <th style="width: 300px;">Customer</th>
            <th>Total Penjualan</th>
        </tr>
        <tr>
            <td colspan="5" style="height: 5px;">
                <hr style="margin-bottom: 2px; margin-left: -5px; margin-top:1px;" size="3" color="grey">
            </td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $grandTotal = 0;
        foreach ($dataJual as $data){ 
            $grandTotal += $data['total'];
        ?>
           <tr>
              <td><?= $no++ ?></td>
              <td align="center"><?= in_date($data['tgl_jual']) ?></td>
              <td align="center"><?= $data['no_jual'] ?></td>
              <td><?= $data['customer'] ?></td>
              <td align="right"><?= number_format($data['total'],0,',','.') ?></td>
           </tr>
        <?php
        }
         ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" align="right"><b>Total Keseluruhan:</b></td>
            <td align="right"><b><?= number_format($grandTotal,0,',','.') ?></b></td>
        </tr>
        <tr>
            <td colspan="5" style="height: 5px;">
                <hr style="margin-bottom: 2px; margin-left: -5px; margin-top:1px;" size="3" color="grey">
            </td>
        </tr>
    </tfoot>
   </table>

   <script>
      // Print otomatis saat halaman dimuat
      window.onload = function() {
         window.print();
      };
   </script>

</body>
</html>
