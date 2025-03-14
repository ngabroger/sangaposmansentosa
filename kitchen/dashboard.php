<?php
include('connection/db_connection.php');

// Calculate total revenue
$sql = "SELECT SUM(nominal_bayar) AS total_revenue FROM sales";
$result = $conn->query($sql);
$total_revenue = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_revenue = $row['total_revenue'];
}

// Calculate total sisa tagihan for the latest update of each factur_id
$sql = "SELECT SUM(f.total_harga - gs.total_nominal_bayar) AS total_sisa_tagihan 
        FROM grouped_sales gs
        JOIN faktur f ON gs.id_faktur = f.id_faktur";
$result = $conn->query($sql);
$total_sisa_tagihan = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_sisa_tagihan = $row['total_sisa_tagihan'];
}

// Calculate sum of total revenue and total sisa tagihan
$total_sum = $total_revenue + $total_sisa_tagihan;

// Fetch recent transactions
$sql = "SELECT * FROM sales ORDER BY update_time DESC LIMIT 5";
$recent_transactions = $conn->query($sql);

// Fetch revenue data for the chart grouped by month using tanggal_penagihan
$sql = "SELECT DATE_FORMAT(tanggal_penagihan, '%Y-%m') as month, SUM(nominal_bayar) as revenue 
        FROM sales 
        GROUP BY DATE_FORMAT(tanggal_penagihan, '%Y-%m') 
        ORDER BY DATE_FORMAT(tanggal_penagihan, '%Y-%m')";
$revenue_data = $conn->query($sql);

$months = [];
$revenues = [];

if ($revenue_data->num_rows > 0) {
    while ($row = $revenue_data->fetch_assoc()) {
        $months[] = $row['month'];
        $revenues[] = $row['revenue'];
    }
}

// Fetch most popular items
$sql = "SELECT p.nama_product, COUNT(fd.id_product) AS purchase_count
        FROM faktur_detail fd
        JOIN product p ON fd.id_product = p.id_product
        GROUP BY fd.id_product
        ORDER BY purchase_count DESC
        LIMIT 5";
$popular_items = $conn->query($sql);

// Fetch revenue data grouped by area_lokasi
$sql = "SELECT c.area_lokasi, SUM(s.nominal_faktur - s.nominal_bayar) as total_sisa_tagihan 
        FROM sales s
        JOIN customer c ON s.nama_toko = c.nama_toko 
        GROUP BY c.area_lokasi";
$area_revenue_data = $conn->query($sql);

$areas = [];
$area_revenues = [];

if ($area_revenue_data->num_rows > 0) {
    while ($row = $area_revenue_data->fetch_assoc()) {
        $areas[] = $row['area_lokasi'];
        $area_revenues[] = $row['total_sisa_tagihan'];
    }
}

// Fetch total sisa tagihan grouped by nama_sales
$sql = "SELECT s.nama_sales, SUM(s.nominal_faktur - s.nominal_bayar) as total_sisa_tagihan 
        FROM sales s
        GROUP BY s.nama_sales";
$sales_revenue_data = $conn->query($sql);

$sales_names = [];
$sales_revenues = [];

if ($sales_revenue_data->num_rows > 0) {
    while ($row = $sales_revenue_data->fetch_assoc()) {
        $sales_names[] = $row['nama_sales'];
        $sales_revenues[] = $row['total_sisa_tagihan'];
    }
}

// Fetch combined revenue per sales
$sql = "SELECT s.nama_sales, 
               SUM(CASE WHEN DATE_FORMAT(s.tanggal_penagihan, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') THEN s.nominal_bayar ELSE 0 END) as monthly_revenue,
               SUM(CASE WHEN DATE_FORMAT(s.tanggal_penagihan, '%Y-%u') = DATE_FORMAT(CURDATE(), '%Y-%u') THEN s.nominal_bayar ELSE 0 END) as weekly_revenue,
               SUM(CASE WHEN DATE_FORMAT(s.tanggal_penagihan, '%Y') = DATE_FORMAT(CURDATE(), '%Y') THEN s.nominal_bayar ELSE 0 END) as yearly_revenue
        FROM sales s
        GROUP BY s.nama_sales
        ORDER BY s.nama_sales";
$combined_revenue_data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php include 'assets/header.php'; ?>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="g-sidenav-show bg-gray-200">
    <?php include 'widget/navbar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Dashboard</h6>
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

    
    <div class="container-fluid mt-5 py-2 ">
        <div class="row">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Revenue</p>
                                    <h5 class="font-weight-bolder">
                                        Rp. <?php echo number_format($total_revenue, 0, ',', '.'); ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="material-icons text-lg opacity-10 align-items-center text-center" aria-hidden="true">payments</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
          
          
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Sisa Tagihan</p>
                                    <h5 class="font-weight-bolder">
                                        Rp. <?php echo number_format($total_sisa_tagihan, 0, ',', '.'); ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="material-icons text-lg opacity-10 align-items-center text-center" aria-hidden="true">payments</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Sum</p>
                                    <h5 class="font-weight-bolder">
                                        Rp. <?php echo number_format($total_sum, 0, ',', '.'); ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="material-icons text-lg opacity-10 align-items-center text-center" aria-hidden="true">payments</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-8 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Revenue Trends</h6>
                    </div>
                    <div class="card-body p-3">
                        <canvas id="revenueChart"></canvas>
                    </div>
                    
                </div>
                <div class="card mt-5">
                    <div class="card-header p-3">
                        <h6>Data Per Sales</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="m-auto d-flex gap-3 justify-content-center">
                            <button class="btn btn-primary" onclick="showTable('combined')">Combined</button>
                        </div>
                        <div id="combinedTable" class="mt-4">
                            <h6>Combined Revenue per Sales</h6>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sales Name</th>
                                        <th>Monthly Revenue</th>
                                        <th>Weekly Revenue</th>
                                        <th>Yearly Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($combined_revenue_data->num_rows > 0) {
                                        while ($row = $combined_revenue_data->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$row['nama_sales']}</td>";
                                            echo "<td>Rp. " . number_format($row['monthly_revenue'], 0, ',', '.') . "</td>";
                                            echo "<td>Rp. " . number_format($row['weekly_revenue'], 0, ',', '.') . "</td>";
                                            echo "<td>Rp. " . number_format($row['yearly_revenue'], 0, ',', '.') . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No data available</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ">
                <div class="card mb-3">
                    <div class="card-header pb-0">
                        <h6>Recent Transactions</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <?php
                            if ($recent_transactions->num_rows > 0) {
                                while ($row = $recent_transactions->fetch_assoc()) {
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                            {$row['id_faktur']}
                                            <span class='badge bg-primary rounded-pill'>Rp. " . number_format($row['nominal_bayar'], 0, ',', '.') . "</span>
                                          </li>";
                                }
                            } else {
                                echo "<li class='list-group-item'>No recent transactions</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Most Popular Items</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <?php
                            if ($popular_items->num_rows > 0) {
                                while ($row = $popular_items->fetch_assoc()) {
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                            {$row['nama_product']}
                                            <span class='badge bg-primary rounded-pill'>{$row['purchase_count']} times</span>
                                          </li>";
                                }
                            } else {
                                echo "<li class='list-group-item'>No popular items</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header pb-0">
                        <h6>TOTAL SISA TAGIHAN  by Area</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <?php
                            if (!empty($areas)) {
                                foreach ($areas as $index => $area) {
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                            {$area}
                                            <span class='badge bg-primary rounded-pill'>Rp. " . number_format($area_revenues[$index], 0, ',', '.') . "</span>
                                          </li>";
                                }
                            } else {
                                echo "<li class='list-group-item'>No revenue data by area</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header pb-0">
                        <h6>Total Sisa Tagihan by Sales</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <?php
                            if (!empty($sales_names)) {
                                foreach ($sales_names as $index => $sales_name) {
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                            {$sales_name}
                                            <span class='badge bg-primary rounded-pill'>Rp. " . number_format($sales_revenues[$index], 0, ',', '.') . "</span>
                                          </li>";
                                }
                            } else {
                                echo "<li class='list-group-item'>No revenue data by sales</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
    </main>
    <?php include 'assets/footer.php'; ?>
    <script>
        function showTable(tableId) {
            document.getElementById('combinedTable').style.display = 'none';
            document.getElementById(tableId + 'Table').style.display = 'block';
        }

        // Revenue chart data
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode($revenues); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>