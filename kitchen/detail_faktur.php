<?php
include 'connection/db_connection.php'; // Koneksi ke database

$idFaktur = $_GET['id_faktur'];

// Query to get the details based on id_faktur
$sql = "SELECT * FROM faktur WHERE id_faktur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $idFaktur);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$idToko = $row['id_toko'];
$tanggal = $row['tanggal'];
$note = $row['note'];
$totalHarga = $row['total_harga'];

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
            <p class="fs-4 pb-5">Note: <?php echo $note; ?></p>
            <p class="fs-2 fw-bold text-dark">Total Harga: Rp.<?php echo number_format($row['total_harga'], 0, ',', '.'); ?></p>
        </div>
        <div class="card-footer just">
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal">edit</button>
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