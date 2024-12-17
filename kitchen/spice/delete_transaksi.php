<?php
include('../connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];

    $query = "DELETE FROM transaksi_penjualan WHERE ID_Transaksi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_transaksi);

    if ($stmt->execute()) {
        header("Location: ../transaksi_tagihan.php?message=Transaksi deleted successfully");
    } else {
        header("Location: ../transaksi_tagihan.php?message=Error deleting transaksi");
    }

    $stmt->close();
    $conn->close();
}
?>
