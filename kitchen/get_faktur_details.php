<?php
include('connection/db_connection.php');

$id_faktur = $_GET['id_faktur'];
$sql = "SELECT f.tanggal AS tanggal_faktur, c.nama_toko, c.nama_sales, f.total_harga AS nominal_faktur, 
        (f.total_harga - COALESCE(SUM(s.nominal_bayar), 0)) AS sisa_tagihan
        FROM faktur f
        JOIN customer c ON f.id_toko = c.id_toko
        LEFT JOIN sales s ON f.id_faktur = s.id_faktur
        WHERE f.id_faktur = '$id_faktur'
        GROUP BY f.id_faktur, c.nama_toko, c.nama_sales, f.total_harga";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}
