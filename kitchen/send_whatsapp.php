
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient = $_POST['recipient'];
    $nama_toko = addslashes($_POST['nama_toko']);
    $owner = addslashes($_POST['owner']);
    $alamat = addslashes($_POST['alamat']);
    $description = addslashes($_POST['description']);
    $system_pembayaran = addslashes($_POST['system_pembayaran']);
    $link_toko = addslashes($_POST['link_toko']);
    $nama_sales = addslashes($_POST['nama_sales']);
    $tanggalsekarang = date("Y-m-d");

    $message = "*REKAP DATA TOKO* \n" .
        "```Tanggal: $tanggalsekarang\n" .
        "Nama Sales: $nama_sales\n" .
        "Nama Toko: $nama_toko\n" .
        "Nama Owner: $owner\n" .
        "Alamat: $alamat\n" .
        "Jenis Barang: $description\n" .
        "System Pembayaran: $system_pembayaran\n" .
        "Link Toko: $link_toko```";

    $url = "https://wa.me/$recipient?text=" . urlencode($message);
    header("Location: $url");
    exit();
}
?>