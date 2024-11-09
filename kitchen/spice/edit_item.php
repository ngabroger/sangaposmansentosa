<?php
include('../connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_product = $_POST['id_product'];
    $nama_product = $_POST['nama_product'];
    $type_product = $_POST['type_product'];
    $price = $_POST['price'];
    $price_luarkota = $_POST['price_luarkota'];
    $amount = $_POST['amount'];

    $total = $price * $amount;

    $query = "UPDATE product SET 
                nama_product = '$nama_product', 
                type_product = '$type_product', 
                price = '$price', 
                price_luarkota = '$price_luarkota', 
                amount = '$amount',
                total = '$total'
              WHERE id_product = '$id_product'";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Product updated successfully!');
                window.location.href = '../item.php';
              </script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
