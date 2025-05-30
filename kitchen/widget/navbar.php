<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="dashboard.php">
            <span class="ms-1 font-weight-bold text-white te-center">Sangap Osman Sentosa</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
        <li class="nav-item">
                <a class="nav-link text-white <?php if ($current_page == 'dashboard.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'dashboard.php') ? '#' : 'dashboard.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php if ($current_page == 'sales.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'sales.php') ? '#' : 'sales.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">real_estate_agent</i>
                    </div>
                    <span class="nav-link-text ms-1">Sales</span>
                </a>
            </li>
            <li class="nav-item ms-4">
                <a class="nav-link text-white <?php if ($current_page == 'detail_sales.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'detail_sales.php') ? '#' : 'detail_sales.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Detail Sales</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php if ($current_page == 'item.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'item.php') ? '#' : 'item.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php if ($current_page == 'customer_subscribe.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'customer_subscribe.php') ? '#' : 'customer_subscribe.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">store</i>
                    </div>
                    <span class="nav-link-text ms-1">Toko Langganan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php if ($current_page == 'faktur.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'faktur.php') ? '#' : 'faktur.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Pembuatan Faktur</span>
                </a>
            </li>
            <li class="nav-item ms-4">
                <a class="nav-link text-white <?php if ($current_page == 'faktur_history.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'faktur_history.php') ? '#' : 'faktur_history.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Faktur  History</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php if ($current_page == 'transaksi_tagihan.php') echo 'active bg-gradient-primary'; ?>" href="<?php echo ($current_page == 'transaksi_tagihan.php') ? '#' : 'transaksi_tagihan.php'; ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt</i>
                    </div>
                    <span class="nav-link-text ms-1">Transaksi Penjualan</span>
                </a>
            </li>
        </ul>
    </div>
</aside>