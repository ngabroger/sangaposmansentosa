<?php
include('connection/db_connection.php');

$invoiceData = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_invoices'])) {
    $selectedInvoices = $_POST['selected_invoices'];
} elseif (isset($_GET['selectedItems'])) {
    $selectedInvoices = explode(',', $_GET['selectedItems']);
}

if (!empty($selectedInvoices)) {
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
$no = 1;
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
    <form method="POST" action="">
        <div class="table-responsive p-5 border border-rounded m-5">
            <table class="table align-items-center mb-0 justify-content-center text-center">
                <thead class="">
                    <tr>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice</th>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Toko</th>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Faktur</th>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Harga</th>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal Pembayaran</th>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($invoiceData)) {
                        foreach ($invoiceData as $invoice) {
                            $formattedPrice = number_format($invoice['total_harga'], 0, ',', '.');
                            echo "<tr>";
                            echo "<td>$no</td>";
                            echo "<td>{$invoice['id_faktur']}</td>";
                            echo "<td>{$invoice['nama_toko']}</td>";
                            echo "<td>{$invoice['tanggal']}</td>";
                            echo "<td>RP. {$formattedPrice}</td>";
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "</tr>";
                            $no++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </form>
    <?php include 'assets/footer.php'; ?>
</body>
</html>
