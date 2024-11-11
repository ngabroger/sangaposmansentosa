<?php
include('../connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_faktur = $_POST['id_faktur'];
    $total_harga = $_POST['total_harga'];

    // Update the total price in the faktur table
    $sql = "UPDATE faktur SET total_harga = ? WHERE id_faktur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ds', $total_harga, $id_faktur);

    if ($stmt->execute()) {
        echo "Price updated successfully.";
    } else {
        echo "Error updating price: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
