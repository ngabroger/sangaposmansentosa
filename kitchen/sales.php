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
    <style>
        .selected-card {
            border: 2px solid blue;
        }
    </style>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        <li class="nav-item">
                        <a data-bs-toggle="modal" data-bs-target="#modalSales" class="btn btn-primary"><i class="material-icons">add</i></a>
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
            <div class="row mb-4">
                <div class="col-md-12">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by Invoice ID or Sales Name">
                </div>
            </div>
            <form id="cardForm" method="POST" action="tabel_marketing.php">
                <div class="row" id="cardContainer">
                    <?php
                    $sql = "SELECT id_faktur, tanggal_faktur, nama_sales, nama_toko, nominal_faktur, 
        (SELECT SUM(nominal_bayar) FROM sales AS s2 WHERE s2.id_faktur = s1.id_faktur) as total_nominal_bayar, 
        (nominal_faktur - (SELECT SUM(nominal_bayar) FROM sales AS s2 WHERE s2.id_faktur = s1.id_faktur)) as total_sisa_tagihan, 
        keterangan, DATE(update_time) as update_date 
        FROM sales AS s1 GROUP BY id_faktur";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $sisaTagihanClass = $row['total_sisa_tagihan'] > 0 ? 'bg-warning' : 'bg-success';
                            $dueDate = date('Y-m-d', strtotime($row['tanggal_faktur'] . ' + 45 days'));
                            $dueDateClass = (strtotime($dueDate) < time()) ? 'bg-danger' : '';
                            $rowClass = $sisaTagihanClass . ' ' . $dueDateClass;
                            ?>
                            <div class="col-md-4 card-item" data-id_faktur="<?php echo $row['id_faktur']; ?>" data-nama_sales="<?php echo $row['nama_sales']; ?>">
                                <div class="card <?php echo $rowClass; ?> mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Invoice ID: <?php echo $row['id_faktur']; ?></h5>
                                        <p class="card-text">Invoice Date: <?php echo $row['tanggal_faktur']; ?></p>
                                        <p class="card-text">Sales Name: <?php echo $row['nama_sales']; ?></p>
                                        <p class="card-text">Store Name: <?php echo $row['nama_toko']; ?></p>
                                        <p class="card-text">Total Invoice Amount: Rp. <?php echo number_format($row['nominal_faktur'], 0, ',', '.'); ?></p>
                                        <p class="card-text">Total Payment Amount: Rp. <?php echo number_format($row['total_nominal_bayar'], 0, ',', '.'); ?></p>
                                        <p class="card-text">Remaining Amount: Rp. <?php echo number_format($row['total_sisa_tagihan'], 0, ',', '.'); ?></p>
                                        <p class="card-text">Description: <?php echo $row['keterangan']; ?></p>
                                        <p class="card-text">Due Date: <?php echo $dueDate; ?></p>
                                        <p class="card-text">Update Time: <?php echo $row['update_date']; ?></p>
                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal" data-id_faktur="<?php echo $row['id_faktur']; ?>" data-tanggal_faktur="<?php echo $row['tanggal_faktur']; ?>" data-nama_sales="<?php echo $row['nama_sales']; ?>" data-nama_toko="<?php echo $row['nama_toko']; ?>" data-total_nominal_faktur="<?php echo $row['nominal_faktur']; ?>" data-total_nominal_bayar="<?php echo $row['total_nominal_bayar']; ?>" data-total_sisa_tagihan="<?php echo $row['total_sisa_tagihan']; ?>" data-keterangan="<?php echo $row['keterangan']; ?>" data-due_date="<?php echo $dueDate; ?>">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='col-md-12'><p>No sales data found</p></div>";
                    }
                    ?>
                </div>
                <input type="hidden" name="selected_invoices" id="selectedInvoices">
                <input type="hidden" name="remaining_amount" id="remainingAmount" value="">
                <input type="hidden" name="remaining_amounts" id="remainingAmounts" value="">
                <button type="submit" class="btn btn-primary mt-3">Send to Marketing Table</button>
            </form>
        </div>

        <div class="row" style="max-height: 100%;">
            <div class="col-md-12 col-xl-12">
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
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Keterangan</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Jatuh Tempo</th>
                                    <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Update Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT id_sales, id_faktur, tanggal_faktur, nama_sales, nama_toko, nominal_faktur, nominal_bayar, sisa_tagihan, keterangan, DATE(update_time) as update_date FROM sales";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $sisaTagihanClass = $row['sisa_tagihan'] > 0 ? 'bg-warning' : 'bg-success';
                                        $dueDate = date('Y-m-d', strtotime($row['tanggal_faktur'] . ' + 45 days'));
                                        $dueDateClass = (strtotime($dueDate) < time()) ? 'bg-danger' : '';
                                        $rowClass = $sisaTagihanClass . ' ' . $dueDateClass;
                                        echo "<tr class='$rowClass' data-bs-toggle='modal' data-bs-target='#detailModal' data-id_sales='" . $row['id_sales'] . "' data-id_faktur='" . $row['id_faktur'] . "' data-tanggal_faktur='" . $row['tanggal_faktur'] . "' data-nama_sales='" . $row['nama_sales'] . "' data-nama_toko='" . $row['nama_toko'] . "' data-nominal_faktur='" . $row['nominal_faktur'] . "' data-nominal_bayar='" . $row['nominal_bayar'] . "' data-sisa_tagihan='" . $row['sisa_tagihan'] . "' data-keterangan='" . $row['keterangan'] . "' data-due_date='" . $dueDate . "'>";
                                        echo "<td>" . $row['id_faktur'] . "</td>";
                                        echo "<td>" . $row['tanggal_faktur'] . "</td>";
                                        echo "<td>" . $row['nama_sales'] . "</td>";
                                        echo "<td>" . $row['nama_toko'] . "</td>";
                                        echo "<td>Rp. " . number_format($row['nominal_faktur'], 0, ',', '.') . "</td>";
                                        echo "<td>Rp. " . number_format($row['nominal_bayar'], 0, ',', '.') . "</td>";
                                        echo "<td>Rp. " . number_format($row['sisa_tagihan'], 0, ',', '.') . "</td>";
                                        echo "<td>" . $row['keterangan'] . "</td>";
                                        echo "<td>" . $dueDate . "</td>";
                                        echo "<td>" . $row['update_date'] . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9'>No sales data found</td></tr>";
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
                            <select class="form-control border border-dark p-2 js-example-basic-single" id="id_faktur" name="id_faktur" required>
                                <option value="">Select Invoice</option>
                                <?php
                                // Fetch invoice IDs from the database
                                $sql = "SELECT id_faktur FROM faktur WHERE id_faktur NOT IN (SELECT id_faktur FROM sales WHERE sisa_tagihan = 0)";
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
                                <input type="text" class="form-control border border-dark p-2" id="sisa_tagihan" name="sisa_tagihan" required>
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

        $(document).ready(function() {
            // Initialize Select2 on the select element
            $('#id_faktur').select2({
                placeholder: 'Select Invoice',
                allowClear: true,
                width: '100%'
            });

            // Autofill feature
            $('#id_faktur').on('change', function() {
                var idFaktur = this.value;
                if (idFaktur) {
                    fetch('spice/get_faktur_details.php?id_faktur=' + idFaktur)
                        .then(response => response.json())
                        .then(data => {
                            $('#tanggal_faktur').val(data.tanggal_faktur);
                            $('#nama_sales').val(data.nama_sales); // Populate nama_sales
                            $('#nama_toko').val(data.nama_toko);
                            $('#nominal_faktur').val(formatRupiah(data.nominal_faktur.toString(), 'Rp. '));
                            $('#sisa_tagihan').val(formatRupiah(data.sisa_tagihan.toString(), 'Rp. '));
                        });
                }
            });
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

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Sales Details</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="detail_id_sales">
                    <p><strong>Invoice ID:</strong> <span id="detail_id_faktur"></span></p>
                    <p><strong>Invoice Date:</strong> <span id="detail_tanggal_faktur"></span></p>
                    <p><strong>Sales Name:</strong> <span id="detail_nama_sales"></span></p>
                    <p><strong>Store Name:</strong> <span id="detail_nama_toko"></span></p>
                    <p><strong>Invoice Amount:</strong> <span id="detail_nominal_faktur"></span></p>
                    <p><strong>Payment Amount:</strong> <span id="detail_nominal_bayar"></span></p>
                    <p><strong>Remaining Amount:</strong> <span id="detail_sisa_tagihan"></span></p>
                    <p><strong>Description:</strong> <span id="detail_keterangan"></span></p>
                    <p><strong>Due Date:</strong> <span id="detail_due_date"></span></p>
                    <h6>Payment Details:</h6>
                    <ul id="payment_details_list"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a id="editButton" class="btn btn-primary">Edit</a>
                    <a href="spice/delete_sales.php" id="deleteButton" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Sales Data</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSalesForm" method="POST" action="spice/edit_sales.php">
                        <input type="hidden" id="edit_id_sales" name="id_sales">
                        <div class="form-group">
                            <label for="edit_nominal_bayar">Payment Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-2">Rp.</span>
                                </div>
                                <input type="text" class="form-control border border-dark p-2" id="edit_nominal_bayar" name="nominal_bayar" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_nominal_faktur">Invoice Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-2">Rp.</span>
                                </div>
                                <input type="text" class="form-control border border-dark p-2" id="edit_nominal_faktur" name="nominal_faktur" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_sisa_tagihan">Remaining Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-2">Rp.</span>
                                </div>
                                <input type="text" class="form-control border border-dark p-2" id="edit_sisa_tagihan" name="sisa_tagihan" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_keterangan">Description</label>
                            <textarea class="form-control border border-dark p-2" id="edit_keterangan" name="keterangan"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectedInvoices = [];
            var remainingAmounts = {};

            document.querySelectorAll('.card-item').forEach(function(card) {
                card.addEventListener('click', function() {
                    var idFaktur = this.getAttribute('data-id_faktur').trim(); // Ensure no extra spaces
                    var remainingAmount = this.querySelector('.card-text:nth-child(7)').textContent.split('Rp. ')[1].replace(/\./g, '');
                    if (this.classList.contains('selected-card')) {
                        this.classList.remove('selected-card');
                        selectedInvoices = selectedInvoices.filter(function(id) {
                            return id !== idFaktur;
                        });
                        delete remainingAmounts[idFaktur];
                    } else {
                        this.classList.add('selected-card');
                        selectedInvoices.push(idFaktur);
                        remainingAmounts[idFaktur] = remainingAmount;
                    }
                    document.getElementById('selectedInvoices').value = selectedInvoices.join(',');
                    document.getElementById('remainingAmounts').value = JSON.stringify(remainingAmounts);
                });
            });

            var detailModal = document.getElementById('detailModal');
            detailModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var idSales = button.getAttribute('data-id_sales');
                var idFaktur = button.getAttribute('data-id_faktur');
                var tanggalFaktur = button.getAttribute('data-tanggal_faktur');
                var namaSales = button.getAttribute('data-nama_sales');
                var namaToko = button.getAttribute('data-nama_toko');
                var totalNominalFaktur = parseFloat(button.getAttribute('data-total_nominal_faktur').replace(/[^0-9.-]+/g, ""));
                var totalNominalBayar = parseFloat(button.getAttribute('data-total_nominal_bayar').replace(/[^0-9.-]+/g, ""));
                var totalSisaTagihan = parseFloat(button.getAttribute('data-total_sisa_tagihan').replace(/[^0-9.-]+/g, ""));
                var keterangan = button.getAttribute('data-keterangan');
                var dueDate = button.getAttribute('data-due_date');

                var modalBody = detailModal.querySelector('.modal-body');

                modalBody.querySelector('#detail_id_faktur').textContent = idFaktur;
                modalBody.querySelector('#detail_tanggal_faktur').textContent = tanggalFaktur;
                modalBody.querySelector('#detail_nama_sales').textContent = namaSales;
                modalBody.querySelector('#detail_nama_toko').textContent = namaToko;
                modalBody.querySelector('#detail_nominal_faktur').textContent = 'Rp. ' + totalNominalFaktur.toLocaleString('id-ID');
                modalBody.querySelector('#detail_nominal_bayar').textContent = 'Rp. ' + totalNominalBayar.toLocaleString('id-ID');
                modalBody.querySelector('#detail_sisa_tagihan').textContent = 'Rp. ' + totalSisaTagihan.toLocaleString('id-ID');
                modalBody.querySelector('#detail_keterangan').textContent = keterangan;
                modalBody.querySelector('#detail_due_date').textContent = dueDate;

                fetch('spice/get_payment_details.php?id_faktur=' + idFaktur)
                    .then(response => response.json())
                    .then(data => {
                        var paymentDetailsList = modalBody.querySelector('#payment_details_list');
                        paymentDetailsList.innerHTML = '';
                        data.forEach((sales, index) => {
                            var listItem = document.createElement('li');
                            listItem.textContent = 'CICILAN ' + (index + 1) + ': Payment Date: ' + sales.tanggal_penagihan + ', Amount: Rp. ' + parseFloat(sales.nominal_bayar).toLocaleString('id-ID');
                            paymentDetailsList.appendChild(listItem);
                        });
                    });

                var editButton = detailModal.querySelector('#editButton');
                var deleteButton = detailModal.querySelector('#deleteButton');

                editButton.addEventListener('click', function() {
                    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    document.getElementById('edit_id_sales').value = idSales;
                    document.getElementById('edit_nominal_bayar').value = totalNominalBayar;
                    document.getElementById('edit_nominal_faktur').value = totalNominalFaktur;
                    document.getElementById('edit_sisa_tagihan').value = totalSisaTagihan;
                    document.getElementById('edit_keterangan').value = keterangan;
                    editModal.show();
                });

                deleteButton.href = 'spice/delete_sales.php?id_sales=' + idSales;
            });

            document.getElementById('searchInput').addEventListener('input', function() {
                var searchValue = this.value.toLowerCase();
                var cardItems = document.querySelectorAll('.card-item');
                cardItems.forEach(function(card) {
                    var idFaktur = card.getAttribute('data-id_faktur').toLowerCase();
                    var namaSales = card.getAttribute('data-nama_sales').toLowerCase();
                    if (idFaktur.includes(searchValue) || namaSales.includes(searchValue)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        document.getElementById('edit_nominal_bayar').addEventListener('input', function() {
            var nominalFaktur = parseRupiah(document.getElementById('edit_nominal_faktur').value) || 0;
            var nominalBayar = parseRupiah(this.value) || 0;
            var sisaTagihan = nominalFaktur - nominalBayar;

            document.getElementById('edit_sisa_tagihan').value = sisaTagihan;
        });

        document.getElementById('editSalesForm').addEventListener('submit', function(event) {
            var sisaTagihan = parseRupiah(document.getElementById('edit_sisa_tagihan').value) || 0;
            if (sisaTagihan < 0) {
                alert('Remaining amount cannot be negative.');
                event.preventDefault();
            }
        });
    </script>

    <?php include 'assets/footer.php' ?>

</body>

</html> 

</html>