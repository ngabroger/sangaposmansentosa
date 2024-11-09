<?php
if (isset($_POST['submit'])) {
    include('../connection/db_connection.php');

    // Memeriksa apakah file diunggah
    if ($_FILES['csv_file']['error'] == 0) {
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');

        // Lewati baris pertama (header)
        fgetcsv($handle);

        // Membaca setiap baris CSV
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) >= 11) {  // Pastikan minimal ada 11 kolom
                $nama_toko = $data[2];

                // Cek apakah nama_toko sudah ada di database
                $checkQuery = "SELECT * FROM customer WHERE nama_toko = '$nama_toko'";
                $result = $conn->query($checkQuery);

                if ($result->num_rows == 0) {
                    // Buat customer_id baru untuk setiap baris
                    $customer_id = substr(uniqid('CS_', true), 0, 13) . rand(1000, 9999);

                    // Insert data baru jika nama_toko belum ada
                    $sql = "INSERT INTO customer (id_toko, nama_toko, alamat, no_hp, owner, system_pembayaran, link_toko, description, nama_sales, area_lokasi) 
                            VALUES ('$customer_id', '$data[2]', '$data[5]', '$data[7]', '$data[3]', '$data[4]', '$data[6]', '$data[8]', '$data[1]', '$data[9]')";
                    $conn->query($sql);
                } else {
                    echo "<script>alert('Toko dengan nama '$nama_toko' sudah ada. Baris diabaikan.');window.location='../customer_subscribe.php';</script>";
                    echo "Toko dengan nama '$nama_toko' sudah ada. Baris diabaikan.<br>";
                }
            } else {
                echo "Baris memiliki jumlah kolom yang tidak sesuai. Baris diabaikan.<br>";
            }
        }
        fclose($handle);
        echo "Data berhasil diimpor!";
        echo "<script>alert('Berhasil Ditambahkan.');window.location='../customer_subscribe.php';</script>";
        // Alihkan ke halaman ../customer_subscribe.php

        exit();  // Pastikan script berhenti setelah pengalihan
    } else {
        echo "Gagal mengunggah file.";
    }
}
