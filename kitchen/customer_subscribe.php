<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php include 'assets/header.php'; ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>

<body class="g-sidenav-show ">
  <?php include 'widget/navbar.php'; ?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Customer</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Customer</h6>
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
            <li class="nav-item">
                <a data-bs-toggle="modal" data-bs-target="#modalCustomer" class="btn btn-primary"><i class="material-icons">add</i></a>
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
      <form action="spice/add_customer_with_form.php" method="post" enctype="multipart/form-data">
        <div class="input-group-outline d-flex text-center m-0 border border-primary rounded">
          <input type="file" class="form-control form-control-lg p-3 border-0" name="csv_file" aria-label="Upload">
          <button class="btn btn-primary d-flex justify-content-center m-0 text-center py-3 px-5" type="submit" name="submit" id="inputGroupFileAddon03">Kirim</button>
        </div>
      </form>
    </div>
    <div class="row" style="max-height: 100%;">
      <div class="col-md-12 col-xl-12">
        
        <div class="card p-3">

          <div class="table-responsive ">
            <table id="customerTable" class="table align-items-center mb-0">
              <thead class="border border">
                <tr>
                  <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Toko </th>
                  <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Sales </th>
                  <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Toko</th>
                  <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nomor Hp</th>
                  <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Pemilik</th>
                  <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">sytem_pembayaran</th>
                </tr>
              </thead>
              <tbody id="storeTable">
                <?php
                include('connection/db_connection.php');




                $query = "SELECT * FROM customer";
                $result = $conn->query($query);
                if ($result === false) {
                  die('Error: ' . $conn->error);
                }
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo ("
            <tr onclick=\"window.location.href='customer_detail.php?id={$row['id_toko']}'\" style='cursor:pointer;'>
            <td class='border'>
                <div class=''>
                <h6 class='text-sm font-weight-normal mb-0' >{$row['id_toko']}</h6>
                </div>
            </td>
            <td class='border'>
                <div class=''>
                <h6 class='text-sm font-weight-normal mb-0'>{$row['nama_sales']}</h6>
                </div>
            </td>
            <td class='border'>
                <div class=''>
                <h6 class='text-sm font-weight-normal mb-0'>{$row['nama_toko']}</h6>
                </div>
            </td>

    
            <td class='border'>
                <div class=''>
                <h6 class='text-sm font-weight-normal mb-0'>{$row['no_hp']}</h6>
                </div>
            </td>
            <td class='border'>
                <div class=''>
                <h6 class='text-sm font-weight-normal mb-0'>{$row['owner']}</h6>
                </div>
            </td>
            <td class='border'>
                <div class=''>
                <h6 class='text-sm font-weight-normal mb-0'>{$row['system_pembayaran']}</h6>
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
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  <script>
    $(document).ready(function() {
      $('#customerTable').DataTable();
    });
  </script>
</body>

</html>