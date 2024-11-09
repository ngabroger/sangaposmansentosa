<?php
include('../connection/db_connection.php');

if (isset($_GET['id'])) {
    $id_toko = $_GET['id'];
    $query = "DELETE FROM customer WHERE id_toko = '$id_toko'";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Customer deleted successfully'); window.location.href = '../customer_subscribe.php';</script>";
    } else {
        echo "<script>alert('Error deleting customer: " . $conn->error . "'); window.location.href = '../customer_list.php';</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('No customer ID provided.'); window.location.href = '../customer_list.php';</script>";
}
