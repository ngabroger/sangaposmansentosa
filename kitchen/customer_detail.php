<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include 'assets/header.php'; ?>

</head>

<body class="container py-5 justify-content-center d-flex">
    <?php include 'widget/navbar.php'; ?>
    <div class="card ">
        <div class="card-header">

            <?php
            include('connection/db_connection.php');

            if (isset($_GET['id'])) {
                $id_toko = $_GET['id'];
                $query = "SELECT * FROM customer WHERE id_toko = '$id_toko'";
                $result = $conn->query($query);

                if ($result === false) {
                    die('Error: ' . $conn->error);
                }

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // Display customer details
                    echo "<h2 class='card-title'>" . $row['nama_toko'] . "</h2>";
                    echo "<div class='card-body'>";
                    echo "<p>Kode Toko: " . $row['id_toko'] . "</p>";
                    echo "<p>Alamat Toko: " . $row['alamat'] . "</p>";
                    echo "<p>Nomor Hp: " . $row['no_hp'] . "</p>";
                    echo "<p>Nama Pemilik: " . $row['owner'] . "</p>";
                    echo "<p>System Pembayaran: " . $row['system_pembayaran'] . "</p>";
                    echo "<p>Link Toko: " . $row['link_toko'] . "</p>";
                    echo "<p>Nama Sales: " . $row['nama_sales'] . "</p>";
                    echo "<p>Area Lokasi: " . $row['area_lokasi'] . "</p>";
                    echo "<p>Deskripsi: " . $row['description'] . "</p>";
                    echo "</div>";
                    echo "<div class='justify-content-evenly d-flex card-footer'>";
                    echo "<button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editCustomerModal'>Edit</button>";
                    echo "<button type='button' class='btn btn-danger' onclick='deleteCustomer(\"" . $row['id_toko'] . "\")'>Delete</button>";
                    echo "<button type='button' class='btn btn-success' onclick='sendWhatsapp()'>Whatsapp</button>";
                    echo "</div>";


                    // Edit Customer Modal
                    echo "
            <div class='modal fade' id='editCustomerModal' tabindex='-1' aria-labelledby='editCustomerModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header bg-warning justify-content-center d-flex'>
                        <h5 class='modal-title fs-3 text-white' id='editCustomerModalLabel'>Edit Customer</h5>
                    </div>
                    <div class='modal-body'>
                        <form action='spice/edit_customer.php' method='POST'>
                            <input type='hidden' name='id_toko' value='" . $row['id_toko'] . "'>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='nama_toko' class='form-control' value='" . $row['nama_toko'] . "' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='alamat' placeholder='alamat' class='form-control' value='" . $row['alamat'] . "' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='no_hp' class='form-control' placeholder='nomor hp' value='" . $row['no_hp'] . "' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='owner' placeholder='nama owner' class='form-control' value='" . $row['owner'] . "' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <select name='system_pembayaran' class='form-control' required>
                                    <option value='Cicil' " . ($row['system_pembayaran'] == 'Cicil' ? 'selected' : '') . ">Cicil</option>
                                    <option value='Cash' " . ($row['system_pembayaran'] == 'Cash' ? 'selected' : '') . ">Cash</option>
                                </select>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' placeholder='link toko' name='link_toko' class='form-control' value='" . $row['link_toko'] . "' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='nama_sales' placeholder='nama sales' class='form-control' value='" . $row['nama_sales'] . "' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <select name='area_lokasi' class='form-control' required>
                                    <option value='Luar Kota' " . ($row['area_lokasi'] == 'Luar Kota' ? 'selected' : '') . ">Luar Kota</option>
                                    <option value='Dalam Kota' " . ($row['area_lokasi'] == 'Dalam Kota' ? 'selected' : '') . ">Dalam Kota</option>
                                </select>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <textarea type='text' name='description' placeholder='deskripsi' class='form-control' required>" . $row['description'] . "</textarea>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <button type='submit' class='btn btn-warning'>Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>";
                } else {
                    echo "<p>Customer not found.</p>";
                }
            } else {
                echo "<p>No customer ID provided.</p>";
            }

            $conn->close();
            ?>

        </div>

        <script>
            function deleteCustomer(id) {
                if (confirm('Are you sure you want to delete this customer?')) {
                    window.location.href = 'spice/delete_customer.php?id=' + id;
                }
            }

            function sendWhatsapp() {
                var phoneNumber = "+6281265137720";
                var message = "Nama Toko: <?php echo $row['nama_toko']; ?>\n" +
                    "Nama: <?php echo $row['owner']; ?>\n" +
                    "Alamat: <?php echo $row['alamat']; ?>\n" +
                    "System Pembayaran: <?php echo $row['system_pembayaran']; ?>\n" +
                    "Link Toko: <?php echo $row['link_toko']; ?>";
                var url = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);
                window.open(url, '_blank');
            }
        </script>
        <?php include 'widget/modal.php'; ?>
        <?php include 'assets/footer.php'; ?>
</body>

</html>