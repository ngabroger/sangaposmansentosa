<?php
include 'connection/db_connection.php'; // Koneksi ke database

$idFaktur = $_GET['id_faktur'];

// Query to get the details based on id_faktur
$sql = "SELECT * FROM faktur WHERE id_faktur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $idFaktur); // Ensure the types and order match the SQL query
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$idToko = $row['id_toko'];
$tanggal = $row['tanggal'];
$note = $row['note'];
$totalHarga = $row['total_harga'];
$discount = $row['discount']; // Add this line to get discount from the database

// Get faktur details/items
$sql_details = "SELECT fd.*, p.nama_product, p.price 
                FROM faktur_detail fd 
                JOIN product p ON fd.id_product = p.id_product 
                WHERE fd.id_faktur = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("s", $idFaktur);
$stmt_details->execute();
$result_details = $stmt_details->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Faktur</title>
    <?php include 'assets/header.php'; ?>
    <!-- Bootstrap CSS -->

</head>

<body>
    
    <div class="container justify-content-center  my-5  card">
        <div class="card-title">
        <h2 class="text-center">Detail Faktur</h2>
        </div>
        <div class="card-body">
            <p class="fs-2 fw-bold text-dark text-end"><?php echo $idFaktur; ?></p>
            <p class="fs-3 fw-bold ">ID Toko: <?php echo $idToko; ?></p>
            <p class="fs-4">Tanggal: <?php echo $tanggal; ?></p>
            <p class="fs-4">Note: <?php echo $note; ?></p>

            <!-- Show items in the faktur -->
            <div class="table-responsive mt-4">
                <h4>Items</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($detail = $result_details->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $detail['nama_product']; ?></td>
                            <td><?php echo $detail['quantity']; ?></td>
                            <td>Rp.<?php echo number_format($detail['harga'], 0, ',', '.'); ?></td>
                            <td>Rp.<?php echo number_format($detail['harga'] * $detail['quantity'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <p class="fs-2 fw-bold text-dark mt-4">Total Harga: Rp.<?php echo number_format($row['total_harga'], 0, ',', '.'); ?></p>
            <p class="fs-2 fw-bold text-dark">Discount: Rp.<?php echo number_format($discount, 0, ',', '.'); ?></p> <!-- Add this line to display discount -->
        </div>
        <div class="card-footer d-flex justify-content-start gap-2">
            <a href="spice/edit_faktur.php?id_faktur=<?php echo $idFaktur; ?>" class="btn btn-warning">Edit Items</a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit Details</button>
            <form action="spice/delete_faktur.php" method="GET" onsubmit="return confirm('Are you sure you want to delete this faktur?');">
                <input type="hidden" name="id_faktur" value="<?php echo $idFaktur; ?>">
                <button type="submit" class="btn btn-danger">delete</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="update_faktur.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Faktur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_faktur" value="<?php echo $idFaktur; ?>">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control border border-dark p-2" id="tanggal" name="tanggal" value="<?php echo $tanggal; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control border border-dark p-2" id="note" name="note" rows="3" required><?php echo $note; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount</label>
                            <input type="number" class="form-control border border-dark p-2" id="discount" name="discount" value="<?php echo $discount; ?>" required> <!-- Add this block for discount input -->
                        </div>
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