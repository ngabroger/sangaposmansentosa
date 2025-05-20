<?php
include '../connection/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_toko = $_POST['id_toko'];
    $nama_toko = $_POST['nama_toko'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $owner = $_POST['owner'];
    $system_pembayaran = $_POST['system_pembayaran'];
    $link_alamat = $_POST['link_alamat'];
    $description = $_POST['description'];
    $nama_sales = $_POST['nama_sales'];
    $area_lokasi = $_POST['area_lokasi'];
    $current_date = date('Y-m-d'); // Current date as default value for recent_date_order

    $query = $conn->prepare("INSERT INTO customer (id_toko, nama_toko, alamat, no_hp, owner, system_pembayaran, link_toko, description, nama_sales, area_lokasi, recent_date_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $query->bind_param("sssssssssss", $id_toko, $nama_toko, $alamat, $no_hp, $owner, $system_pembayaran, $link_alamat, $description, $nama_sales, $area_lokasi, $current_date);

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
