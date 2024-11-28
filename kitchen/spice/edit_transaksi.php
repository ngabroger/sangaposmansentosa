<?php
include('../connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $status = $_POST['status'];
    $point = $_POST['point'];

    $sql = "UPDATE transaksi_penjualan SET Status = '$status', Point = '$point' WHERE ID_Transaksi = '$id_transaksi'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil diperbarui.');window.location='../transaksi_tagihan.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
