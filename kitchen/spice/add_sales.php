<?php
include('../connection/db_connection.php'); // Adjusted the path to the parent directory

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_faktur = $_POST['id_faktur'];
    $tanggal_faktur = $_POST['tanggal_faktur'];
    $nama_sales = $_POST['nama_sales']; // New column
    $nama_toko = $_POST['nama_toko'];
    $tanggal_penagihan = $_POST['tanggal_penagihan'];
    $nominal_faktur = str_replace(['Rp. ', '.'], '', $_POST['nominal_faktur']);
    $nominal_bayar = str_replace(['Rp. ', '.'], '', $_POST['nominal_bayar']);
    $sisa_tagihan = str_replace(['Rp. ', '.'], '', $_POST['sisa_tagihan']);
    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO sales (id_faktur, tanggal_faktur, nama_sales, nama_toko, tanggal_penagihan, nominal_faktur, nominal_bayar, sisa_tagihan, keterangan) 
            VALUES ('$id_faktur', '$tanggal_faktur', '$nama_sales', '$nama_toko', '$tanggal_penagihan', '$nominal_faktur', '$nominal_bayar', '$sisa_tagihan', '$keterangan')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Berhasil Ditambahkan.');window.location='../sales.php';</script>";
        header("Location: ../sales.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
