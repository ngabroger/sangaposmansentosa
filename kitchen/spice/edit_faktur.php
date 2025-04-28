<?php
include('../connection/db_connection.php');

// Function to get all products
function getProducts($conn)
{
    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

// Function to get faktur details
function getFakturDetails($conn, $id_faktur)
{
    $sql = "SELECT fd.*, p.nama_product, p.price 
            FROM faktur_detail fd 
            JOIN product p ON fd.id_product = p.id_product 
            WHERE fd.id_faktur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_faktur);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
    }
    return $details;
}

// Function to get faktur
function getFaktur($conn, $id_faktur)
{
    $sql = "SELECT * FROM faktur WHERE id_faktur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_faktur);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_faktur = $_POST['id_faktur'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Handle item updates
        if (isset($_POST['item'])) {
            // Delete existing items
            $delete_sql = "DELETE FROM faktur_detail WHERE id_faktur = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("s", $id_faktur);
            $delete_stmt->execute();

            $total_harga = 0;

            // Insert new/updated items
            foreach ($_POST['item'] as $item) {
                $id_product = $item['id_product'];
                $quantity = $item['quantity'];
                $harga = $item['harga'];

                if ($quantity > 0) {
                    $insert_sql = "INSERT INTO faktur_detail (id_faktur, id_product, quantity, harga) VALUES (?, ?, ?, ?)";
                    $insert_stmt = $conn->prepare($insert_sql);
                    $insert_stmt->bind_param("ssid", $id_faktur, $id_product, $quantity, $harga);
                    $insert_stmt->execute();

                    $total_harga += ($harga * $quantity);
                }
            }

            // Update the total price in the faktur table
            $update_sql = "UPDATE faktur SET total_harga = ? WHERE id_faktur = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ds", $total_harga, $id_faktur);
            $update_stmt->execute();

            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Faktur updated successfully', 'redirect' => "../detail_faktur.php?id_faktur=$id_faktur"]);
        } else {
            // Just update the total price if no items were changed
            $total_harga = $_POST['total_harga'];
            $sql = "UPDATE faktur SET total_harga = ? WHERE id_faktur = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ds', $total_harga, $id_faktur);

            if ($stmt->execute()) {
                $conn->commit();
                echo "Price updated successfully.";
            } else {
                throw new Exception("Error updating price: " . $conn->error);
            }
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    $conn->close();
} else {
    // Display the edit form
    $id_faktur = $_GET['id_faktur'];
    $faktur = getFaktur($conn, $id_faktur);
    $details = getFakturDetails($conn, $id_faktur);
    $products = getProducts($conn);

    // Return HTML for the edit form
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Faktur</title>
        <?php include '../assets/header.php'; ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

        <style>
            .product-row {
                margin-bottom: 10px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            .remove-item {
                color: red;
                cursor: pointer;
            }
        </style>
    </head>

    <body class="bg-light">
        <div class="container mt-5">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Edit Faktur <?php echo $id_faktur; ?></h3>
                </div>
                <div class="card-body">
                    <form id="editFakturForm">
                        <input type="hidden" name="id_faktur" value="<?php echo $id_faktur; ?>">

                        <h4 class="mb-3">Current Items</h4>
                        <div id="itemsContainer">
                            <?php foreach ($details as $detail): ?>
                                <div class="product-row" data-id="<?php echo $detail['id_product']; ?>">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <select class="form-control product-select" name="item[<?php echo $detail['id_product']; ?>][id_product]">
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?php echo $product['id_product']; ?>"
                                                        data-price="<?php echo $product['price']; ?>"
                                                        <?php echo ($product['id_product'] == $detail['id_product']) ? 'selected' : ''; ?>>
                                                        <?php echo $product['nama_product']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control quantity" name="item[<?php echo $detail['id_product']; ?>][quantity]" value="<?php echo $detail['quantity']; ?>" min="0">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control price" name="item[<?php echo $detail['id_product']; ?>][harga]" value="<?php echo $detail['harga']; ?>" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="subtotal"><?php echo $detail['harga'] * $detail['quantity']; ?></span>
                                        </div>
                                        <div class="col-md-1">
                                            <i class="material-icons remove-item">delete</i>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="button" class="btn btn-success mt-3" id="addItemBtn">
                            Add Item
                        </button>

                        <div class="row mt-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="d-flex justify-content-between">
                                    <h4>Total:</h4>
                                    <h4 id="totalPrice"><?php echo $faktur['total_harga']; ?></h4>
                                    <input type="hidden" name="total_harga" id="totalHargaInput" value="<?php echo $faktur['total_harga']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-primary" id="saveChanges">Save Changes</button>
                            <a href="../detail_faktur.php?id_faktur=<?php echo $id_faktur; ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                    <!-- Template for new item row -->
                    <template id="newItemTemplate">
                        <div class="product-row" data-id="NEW_ID">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <select class="form-control product-select" name="item[NEW_ID][id_product]">
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?php echo $product['id_product']; ?>" data-price="<?php echo $product['price']; ?>">
                                                <?php echo $product['nama_product']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control quantity" name="item[NEW_ID][quantity]" value="1" min="0">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control price" name="item[NEW_ID][harga]" value="0" readonly>
                                </div>
                                <div class="col-md-2">
                                    <span class="subtotal">0</span>
                                </div>
                                <div class="col-md-1">
                                    <i class="btn btn-danger remove-item">delete</i>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Variables
                let counter = 1;
                const itemsContainer = document.getElementById('itemsContainer');
                const addItemBtn = document.getElementById('addItemBtn');
                const newItemTemplate = document.getElementById('newItemTemplate');
                const saveChangesBtn = document.getElementById('saveChanges');

                // Initialize prices
                document.querySelectorAll('.product-select').forEach(select => {
                    const price = select.options[select.selectedIndex].getAttribute('data-price');
                    const row = select.closest('.product-row');
                    const priceInput = row.querySelector('.price');
                    priceInput.value = price;
                    updateSubtotal(row);
                });

                // Calculate total
                calculateTotal();

                // Add new item
                addItemBtn.addEventListener('click', function() {
                    const newId = 'new_' + counter++;
                    const content = newItemTemplate.content.cloneNode(true);

                    // Update the id and name attributes
                    content.querySelector('.product-row').dataset.id = newId;
                    content.querySelectorAll('[name^="item[NEW_ID]"]').forEach(el => {
                        el.name = el.name.replace('NEW_ID', newId);
                    });

                    // Set initial price
                    const select = content.querySelector('.product-select');
                    const price = select.options[select.selectedIndex].getAttribute('data-price');
                    content.querySelector('.price').value = price;

                    itemsContainer.appendChild(content);

                    // Calculate subtotal for the new row
                    const newRow = itemsContainer.lastElementChild;
                    updateSubtotal(newRow);
                    calculateTotal();
                });

                // Event delegation for product selection change
                itemsContainer.addEventListener('change', function(e) {
                    if (e.target.classList.contains('product-select')) {
                        const price = e.target.options[e.target.selectedIndex].getAttribute('data-price');
                        const row = e.target.closest('.product-row');
                        const priceInput = row.querySelector('.price');
                        priceInput.value = price;
                        updateSubtotal(row);
                        calculateTotal();
                    }
                });

                // Event delegation for quantity change
                itemsContainer.addEventListener('input', function(e) {
                    if (e.target.classList.contains('quantity')) {
                        const row = e.target.closest('.product-row');
                        updateSubtotal(row);
                        calculateTotal();
                    }
                });

                // Event delegation for remove item
                itemsContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-item')) {
                        const row = e.target.closest('.product-row');
                        row.remove();
                        calculateTotal();
                    }
                });

                // Calculate subtotal for a row
                function updateSubtotal(row) {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.price').value) || 0;
                    const subtotal = quantity * price;
                    row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                    return subtotal;
                }

                // Calculate total price
                function calculateTotal() {
                    let total = 0;
                    document.querySelectorAll('.product-row').forEach(row => {
                        total += parseFloat(row.querySelector('.subtotal').textContent) || 0;
                    });
                    document.getElementById('totalPrice').textContent = total.toFixed(2);
                    document.getElementById('totalHargaInput').value = total;
                }

                // Save changes
                saveChangesBtn.addEventListener('click', function() {
                    const form = document.getElementById('editFakturForm');
                    const formData = new FormData(form);

                    fetch('edit_faktur.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                alert(data.message);
                                window.location.href = data.redirect;
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while saving changes');
                        });
                });
            });
        </script>

        <?php include '../assets/footer.php'; ?>
    </body>

    </html>
<?php
}
?>