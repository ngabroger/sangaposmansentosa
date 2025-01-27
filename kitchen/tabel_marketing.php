<?php
include('connection/db_connection.php');

$invoiceData = [];
$salesName = '';
$selectedInvoices = [];
$remainingAmounts = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_invoices'])) {
    $selectedInvoices = $_POST['selected_invoices'];
    $remainingAmounts = $_POST['remaining_amounts'];
    echo "<script>console.log('Selected Invoices:', " . json_encode($selectedInvoices) . ");</script>";
    echo "<script>console.log('Remaining Amounts:', " . json_encode($remainingAmounts) . ");</script>";
}

if (!empty($selectedInvoices)) {
    foreach ($selectedInvoices as $invoiceId) {
        $invoiceId = trim($invoiceId);
        $sql = "SELECT gs.id_faktur, c.nama_toko, f.tanggal, gs.total_nominal_bayar as nominal_bayar, 
                       f.total_harga, c.nama_sales
                FROM grouped_sales gs
                JOIN faktur f ON gs.id_faktur = f.id_faktur
                JOIN customer c ON f.id_toko = c.id_toko
                WHERE gs.id_faktur = '$invoiceId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['remaining_amount'] = preg_replace('/[^0-9]/', '', $remainingAmounts[$row['id_faktur']] ?? 0);
                $invoiceData[] = $row;
                $salesName = $row['nama_sales'];
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body class="text-dark">
    <div class="container mx-5 mt-5 row">
        <div class="col-6 text-start justify-content-start">
            <p class="fw-bold">Hari Tanggal : <?php echo date('Y-m-d'); ?></p> 
        </div>
        <div class="col-6 text-start justify-content-start">
            <p class="fw-bold">Nama Sales : <?php echo htmlspecialchars($salesName); ?></p> 
        </div>
    </div>

    <div class="table-responsive p-5">
        <table class="table align-items-center mb-0 justify-content-center text-center border border-dark">
            <thead class="border border-dark">
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">No</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">Invoice</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">Nama Toko</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">Tanggal Faktur</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">Total Harga</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">Nominal Pembayaran</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 border border-dark">Sisa Tagihan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoiceData)) {
                    foreach ($invoiceData as $invoice) {
                        $formattedPrice = number_format($invoice['remaining_amount'], 0, ',', '.');
                 
                        echo "<tr>";
                        echo "<td class='border border-dark'>{$no}</td>";
                        echo "<td class='border border-dark'>{$invoice['id_faktur']}</td>";
                        echo "<td class='border border-dark'>{$invoice['nama_toko']}</td>";
                        echo "<td class='border border-dark'>{$invoice['tanggal']}</td>";
                        echo "<td class='border border-dark'>RP. {$formattedPrice}</td>";
                        echo "<td class='border border-dark'></td>";
                        echo "<td class='border border-dark'></td>";
                        echo "</tr>";
                        $no++;
                    }
                } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
