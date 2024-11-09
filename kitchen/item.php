<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barang</title>

  <?php include 'assets/header.php'; ?>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <?php include 'widget/navbar.php'; ?>



  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Barang</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Barang</h6>
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
    <!-- End Navbar -->
    <div class="container-fluid py-4">

      <div class="row" style="max-height: 100%;">
        <div class="col-md-12 col-xl-12">
          <div class="  d-flex justify-content-end ">
            <div class="position-absolute ms-5" style="z-index: 100;">
              <a data-bs-toggle="modal" data-bs-target="#modalItem" class="btn btn-primary"><i class="material-icons">add</i></a>
            </div>

          </div>
          <div class="card p-3" >
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Product </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Product</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type Product</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga Kemasan</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Isi Kemasan</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Harga</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include('connection/db_connection.php');




                  $query = "SELECT * FROM product";
                  $result = $conn->query($query);
                  if ($result === false) {
                    die('Error: ' . $conn->error);
                  }
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo ("
          <tr>
            <td>
              <div class=''>
                <h6 class='text-sm font-weight-normal mb-0' >{$row['id_product']}</h6>
              </div>
            </td>
            <td>
              <div class=''>
                <h6 class='text-sm font-weight-normal mb-0'>{$row['nama_product']}</h6>
              </div>
            </td>
            <td>
              <div class=''>
              <h6 class='text-sm font-weight-normal mb-0'>{$row['type_product']}</h6>
              </div>
            </td>
            <td>
              <div class=''>
              <h6 class='text-sm font-weight-normal mb-0'>{$row['price']}</h6>
              </div>
            </td>
            <td>
              <div class=''>
              <h6 class='text-sm font-weight-normal mb-0'>{$row['amount']}</h6>
              </div>
            </td>
            <td>
              <div class=''>
              <h6 class='text-sm font-weight-normal mb-0'>{$row['total']}</h6>
              </div>
            </td>
           <td>
              <div class=''>
                <a data-bs-toggle='modal' data-bs-target='#infoModal{$row['id_product']}' class='btn btn-warning'><i class='material-icons'>edit</i></a>
              </div>
            </td>
          </tr>
          ");
                    }
                  } else {
                    // Menampilkan pesan jika data tidak ditemukan
                    echo "<tr><td class='text-center' colspan='7'>Data not found.</td></tr>";
                  }


                  $conn->close();
                  ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
  </main>


  <?php include 'widget/modal.php'; ?>
  <?php include 'assets/footer.php'; ?>
</body>

</html>