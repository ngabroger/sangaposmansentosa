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
</head>
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
</style>

<body class="">


    <!-- End Navbar -->
    <p class="my-2 text-bold fs-3 justify-content-center text-center ">Pembuatan Faktur</p>
    </div>
    </div>
    <div class="table-responsive p-5 border border-rounded m-5">
        <table class="table align-items-center mb-0">
            <thead class="">
                <tr>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice </th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Toko</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Barang dan jumlah </th>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Note</th>
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
                    echo "<td><a href='detail_faktur.php?id_faktur=$idFaktur'>$idFaktur</a></td>";
                    echo "<td><a href='detail_faktur.php?id_faktur=$idFaktur'>$namaToko</a></td>";
                    echo "<td><a href='detail_faktur.php?id_faktur=$idFaktur'>$tanggal</a></td>";
                    echo "<td><a href='detail_faktur.php?id_faktur=$idFaktur'>$productSpan</a></td>";  // Menampilkan produk sebagai badge
                    echo "<td><a href='detail_faktur.php?id_faktur=$idFaktur'>$note</a></td>";
                    echo "<td><a href='detail_faktur.php?id_faktur=$idFaktur'>$totalHarga</a></td>";
                    echo "<td><form method='POST' action='faktur_create.php'>"; // Ganti 'target_page.php' dengan halaman tujuan Anda
                    echo "<input type='hidden' name='alamat' value='$alamat'>";
                    echo "<input type='hidden' name='id_toko' value='$id_toko'>";
                    echo "<input type='hidden' name='no_hp' value='$no_hp'>";
                    echo "<input type='hidden' name='owner' value='$owner'>";
                    echo "<input type='hidden' name='product_names' value='$productNames'>";
                    echo "<input type='hidden' name='product_kemasan' value='$productKemasan'>";
                    echo "<input type='hidden' name='product_price' value='$productPrice'>";
                    echo "<input type='hidden' name='quantities' value='$quantities'>";
                    echo "<input type='hidden' name='id_faktur' value='$idFaktur'>";
                    echo "<input type='hidden' name='nama_toko' value='$namaToko'>";
                    echo "<input type='hidden' name='tanggal' value='$tanggal'>";
                    echo "<input type='hidden' name='product' value='$product'>";
                    echo "<input type='hidden' name='note' value='$note'>";
                    echo "<input type='hidden' name='total_harga' value='$totalHarga'>";
                    echo "<input type='hidden' name='nama_sales' value='$nama_sales'>";
                    echo "<button type='submit' class='btn btn-danger'>Create</button>";
                    echo "</form></td>";
                    echo "</tr>";
                }
                ?>

            </tbody>
        </table>
    </div>

    </div>
    </div>





    <?php include 'assets/footer.php'; ?>

</body>

</html>