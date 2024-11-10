<?php
include('../connection/db_connection.php');

if (isset($_GET['id_sales'])) {
    $id_sales = $_GET['id_sales'];

    $sql = "DELETE FROM sales WHERE id_sales = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_sales);

    if ($stmt->execute()) {
        header("Location: ../sales.php");
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No id_sales provided";
}
