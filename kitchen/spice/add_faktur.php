<?php
include '../connection/db_connection.php'; // Koneksi ke database

// Generate ID Faktur dengan format IVC/XX/XX/YYYY
function generateFakturId()
{
    $part1 = sprintf("%02d", rand(0, 99));
    $part2 = sprintf("%02d", rand(0, 99));
    $part3 = sprintf("%04d", rand(0, 9999));
    return "IVC/$part1/$part2/$part3";
}

$idFaktur = generateFakturId();
$idToko = $_POST['id_toko'];
$tanggal = $_POST['tanggal_customer'];
$note = $_POST['note'];
$totalHarga = $_POST['total_harga'];
$discount = $_POST['discount']; // Change this line to get discount from POST data

// Mulai transaksi
$conn->begin_transaction();

try {
    // Insert data faktur ke tabel faktur
    $sqlFaktur = "INSERT INTO faktur (id_faktur, id_toko, tanggal, note, total_harga, discount) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlFaktur);
    $stmt->bind_param("ssssdd", $idFaktur, $idToko, $tanggal, $note, $totalHarga, $discount); // Ensure the types and order match the SQL query
    $stmt->execute();

    // Insert tiap barang ke tabel faktur_detail
    foreach ($_POST['jenis_barang'] as $index => $idBarang) {
        $quantity = $_POST['jumlah'][$index];
        $harga = $_POST['harga'][$index]; // Pastikan harga juga dikirimkan dari frontend

        // Check if idBarang exists in product table
        $sqlCheckProduct = "SELECT COUNT(*) FROM product WHERE id_product = ?";
        $stmtCheckProduct = $conn->prepare($sqlCheckProduct);
        $stmtCheckProduct->bind_param("s", $idBarang);
        $stmtCheckProduct->execute();
        $stmtCheckProduct->bind_result($productCount);
        $stmtCheckProduct->fetch();
        $stmtCheckProduct->close();

        if ($productCount == 0) {
            throw new Exception("Product with id $idBarang does not exist.");
        }

        $sqlDetail = "INSERT INTO faktur_detail (id_faktur, id_product, quantity, harga) VALUES (?, ?, ?, ?)";
        $stmtDetail = $conn->prepare($sqlDetail);
        $stmtDetail->bind_param("ssii", $idFaktur, $idBarang, $quantity, $harga);
        $stmtDetail->execute();
    }

    // Commit transaksi
    $conn->commit();
    echo "<script>alert('Berhasil Ditambahkan.');window.location='../faktur_history.php';</script>";
    echo "Faktur berhasil disimpan!";
} catch (Exception $e) {
    // Rollback jika terjadi error
    $conn->rollback();
    echo "Gagal menyimpan faktur: " . $e->getMessage();
}

$conn->close();
