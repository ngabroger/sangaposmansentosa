<?php
include('../connection/db_connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM product WHERE id_product = '$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: ../widget/modal.php?deleted=true");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>
