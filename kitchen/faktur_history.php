<?php
session_start();

include('connection/db_connection.php');

// Query untuk mendapatkan data layanan
$sql = "SELECT id_product, nama_product, price FROM product";
$result = $conn->query($sql);

$sqlCustomer = "SELECT id_toko, nama_toko FROM customer";
$resultCustomer = $conn->query($sqlCustomer); // Execute the query

$sql = "SELECT id_product, nama_product, price FROM product";
$result = $conn->query($sql);

$options = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options[] = [
            'id' => $row["id_product"],
            'name' => $row["nama_product"],
            'price' => $row["price"]
        ];
    }
}
$options_json = json_encode($options);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../resources/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../resources/img/favicon.png">
    <title>
        CREATE FAKTUR BARANG
    </title>
    <?php include 'assets/header.php'; ?>
    <style>
        /* Untuk Chrome, Safari, Edge, Opera */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Untuk Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table thead {
                display: none;
            }

            .table tr {
                display: block;
                margin-bottom: 1rem;
            }

            .table td {
                display: block;
                text-align: right;
                font-size: 0.8rem;
                border-bottom: 1px solid #dee2e6;
                white-space: normal; /* Ensure text wraps */
                word-wrap: break-word; /* Break long words */
            }

            .table td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            .table td:last-child {
                border-bottom: 0;
            }
        }

        .note-column {
            width: 150px; /* Set a fixed width for the Note column */
        }
    </style>
</head>

<body class="">


    <!-- End Navbar -->
    <p class="my-2 text-bold fs-3 justify-content-center text-center ">Pembuatan Faktur</p>
    <a id="buatMarketingBtn" class='btn btn-danger d-flex text-center justify-content-center m-5'>Buat Marketing </a>
    </div>
    </div>
    <div class="table-responsive p-5 border border-rounded m-5">
        <table class="table align-items-center mb-0">
            <thead class="">
                <tr>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Select</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice </th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Toko</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Barang dan jumlah </th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 note-column">Note</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Harga</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                </tr>
            </thead>
            <tbody id="tableData">
                <?php
                $sql = "SELECT f.id_faktur, c.nama_toko, c.alamat, c.no_hp,c.id_toko, c.owner , f.tanggal, f.note, f.total_harga,c.nama_sales,
                    GROUP_CONCAT(p.nama_product, ' (', fd.quantity, 'x)') AS product, 
                    GROUP_CONCAT(p.nama_product) AS product_names, 
                    GROUP_CONCAT(p.type_product) AS product_kemasan, 
                    GROUP_CONCAT(p.price) AS product_price, 
                    GROUP_CONCAT(fd.harga) AS total_item_price, 
                    GROUP_CONCAT(fd.quantity) AS quantities 
                    FROM faktur f 
                    JOIN faktur_detail fd ON f.id_faktur = fd.id_faktur 
                    JOIN product p ON fd.id_product = p.id_product 
                    JOIN customer c ON f.id_toko = c.id_toko 
                    GROUP BY f.id_faktur";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $idFaktur = $row['id_faktur'];
                    $namaToko = $row['nama_toko'];
                    $alamat = $row['alamat'];
                    $no_hp = $row['no_hp'];
                    $id_toko = $row['id_toko'];
                    $owner = $row['owner'];
                    $tanggal = $row['tanggal'];
                    $product = $row['product'];
                    $productNames = $row['product_names'];
                    $productKemasan = $row['product_kemasan'];
                    $productPrice = $row['product_price'];
                    $quantities = $row['quantities'];
                    $note = $row['note'];

                    $totalHarga = $row['total_harga'];
                    $nama_sales = $row['nama_sales'];

                    // Pisahkan produk dengan koma, dan buat span untuk setiap produk
                    $productList = explode(',', $product); // Memisahkan produk berdasarkan koma
                    $productSpan = '';
                    foreach ($productList as $prod) {
                        $productSpan .= "<span class='badge text-bg-primary'>$prod</span> ";
                    }

                    echo "<tr>";
                    echo "<td data-label='Select'><input type='checkbox' class='itemCheckbox' value='$idFaktur'></td>";
                    echo "<td data-label='Invoice'>$idFaktur</td>";
                    echo "<td data-label='Nama Toko'>$namaToko</td>";
                    echo "<td data-label='Tanggal'>$tanggal</td>";
                    echo "<td data-label='Barang dan jumlah'>$productSpan</td>";  // Menampilkan produk sebagai badge
                    echo "<td data-label='Note' class='note-column'>$note</td>";
                    echo "<td data-label='Total Harga'>$totalHarga</td>";
                    echo "<td data-label='Action'><form method='POST' action='faktur_create.php'>";
                    echo "<input type='hidden' name='id_faktur' value='$idFaktur'>";
                    echo "<button type='submit' class='btn btn-danger me-3'>Create</button>";
                    echo "<a href='detail_faktur.php?id_faktur=$idFaktur' class='btn btn-warning'>Detail</a>";
                    echo "</form></td>";
                    echo "</tr>";
                }
                ?>

            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('buatMarketingBtn').addEventListener('click', function() {
            const selectedItems = [];
            document.querySelectorAll('.itemCheckbox:checked').forEach(checkbox => {
                selectedItems.push(checkbox.value);
            });
            if (selectedItems.length > 0) {
                window.location.href = 'tabel_marketing.php?selectedItems=' + selectedItems.join(',');
            } else {
                alert('Please select at least one item.');
            }
        });
    </script>

    <?php include 'assets/footer.php'; ?>

</body>

</html>