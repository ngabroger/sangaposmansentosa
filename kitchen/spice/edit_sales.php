<?php
include('../connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sales = $_POST['id_sales'];
    $nominal_bayar = $_POST['nominal_bayar'];
    $nominal_faktur = $_POST['nominal_faktur'];
    $sisa_tagihan = $nominal_faktur - $nominal_bayar;

    // Update the sales data in the database
    $sql = "UPDATE sales SET nominal_bayar = ?, sisa_tagihan = ? WHERE id_sales = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddi", $nominal_bayar, $sisa_tagihan, $id_sales);

    if ($stmt->execute()) {
        // Redirect to the sales page with a success message
        header("Location: ../sales.php?message=Sales data updated successfully");
    } else {
        // Redirect to the sales page with an error message
        header("Location: ../sales.php?error=Failed to update sales data");
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to the sales page if the request method is not POST
    header("Location: ../sales.php");
}
