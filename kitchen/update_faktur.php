<?php
include 'connection/db_connection.php'; // Koneksi ke database

$idFaktur = $_POST['id_faktur'];
$tanggal = $_POST['tanggal'];
$note = $_POST['note'];
$discount = $_POST['discount']; // Add this line to get discount from POST data

// Update query
$sql = "UPDATE faktur SET tanggal = ?, note = ?, discount = ? WHERE id_faktur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $tanggal, $note, $discount, $idFaktur); // Ensure the types and order match the SQL query

if ($stmt->execute()) {
    header("Location: detail_faktur.php?id_faktur=$idFaktur");
} else {
    echo "Error updating record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>