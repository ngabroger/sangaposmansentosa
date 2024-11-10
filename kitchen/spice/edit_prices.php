
<?php
include 'connection/db_connection.php'; // Koneksi ke database

$idFaktur = $_POST['id_faktur'];
$prices = $_POST['prices'];

foreach ($prices as $idProduct => $price) {
    $sql = "UPDATE product SET price = ? WHERE id_product = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $price, $idProduct);
    $stmt->execute();
}

header("Location: detail_faktur.php?id_faktur=$idFaktur");
?>