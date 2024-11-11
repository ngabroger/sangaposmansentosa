
<?php
include 'connection/db_connection.php'; // Koneksi ke database

$idFaktur = $_POST['id_faktur'];
$tanggal = $_POST['tanggal'];
$note = $_POST['note'];

// Update query
$sql = "UPDATE faktur SET tanggal = ?, note = ? WHERE id_faktur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $tanggal, $note, $idFaktur);

if ($stmt->execute()) {
    header("Location: detail_faktur.php?id_faktur=$idFaktur");
} else {
    echo "Error updating record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>