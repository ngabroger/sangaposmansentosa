<?php
include('connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_invoices'])) {
    $selectedInvoices = $_POST['selected_invoices'];
    $invoiceData = [];

    foreach ($selectedInvoices as $invoiceId) {
        $sql = "SELECT f.id_faktur, c.nama_toko, f.tanggal, f.total_harga
                FROM faktur f
                JOIN customer c ON f.id_toko = c.id_toko
                WHERE f.id_faktur = '$invoiceId'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $invoiceData[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Table Marketing</title>
    <?php include 'assets/header.php'; ?>
</head>
<body>
    <div class="table-responsive p-5 border border-rounded m-5">
        <table class="table align-items-center mb-0">
            <thead class="">
                <tr>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Toko</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Harga</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal Pembayaran</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($invoiceData)) {
                    foreach ($invoiceData as $invoice) {
                        echo "<tr>";
                        echo "<td>{$invoice['id_faktur']}</td>";
                        echo "<td>{$invoice['nama_toko']}</td>";
                        echo "<td>{$invoice['tanggal']}</td>";
                        echo "<td>{$invoice['total_harga']}</td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include 'assets/footer.php'; ?>
</body>
</html>
