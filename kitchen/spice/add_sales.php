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

    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO sales (id_faktur, tanggal_faktur, nama_sales, nama_toko, tanggal_penagihan, nominal_faktur, nominal_bayar, keterangan) 
            VALUES ('$id_faktur', '$tanggal_faktur', '$nama_sales', '$nama_toko', '$tanggal_penagihan', '$nominal_faktur', '$nominal_bayar', '$keterangan')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Berhasil Ditambahkan.');window.location='../sales.php';</script>";
        header("Location: ../sales.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Function to update or insert into grouped_sales
    function updateGroupedSales($conn, $id_faktur, $nominal_bayar) {
        // Check if the id_faktur exists in grouped_sales
        $checkSql = "SELECT * FROM grouped_sales WHERE id_faktur = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("s", $id_faktur);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If exists, update the total_nominal_bayar
            $updateSql = "UPDATE grouped_sales SET total_nominal_bayar = total_nominal_bayar + ? WHERE id_faktur = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("ds", $nominal_bayar, $id_faktur);
        } else {
            // If not exists, insert a new record
            $insertSql = "INSERT INTO grouped_sales (id_faktur, total_nominal_bayar) VALUES (?, ?)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bind_param("sd", $id_faktur, $nominal_bayar);
        }
        $stmt->execute();
    }

    // Call the function to update or insert into grouped_sales
    updateGroupedSales($conn, $id_faktur, $nominal_bayar);

    $conn->close();
}
?>
