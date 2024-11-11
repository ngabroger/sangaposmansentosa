<?php
include '../connection/db_connection.php'; // Koneksi ke database

$idFaktur = $_GET['id_faktur'];

// Delete from faktur_detail based on id_faktur
$sqlDeleteDetails = "DELETE FROM faktur_detail WHERE id_faktur = ?";
$stmtDeleteDetails = $conn->prepare($sqlDeleteDetails);
$stmtDeleteDetails->bind_param("s", $idFaktur);
$stmtDeleteDetails->execute();

// Delete from faktur
$sqlFaktur = "DELETE FROM faktur WHERE id_faktur = ?";
$stmtFaktur = $conn->prepare($sqlFaktur);
$stmtFaktur->bind_param("s", $idFaktur);
$stmtFaktur->execute();

// Redirect to a confirmation page or back to the list
header("Location: ../faktur_history.php");
exit();
?>
