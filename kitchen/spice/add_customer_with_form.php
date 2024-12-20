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
                    $sql = "INSERT INTO customer (id_toko, nama_toko, alamat, no_hp, owner, system_pembayaran, link_toko, description, nama_sales, area_lokasi, tanggal_pertama) 
                            VALUES ('$customer_id', '$data[2]', '$data[5]', '$data[7]', '$data[3]', '$data[4]', '$data[6]', '$data[8]', '$data[1]', '$data[9]','$data[0]')";
                    $conn->query($sql);
                } else {
                    // Update data jika nama_toko sudah ada
                    $sql = "UPDATE customer SET 
                            alamat = '$data[5]', 
                            no_hp = '$data[7]', 
                            owner = '$data[3]', 
                            system_pembayaran = '$data[4]', 
                            link_toko = '$data[6]', 
                            description = '$data[8]', 
                            nama_sales = '$data[1]', 
                            area_lokasi = '$data[9]', 
                            tanggal_pertama = '$data[0]' 
                            WHERE nama_toko = '$nama_toko'";
                    $conn->query($sql);

                    $customer_row = $result->fetch_assoc();
                    $customer_id = $customer_row['id_toko'];
                }

                // Insert or update data ke tabel transaksi_penjualan
                $timestamp = $data[0];
                $checkTransaksiQuery = "SELECT * FROM transaksi_penjualan WHERE Timestamp = '$timestamp' AND ID_Toko = '$customer_id'";
                $transaksiResult = $conn->query($checkTransaksiQuery);

                if ($transaksiResult->num_rows == 0) {
                    $sql_transaksi = "INSERT INTO transaksi_penjualan (Timestamp, ID_Toko, Jenis_Barang, Sistem_Pembayaran, Status, Point) 
                                      VALUES ('$timestamp',  '$customer_id','$data[8]' , '$data[4]', 'Belum dikirim', 0)";
                    $conn->query($sql_transaksi);
                } else {
                    $sql_transaksi = "UPDATE transaksi_penjualan SET 
                                      Sistem_Pembayaran = '$data[4]' 
                                      WHERE ID_Toko = '$customer_id'";
                    $conn->query($sql_transaksi);
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
?>
