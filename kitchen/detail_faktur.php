<?php
include 'connection/db_connection.php'; // Koneksi ke database

$idFaktur = $_GET['id_faktur'];
$useLuarKotaPrice = isset($_GET['use_luarkota']) ? $_GET['use_luarkota'] : 0;

// Query untuk mendapatkan detail faktur
$sql = "SELECT f.id_faktur, c.nama_toko, f.tanggal, f.note, f.total_harga,
        GROUP_CONCAT(p.nama_product, ' (', fd.quantity, 'x)') AS product,
        fd.id_product, fd.quantity, p.price, p.price_luarkota
        FROM faktur f
        JOIN faktur_detail fd ON f.id_faktur = fd.id_faktur
        JOIN product p ON fd.id_product = p.id_product
        JOIN customer c ON f.id_toko = c.id_toko
        WHERE f.id_faktur = ?
        GROUP BY fd.id_product";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $idFaktur);
$stmt->execute();
$result = $stmt->get_result();
$faktur = $result->fetch_assoc();
$details = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Faktur</title>
    <?php include 'assets/header.php'; ?>
</head>

<body>
    <div class="container">
        <h2>Detail Faktur</h2>
        <?php if ($faktur) { ?>
            <p>ID Faktur: <?php echo $faktur['id_faktur']; ?></p>
            <p>Nama Toko: <?php echo $faktur['nama_toko']; ?></p>
            <p>Tanggal: <?php echo $faktur['tanggal']; ?></p>
            <p>Note: <?php echo $faktur['note']; ?></p>
            <p>Total Harga: <?php echo $faktur['total_harga']; ?></p>
            <p>Produk: <?php echo $faktur['product']; ?></p>
        <?php } else { ?>
            <p>Data faktur tidak ditemukan.</p>
        <?php } ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPriceModal">Edit Prices</button>
        <form method="GET" action="">
            <input type="hidden" name="id_faktur" value="<?php echo $idFaktur; ?>">
            <label for="use_luarkota">Use Harga Luar Kota:</label>
            <input type="checkbox" id="use_luarkota" name="use_luarkota" value="1" <?php if ($useLuarKotaPrice) echo 'checked'; ?>>
            <button type="submit">Update</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editPriceModal" tabindex="-1" aria-labelledby="editPriceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="spice/edit_prices.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPriceModalLabel">Edit Prices</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php foreach ($details as $detail) { ?>
                            <div class="mb-3">
                                <label for="price_<?php echo $detail['id_product']; ?>" class="form-label"><?php echo $detail['nama_product']; ?> (<?php echo $detail['quantity']; ?>x)</label>
                                <input type="number" class="form-control" id="price_<?php echo $detail['id_product']; ?>" name="prices[<?php echo $detail['id_product']; ?>]" value="<?php echo $detail['price']; ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'assets/footer.php'; ?>
</body>

</html>