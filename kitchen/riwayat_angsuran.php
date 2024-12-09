<?php
include('connection/db_connection.php');

$id_faktur_array = isset($_POST['id_faktur']) ? $_POST['id_faktur'] : [];

// Fetch data for each id_faktur
$data = [];
foreach ($id_faktur_array as $id_faktur) {
    $sql = "SELECT f.id_faktur, c.nama_toko, f.tanggal, f.total_harga, 
            DATE_ADD(f.tanggal, INTERVAL 45 DAY) AS tanggal_jatuh_tempo 
            FROM faktur f 
            JOIN customer c ON f.id_toko = c.id_toko 
            WHERE f.id_faktur = '$id_faktur'";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        $row['formatted_total_harga'] = "Rp " . number_format((float)$row['total_harga'], 0, ',', '.');
        $data[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Angsuran Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        tbody {
            border: 1px solid black;
        }
        td, th {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="container-fluid" id="angsuran">
        <?php if (!empty($data)): ?>
            <?php foreach ($data as $invoice): ?>
            <div class="container">
                <div class="d-flex justify-content-center">
                    <h2 class="p-3">Riwayat Angsuran Toko</h2>
                </div>
               
                <div class="container">
                    <div class="row">
                        <div class="col-6 text-start">
                            <p> Nama Toko : <?php echo $invoice['nama_toko']; ?> </p>
                            <p> Nomor Faktur : <?php echo $invoice['id_faktur']; ?> </p>
                        </div>
                        <div class="col-6 text-end">
                            <p> Tanggal Jatuh Tempo : <?php echo $invoice['tanggal_jatuh_tempo']; ?> </p>
                            <p> Jumlah Angsuran : <?php echo $invoice['formatted_total_harga']; ?> </p>
                        </div>
                    </div>
                </div>
               
            </div>
        
        <div class="container-fluid mb-5">
            <div class="table ">
                <table class="table table-striped">
                    <thead class="border border-dark">
                        <tr>
                            <th scope="col">Mingguan</th>
                            <th scope="col">Tanggal Angsuran</th>
                            <th scope="col">Jumlah Angsuran</th>
                            <th scope="col">Sisa Tagihan</th>
                            <th scope="col">Paraf</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr >
                            <th class="p-5" scope="row"></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> <?php endforeach; ?>
        <?php else: ?>
            <div class="container">
                <p>No data selected.</p>
            </div>
        <?php endif; ?>
    </div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    
</body>
</html>