<?php
include('../connection/db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_toko = $_POST['id_toko'];
    $nama_toko = $_POST['nama_toko'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $owner = $_POST['owner'];

    $query = $conn->prepare("INSERT INTO customer (id_toko, nama_toko, alamat, no_hp, owner) VALUES (?, ?, ?, ?, ?)");

    $query->bind_param("sssss", $id_toko, $nama_toko, $alamat, $no_hp, $owner);

    if ($query->execute()) {
        echo "<script>alert('Berhasil Ditambahkan.');window.location='../customer_subscribe.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data Gagal Ditambahkan.');window.location='../customer_subscribe.php';</script>";
        exit();
    }

} else {
    header("Location: ../item.php");
    exit();
}
?>