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
    <p class="my-2 text-bold fs-3 ">Pembuatan Faktur</p>
            </div>
        </div>
        <div class="table-responsive ">
        <table class="table align-items-center mb-0">
                <thead class="border border">
        <tr>
                <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice </th>
                <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Toko</th>
                <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Barang dan jumlah </th>
                <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Note</th>
                <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Harga</th>
                <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
        </tr>
            </thead>
            <tbody id="tableData">
            <?php
            $sql = "SELECT f.id_faktur, c.nama_toko, f.tanggal, f.note, f.total_harga, 
                    GROUP_CONCAT(p.nama_product, ' (', fd.quantity, 'x)') AS product 
                    FROM faktur f 
                    JOIN faktur_detail fd ON f.id_faktur = fd.id_faktur 
                    JOIN product p ON fd.id_product = p.id_product 
                    JOIN customer c ON f.id_toko = c.id_toko 
                    GROUP BY f.id_faktur";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $idFaktur = $row['id_faktur'];
                $namaToko = $row['nama_toko'];
                $tanggal = $row['tanggal'];
                $product = $row['product'];
                $note = $row['note'];
                $totalHarga = $row['total_harga'];

                // Pisahkan produk dengan koma, dan buat span untuk setiap produk
                $productList = explode(',', $product); // Memisahkan produk berdasarkan koma
                $productSpan = '';
                foreach ($productList as $prod) {
                    $productSpan .= "<span class='badge text-bg-primary'>$prod</span> ";
                }

                echo "<tr>";
                echo "<td>$idFaktur</td>";
                echo "<td>$namaToko</td>";
                echo "<td>$tanggal</td>";
                echo "<td>$productSpan</td>";  // Menampilkan produk sebagai badge
                echo "<td>$note</td>";
                echo "<td>$totalHarga</td>";
                echo "<td><button class='btn btn-danger'>Hapus</button></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
            </div>
       
      </div>
    </div>


  
 

  <?php include 'assets/footer.php';?>
  
</body>

</html>
