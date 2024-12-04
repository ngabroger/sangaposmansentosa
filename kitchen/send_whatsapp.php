<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient = $_POST['recipient'];
    $message = "Nama Toko: {$_POST['nama_toko']}\n";
    $message .= "Alamat Toko: {$_POST['alamat']}\n";
    $message .= "Deskripsi: {$_POST['description']}\n";
    $message .= "Link Toko: {$_POST['link_toko']}\n";
    $message .= "Sistem Pembayaran: {$_POST['system_pembayaran']}\n";

    $url = "https://api.whatsapp.com/send?phone=$recipient&text=" . urlencode($message);
    header("Location: $url");
    exit();
}
?>