<?php
include('connection/db_connection.php');

$invoiceData = [];
$salesName = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_invoices'])) {
    $selectedInvoices = $_POST['selected_invoices'];
} elseif (isset($_GET['selectedItems'])) {
    $selectedInvoices = explode(',', $_GET['selectedItems']);
}

if (!empty($selectedInvoices)) {
    foreach ($selectedInvoices as $invoiceId) {
        $sql = "SELECT f.id_faktur, c.nama_toko, f.tanggal, f.total_harga, c.nama_sales, c.system_pembayaran,f.note,
                       GROUP_CONCAT(CONCAT(p.nama_product, ' (', fd.quantity, 'x)') SEPARATOR ', ') AS product
                FROM faktur f
                JOIN customer c ON f.id_toko = c.id_toko
                JOIN faktur_detail fd ON f.id_faktur = fd.id_faktur
                JOIN product p ON fd.id_product = p.id_product
                WHERE f.id_faktur = '$invoiceId'
                GROUP BY f.id_faktur";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $invoiceData[] = $row;
                $salesName = $row['nama_sales']; // Get the sales name
            }
        }
    }
}
$no = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Table Pembayaran</title>
    <?php include 'assets/header.php'; ?>
    <style>

        .table-full {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .fixed-width {
            width: 500px; /* Adjust the width as needed */
            word-wrap: break-word;
        }
        .fixed-width1{
            width: 50px; /* Adjust the width as needed */
            word-wrap: break-word;
        }
        .fixed-width2{
            width: 200px; /* Adjust the width as needed */
            word-wrap: break-word;
        }
        .barang-width {
            width: 200px; /* Adjust the width as needed */
            word-wrap: break-word;
        }
        .ttd .col-4,
        .ttd .col-3,
        .ttd .col-2 {
            border: 1px solid black;
            padding-bottom: 100px;
            text-align: center; /* Center-align text */
        }
    </style>
</head>
<style></style>
<body class="text-dark ">
    <div class="container d-flex justify-content-center p-2 text-center">
        <h1 class="fw-bold">Surat Jalan</h1>
    </div>
    <div class="container px-5 m-0 mb-2 w-35 align-items-center d-flex">
        <div class="p-2  border border-dark  ">
            <p class="fw-bold">Hari Tanggal :  <?php  echo date('Y-m-d') ?></p> 
        </div>
        <div class="p-2  border border-dark  ">
            <p  class="fw-bold">Nama Driver :  Jonggi</p> 
        </div>
    </div>

    <div class="table-responsive border border-rounded mx-5 border border-1 border-dark">
        <table class="table-full align-items-center mb-0 justify-content-center text-center">
            <thead class="">
                <tr class="border border-dark">
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark  fixed-width1">No</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark barang-width">Invoice</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark barang-width">Nama Toko</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark fixed-width2">Tanggal Faktur</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark barang-width">Barang dan jumlah</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark fixed-width2 ">System Pembayaran</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark fixed-width">Detail Barang</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">Keterangan</th>
                </tr>
            </thead>
            <tbody class="">
                <?php
                if (!empty($invoiceData)) {
                    foreach ($invoiceData as $invoice) {

                        echo "<tr >";
                        echo "<td class='border border-dark'>$no</td>";
                        echo "<td class='border border-dark'>{$invoice['id_faktur']}</td>";
                        echo "<td class='border border-dark'>{$invoice['nama_toko']}</td>";
                        echo "<td class='border border-dark'>{$invoice['tanggal']}</td>";

                        echo "<td class='text-start border border-dark'><ul>";
                        $products = explode(', ', $invoice['product']);
                        foreach ($products as $product) {
                            echo "<li><span class='badge text-bg-primary'>{$product}</span></li>";
                        }
                        echo "</ul></td>";
                        echo "<td class='border border-dark'>{$invoice['system_pembayaran']}</td>";
                        echo "<td class='border border-dark fixed-width text-start p-2'>{$invoice['note']}</td>";
                        echo "<td class='border border-dark'></td>";
                        echo "</tr>";
                        $no++;
                    }
                }
                ?>
            </tbody>
        </table>
        
    </div>

    <div class="container-fluid  mt-5 row ttd justify-content-center">
        <div class="col-4 mb-5 text-center">
            <p class="f-l border-dark border-bottom">Hormat Kami</p>
        </div>
        <div class="col-3 mb-5 text-center">
            <p class="f-l border-dark border-bottom">Gudang</p>
        </div>
        <div class="col-3 mb-5 text-center"> 
            <p class="f-l border-dark border-bottom">Driver</p>
        </div>
      
    </div>
    
    <?php include 'assets/footer.php'; ?>
</body>
</html>