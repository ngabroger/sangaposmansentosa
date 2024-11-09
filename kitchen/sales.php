<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Market</title>
    <?php include 'assets/header.php'; ?>

</head>

<body>
    <?php include 'widget/navbar.php' ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Sales</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Sales</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                    </div>
                    <ul class="navbar-nav  justify-content-end">
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

        </div>

        <div class="row" style="max-height: 100%;">
            <div class="col-md-12 col-xl-12">
                <div class="  d-flex justify-content-end ">
                    <div class="position-absolute ms-5" style="z-index: 100;">
                        <a data-bs-toggle="modal" data-bs-target="#modalCustomer" class="btn btn-primary"><i class="material-icons">add</i></a>
                    </div>

                </div>
                <div class="card p-3">

                    <div class="table-responsive ">
                        <table class="table align-items-center mb-0">
                            <thead class="border border">
                                <tr>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Toko </th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama CS </th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Toko</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor Hp</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Pemilik</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">sytem_pembayaran</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>

            </div>
    </main>

    <?php include 'assets/footer.php' ?>

</body>

</html>