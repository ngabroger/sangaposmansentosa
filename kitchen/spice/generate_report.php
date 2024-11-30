<?php
include('../connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_ids = explode(',', $_POST['selected_ids']);
    $ids = implode("','", $selected_ids);
    $query = "SELECT tp.*, c.nama_toko FROM transaksi_penjualan tp JOIN customer c ON tp.ID_Toko = c.id_toko WHERE tp.ID_Transaksi IN ('$ids')";
    $result = $conn->query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>
<body>
    <div class="container">
        <h2>BARANG YANG SUDAH DIKIRIM/ BELUM DIKIRIM</h2>
        <table id="reportTable" class="display">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Timestamp</th>
                    <th>Nama Toko</th>
                    <th>Jenis Barang</th>
                    <th>Sistem Pembayaran</th>
                    <th>Status</th>
                    <th>Point</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['ID_Transaksi']}</td>";
                        echo "<td>{$row['Timestamp']}</td>";
                        echo "<td>{$row['nama_toko']}</td>";
                        echo "<td>{$row['Jenis_Barang']}</td>";
                        echo "<td>{$row['Sistem_Pembayaran']}</td>";
                        echo "<td>{$row['Status']}</td>";
                        echo "<td>{$row['Point']}</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include jQuery and DataTables JS -->
   
    
    <script>
        $(document).ready(function() {
            $('#reportTable').DataTable();
        });
    </script>
</body>
</html>
