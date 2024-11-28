<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient = $_POST['recipient'];
    $message = "ID Transaksi: {$_POST['id_transaksi']}\n";
    $message .= "Nama Toko: {$_POST['nama_toko']}\n";
    $message .= "Jenis Barang: {$_POST['jenis_barang']}\n";
    $message .= "Sistem Pembayaran: {$_POST['sistem_pembayaran']}\n";
    $message .= "Status: {$_POST['status']}\n";
    $message .= "Point: {$_POST['point']}\n";

    $url = "https://api.whatsapp.com/send?phone=$recipient&text=" . urlencode($message);
    header("Location: $url");
    exit();
}
?>