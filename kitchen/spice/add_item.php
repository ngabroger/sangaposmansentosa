<?php
include('../connection/db_connection.php');

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $id_product = $_POST['id_product'];
    $nama_product = $_POST['nama_product'];
    $type_product = $_POST['type_product'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $total = $price * $amount;

    $query = $conn->prepare("INSERT INTO product (id_product, nama_product, type_product, price, amount, total) VALUES (?, ?, ?, ?, ?, ?)");

    $query->bind_param("ssssss", $id_product, $nama_product, $type_product, $price, $amount, $total);

    if ($query->execute()) {
        echo "<script>alert('Berhasil Ditambahkan.');window.location='../item.php';</script>";
        header("Location: ../item.php");
        exit();
    } else {
        echo "<script>alert('Data Gagal Ditambahkan.');window.location='../item.php';</script>";
        echo "Gagal menambahkan data";
    }

} else {
    header("Location: ../item.php");
    exit();
}


?>