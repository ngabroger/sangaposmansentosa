<?php
include('../connection/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_toko = $_POST['id_toko'];
    $nama_toko = $_POST['nama_toko'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $owner = $_POST['owner'];
    $system_pembayaran = $_POST['system_pembayaran'];
    $link_toko = $_POST['link_toko'];

    $query = "UPDATE customer SET 
              nama_toko = '$nama_toko', 
              alamat = '$alamat', 
              no_hp = '$no_hp', 
              owner = '$owner', 
              system_pembayaran = '$system_pembayaran', 
              link_toko = '$link_toko' 
              WHERE id_toko = '$id_toko'";

    if ($conn->query($query) === TRUE) {
        echo "Customer updated successfully";
        header("Location: ../customer_detail.php?id=$id_toko");
    } else {
        echo "Error updating customer: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
