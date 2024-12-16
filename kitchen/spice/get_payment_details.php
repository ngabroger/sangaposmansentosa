<?php
include('../connection/db_connection.php');

$id_faktur = $_GET['id_faktur'];

$sql = "SELECT tanggal_penagihan, nominal_bayar FROM sales WHERE id_faktur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_faktur);
$stmt->execute();
$result = $stmt->get_result();

$payments = array();
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
}

echo json_encode($payments);

$stmt->close();
$conn->close();
?>
