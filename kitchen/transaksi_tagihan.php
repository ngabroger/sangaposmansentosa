<?php
include('connection/db_connection.php');
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan</title>
    <?php include 'assets/header.php'; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>
<style>
 
</style>
<body class="g-sidenav-show bg-gray-200">
    <?php include 'widget/navbar.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Transaksi Penjualan</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">Transaksi Penjualan</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
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
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row" style="max-height: 100%;">
                <div class="col-md-12 col-xl-12">
                    <div class="card p-3">
                        <div class="table-responsive">
                            <form id="reportForm" action="spice/generate_report.php" method="POST">
                                <table id="transaksiTable" class=" align-items-center mb-0">
                                    <thead class="border border">
                                        <tr>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Select</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Transaksi</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Timestamp</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Toko</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="width: 25%;">Jenis Barang</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sistem Pembayaran</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Point</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Sales</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                            <th class="border text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kirim Whatsapp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT tp.*, c.nama_toko, c.nama_sales FROM transaksi_penjualan tp JOIN customer c ON tp.ID_Toko = c.id_toko";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            $row_class = $row['Status'] == 'Belum dikirim' ? 'bg-danger' : 'bg-success';
                                            echo "<tr class='{$row_class}'>";
                                            echo "<td class='border'><input type='checkbox' class='select-checkbox' value='{$row['ID_Transaksi']}'></td>";
                                            echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['ID_Transaksi']}</h6></div></td>";
                                            echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['Timestamp']}</h6></div></td>";
                                            echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['nama_toko']}</h6></div></td>";
                                            echo "<td class='border' ><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['Jenis_Barang']}</h6></div></td>";
                                            echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['Sistem_Pembayaran']}</h6></div></td>";
                                            echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['Status']}</h6></div></td>";
                                            echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['Point']}</h6></div></td>";
                                            echo "<td class='border'><div class=''><h6 class='text-sm font-weight-normal mb-0'>{$row['nama_sales']}</h6></div></td>";
                                            echo "<td class='border'><div class=''><a data-bs-toggle='modal' data-bs-target='#editModal{$row['ID_Transaksi']}' class='btn btn-warning'><i class='material-icons'>edit</i></a></div></td>";
                                            echo "<td class='border'><div class=''><form id='sendWhatsappForm{$row['ID_Transaksi']}' action='send_whatsapp.php' method='POST' target='_blank'>
                                                    <input type='hidden' name='id_transaksi' value='{$row['ID_Transaksi']}'>
                                                    <input type='hidden' name='nama_toko' value='{$row['nama_toko']}'>
                                                    <input type='hidden' name='jenis_barang' value='{$row['Jenis_Barang']}'>
                                                    <input type='hidden' name='sistem_pembayaran' value='{$row['Sistem_Pembayaran']}'>
                                                    <input type='hidden' name='status' value='{$row['Status']}'>
                                                    <input type='hidden' name='point' value='{$row['Point']}'>
                                                    <input type='hidden' id='whatsappRecipientInput{$row['ID_Transaksi']}' name='recipient' value='+6285156295013'>
                                                    <button type='button' class='btn btn-success' onclick='submitWhatsappForm({$row['ID_Transaksi']})'>Whatsapp Admin</button>
                                                  </form></div></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary mt-3" onclick="submitReportForm()">Generate Report</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <?php
    $result->data_seek(0); // Reset result pointer to the beginning
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='modal fade' id='editModal{$row['ID_Transaksi']}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel{$row['ID_Transaksi']}' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='editModalLabel{$row['ID_Transaksi']}'>Edit Transaksi</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <form action='spice/edit_transaksi.php' method='POST'>
                            <input type='hidden' name='id_transaksi' value='{$row['ID_Transaksi']}'>
                            <div class='form-group '>
                                <label for='status'>Status</label>
                                <select class='form-control border p-3' id='status' name='status'>
                                    <option value='Belum dikirim' " . ($row['Status'] == 'Belum dikirim' ? 'selected' : '') . ">Belum dikirim</option>
                                    <option value='Sudah dikirim' " . ($row['Status'] == 'Sudah dikirim' ? 'selected' : '') . ">Sudah dikirim</option>
                                </select>
                            </div>
                            <div class='form-group mb-2'>
                                <label for='point'>Point</label>
                                <input type='number' class='form-control border p-2' id='point' name='point' value='{$row['Point']}'>
                            </div>
                            <button type='submit' class='btn btn-primary'>Save changes</button>
                        </form>
                        <form action='spice/delete_transaksi.php' method='POST' class='mt-2'>
                            <input type='hidden' name='id_transaksi' value='{$row['ID_Transaksi']}'>
                            <button type='submit' class='btn btn-danger'>Delete</button>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div>";
    }
    ?>

    <?php include 'widget/modal.php'; ?>
    <?php include 'assets/footer.php'; ?>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#transaksiTable').DataTable();
        });

        function submitWhatsappForm(id) {
            document.getElementById('sendWhatsappForm' + id).submit();
        }

        function submitReportForm() {
            var selected = [];
            $('.select-checkbox:checked').each(function() {
                selected.push($(this).val());
            });
            $('<input>').attr({
                type: 'hidden',
                name: 'selected_ids',
                value: selected.join(',')
            }).appendTo('#reportForm');
            $('#reportForm').submit();
        }
    </script>
</body>
</html>
