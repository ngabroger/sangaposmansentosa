<?php
include('connection/db_connection.php');
?>
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
                        <a data-bs-toggle="modal" data-bs-target="#modalSales" class="btn btn-primary"><i class="material-icons">add</i></a>
                    </div>

                </div>
                <div class="card p-3">

                    <div class="table-responsive ">
                        <table class="table align-items-center mb-0">
                            <thead class="border border">
                                <tr>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Id Faktur</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Faktur</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Sales</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Toko</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nominal Faktur</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nominal Bayar</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sisa Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT id_faktur, tanggal_faktur, nama_sales, nama_toko, nominal_faktur, nominal_bayar, sisa_tagihan FROM sales";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id_faktur'] . "</td>";
                                        echo "<td>" . $row['tanggal_faktur'] . "</td>";
                                        echo "<td>" . $row['nama_sales'] . "</td>";
                                        echo "<td>" . $row['nama_toko'] . "</td>";
                                        echo "<td>Rp. " . number_format($row['nominal_faktur'], 0, ',', '.') . "</td>";
                                        echo "<td>Rp. " . number_format($row['nominal_bayar'], 0, ',', '.') . "</td>";
                                        echo "<td>Rp. " . number_format($row['sisa_tagihan'], 0, ',', '.') . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No sales data found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
    </main>

    <div class="modal fade" id="modalSales" tabindex="-1" role="dialog" aria-labelledby="modalSalesLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ">
                <div class="modal-header text-center justify-content-center d-flex align-items-center bg-primary">
                    <div class="modal-title">
                        <h5 class=" text-white" id="modalSalesLabel">Add Sales Data</h5>
                    </div>

                </div>
                <div class="modal-body">
                    <form id="salesForm" method="POST" action="spice/add_sales.php">
                        <div class="form-group">
                            <label for="id_faktur">Invoice ID</label>
                            <select class="form-control border border-dark p-2" id="id_faktur" name="id_faktur" required>
                                <option value="">Select Invoice</option>
                                <?php
                                // Fetch invoice IDs from the database
                                $sql = "SELECT id_faktur FROM faktur";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id_faktur'] . "'>" . $row['id_faktur'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_faktur">Invoice Date</label>
                            <input type="date" class="form-control border border-dark p-2" id="tanggal_faktur" name="tanggal_faktur" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_sales">Sales Name</label>
                            <input type="text" class="form-control border border-dark p-2" id="nama_sales" name="nama_sales" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_toko">Store Name</label>
                            <input type="text" class="form-control border border-dark p-2" id="nama_toko" name="nama_toko" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_penagihan">Billing Date</label>
                            <input type="date" class="form-control border border-dark p-2" id="tanggal_penagihan" name="tanggal_penagihan" required>
                        </div>
                        <div class="form-group">
                            <label for="nominal_faktur">Invoice Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-2">Rp.</span>
                                </div>
                                <input type="text" class="form-control border border-dark p-2" id="nominal_faktur" name="nominal_faktur" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nominal_bayar">Payment Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-2">Rp.</span>
                                </div>
                                <input type="text" class="form-control border border-dark p-2" id="nominal_bayar" name="nominal_bayar" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sisa_tagihan">Remaining Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-2">Rp.</span>
                                </div>
                                <input type="text" class="form-control border border-dark p-2" id="sisa_tagihan" name="sisa_tagihan" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Description</label>
                            <textarea class="form-control border border-dark p-2" id="keterangan" name="keterangan"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function parseRupiah(rupiah) {
            return parseFloat(rupiah.replace(/[^,\d]/g, '').replace(',', '.'));
        }

        document.getElementById('id_faktur').addEventListener('change', function() {
            var idFaktur = this.value;
            if (idFaktur) {
                fetch('get_faktur_details.php?id_faktur=' + idFaktur)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('tanggal_faktur').value = data.tanggal_faktur;
                        document.getElementById('nama_sales').value = data.nama_sales; // Populate nama_sales
                        document.getElementById('nama_toko').value = data.nama_toko;
                        document.getElementById('nominal_faktur').value = formatRupiah(data.nominal_faktur.toString(), 'Rp. ');
                        document.getElementById('sisa_tagihan').value = formatRupiah(data.sisa_tagihan.toString(), 'Rp. ');
                    });
            }
        });

        document.getElementById('nominal_bayar').addEventListener('input', function() {
            var nominalFaktur = parseRupiah(document.getElementById('nominal_faktur').value) || 0;
            var nominalBayar = parseRupiah(this.value) || 0;
            var sisaTagihan = nominalFaktur - nominalBayar;

            if (sisaTagihan < 0) {
                alert('Remaining amount cannot be negative.');
                document.getElementById('sisa_tagihan').value = '';
                this.value = '';
            } else {
                document.getElementById('sisa_tagihan').value = formatRupiah(sisaTagihan.toString(), 'Rp. ');
                this.value = formatRupiah(this.value, 'Rp. ');
            }
        });

        document.getElementById('salesForm').addEventListener('submit', function(event) {
            var sisaTagihan = parseRupiah(document.getElementById('sisa_tagihan').value) || 0;
            if (sisaTagihan < 0) {
                alert('Remaining amount cannot be negative.');
                event.preventDefault();
            }
        });
    </script>

    <?php include 'assets/footer.php' ?>

</body>

</html>