<?php
// Database connection
include('connection/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to format numbers as Indonesian Rupiah
function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

// Fetch grouped sales data from the database
$sql = "SELECT gs.id, gs.id_faktur, gs.total_nominal_bayar, f.total_harga, c.nama_toko
        FROM grouped_sales gs
        JOIN faktur f ON gs.id_faktur = f.id_faktur
        JOIN customer c ON f.id_toko = c.id_toko";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <title>Sales</title>
   <?php 
   include 'assets/header.php';
   ?>
</head>
<body class="g-sidenav-show">
    <?php include 'widget/navbar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Sales</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0"> Detail Sales</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                       
                        <li class="nav-item px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0">
                                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">
            <div class="row" style="max-height: 100%;">
                <div class="col-md-12 col-xl-12">
                    <div class="card p-3">
                        <div class="table-responsive">
                            <table id="salesTable" class="table align-items-center mb-0">
                                <thead class="border border">
                                    <tr>
                                        <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Sales Number</th>
                                        <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice Number</th>
                                        <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Store Name</th>
                                        <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Nominal Bayar</th>
                                        <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Faktur</th>
                                        <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sisa Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>" . $row['id'] . "</h6></div></td>";
                                        echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>" . $row['id_faktur'] . "</h6></div></td>";
                                        echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>" . $row['nama_toko'] . "</h6></div></td>";
                                        echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>" . formatRupiah($row['total_nominal_bayar']) . "</h6></div></td>";
                                        echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>" . formatRupiah($row['total_harga']) . "</h6></div></td>";
                                        echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>" . formatRupiah($row['total_harga'] - $row['total_nominal_bayar']) . "</h6></div></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'widget/modal.php'; ?>
    <?php include 'assets/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#salesTable').DataTable();
        });
    </script>
</body>
</html>