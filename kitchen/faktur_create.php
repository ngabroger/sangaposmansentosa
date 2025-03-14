<?php
include('connection/db_connection.php');

$id_faktur_array = $_POST['id_faktur'];

function terbilang($angka) {
    $angka = abs($angka);
    $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
    $temp = "";

    if ($angka < 12) {
        $temp = " " . $huruf[$angka];
    } else if ($angka < 20) {
        $temp = terbilang($angka - 10) . " Belas";
    } else if ($angka < 100) {
        $temp = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $temp = " Seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $temp = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $temp = " Seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $temp = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $temp = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
    } else if ($angka < 1000000000000) {
        $temp = terbilang($angka / 1000000000) . " Milyar" . terbilang(fmod($angka, 1000000000));
    }

    return $temp;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur PT. Sangap Osman Sentosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  
</head>

<body>
<?php
$invoices = []; // Array untuk menampung semua data faktur

foreach ($id_faktur_array as $id_faktur) {
    $sql = "SELECT f.id_faktur, c.nama_toko, c.alamat, c.no_hp, c.id_toko, c.owner, f.tanggal, f.note,f.discount, f.total_harga, c.nama_sales, c.area_lokasi,
            GROUP_CONCAT(p.nama_product) AS product_names, 
            GROUP_CONCAT(p.type_product) AS product_kemasan, 
            GROUP_CONCAT(CASE WHEN c.area_lokasi = 'Luar Kota' THEN p.price_luarkota ELSE p.price END) AS product_price, 
            GROUP_CONCAT(fd.quantity) AS quantities 
            FROM faktur f 
            JOIN faktur_detail fd ON f.id_faktur = fd.id_faktur 
            JOIN product p ON fd.id_product = p.id_product 
            JOIN customer c ON f.id_toko = c.id_toko 
            WHERE f.id_faktur = '$id_faktur'
            GROUP BY f.id_faktur";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        $productNamesArray = explode(',', $row['product_names']);
        $quantitiesArray = explode(',', $row['quantities']);
        $kemasanArray = explode(',', $row['product_kemasan']);
        $product_priceArray = explode(',', $row['product_price']);
        $formatted_price_productArray = array_map(function ($price) {
            return "Rp " . number_format((float)$price, 0, ',', '.');
        }, $product_priceArray);
        $formatted_price_discount = "Rp " . number_format((float)$row['discount'], 0, ',', '.');
        $formatted_price_total = "Rp " . number_format((float)$row['total_harga'], 0, ',', '.');
        $total_after_discount = $row['total_harga'] - $row['discount'];
        $formatted_total_after_discount = "Rp " . number_format((float)$total_after_discount, 0, ',', '.');
        $tanggal_jatuh_tempo = date('Y-m-d', strtotime($row['tanggal'] . ' + 45 days'));

        $harga_terbilang = " TERBILANG " . strtoupper(terbilang($row['total_harga'])) . " RUPIAH";

        // Simpan data ke dalam array invoices
        $invoices[] = [
            'id_faktur' => $row['id_faktur'],
            'nama_toko' => $row['nama_toko'],
            'alamat' => $row['alamat'],
            'no_hp' => $row['no_hp'],
            'id_toko' => $row['id_toko'],
            'nama_sales' => $row['nama_sales'],
            'tanggal' => $row['tanggal'],
            'tanggal_jatuh_tempo' => $tanggal_jatuh_tempo,
            'productNamesArray' => $productNamesArray,
            'quantitiesArray' => $quantitiesArray,
            'kemasanArray' => $kemasanArray,
            'formatted_price_productArray' => $formatted_price_productArray,
            'product_priceArray' => $product_priceArray,
            'discount' => $formatted_price_discount,
            'note' => $row['note'],
            'harga_terbilang' => $harga_terbilang,
            'formatted_price_total' => $formatted_price_total,
            'formatted_total_after_discount' => $formatted_total_after_discount
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                width: 100%;
                max-width: 100%;
                page-break-after: always;
            }
            .table th, .table td {
                border: 1px solid black !important;
                padding: 5px !important;
                font-size: 16px !important;
            }
        }
        .margin-custom {
            margin-top:150px;
        }
    </style>
</head>
<body>

<?php foreach ($invoices as $invoice): ?>
    <div class="container-fluid p-5 ">
        <!-- Header -->
        <div class="row">
            <div class="col-8">
                <h2 class="fw-bold">PT. SANGAP OSMAN SENTOSA</h2>
                <p class="mb-1">Cibonong Kradenan Jl. Kampung Pisang</p>
                <p class="mb-1">No. 112 B RT.001/RW.006 Kode Pos 16913 - Cibonong - Jawa Barat</p>
                <p class="mb-1">Email: sangaposmansentosa@gmail.com | Telp: 08179001304</p>
            </div>
            <div class="col-4 text-end">
                <h3 class="fw-bold border border-dark p-2">FAKTUR</h3>
                <p class="fw-bold">Nomor Faktur: <?= $invoice['id_faktur']; ?></p>
                <p class="fw-bold">Bank BRI: 222101000449569</p>
                <p>An Sangap Osman Sentosa</p>
            </div>
        </div>

        <!-- Informasi Pelanggan -->
        <div class="row border border-dark p-2 mt-3">
            <div class="col-6">
                <p class="fw-bold mb-1">Kepada Yth:</p>
                <p class="mb-1"><?= $invoice['nama_toko']; ?></p>
                <p class="mb-1"><?= $invoice['alamat']; ?></p>
                <p>No. Telp: <?= $invoice['no_hp']; ?></p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1">Nomor Cs: <strong><?= $invoice['id_toko']; ?></strong></p>
                <p class="mb-1">Sales: <strong><?= $invoice['nama_sales']; ?></strong></p>
                <p class="mb-1">Tanggal Faktur: <strong><?= $invoice['tanggal']; ?></strong></p>
                <p class="mb-1">Jatuh Tempo: <strong><?= $invoice['tanggal_jatuh_tempo']; ?></strong></p>
            </div>
        </div>

        <!-- Tabel Barang -->
        <div class="table-responsive mt-3">
            <table class="table table-bordered text-center fs-5">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kemasan</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    foreach ($invoice['productNamesArray'] as $i => $productName):
                        $quantity = $invoice['quantitiesArray'][$i];
                        $kemasan = $invoice['kemasanArray'][$i];
                        $formatted_price_product = $invoice['formatted_price_productArray'][$i];
                        $total_item_price = $invoice['product_priceArray'][$i] * $quantity;
                        $formatted_totalItemPrice = "Rp " . number_format($total_item_price, 0, ',', '.');
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $productName; ?></td>
                        <td><?= $kemasan; ?></td>
                        <td><?= $quantity; ?></td>
                        <td><?= $formatted_price_product; ?></td>
                        <td class='text-end'><?= $formatted_totalItemPrice; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class='border border-3 border-dark'>
                        <td colspan="5" class="fw-bold text-end">Total Harga</td>
                        <td class='text-end'><?= $invoice['formatted_price_total']; ?></td>
                    </tr>
                    <tr class='border border-3 border-dark'>
                        <td colspan="5" class="fw-bold text-end">Diskon</td>
                        <td class='text-end'><?= $invoice['discount']; ?></td>
                    </tr >
                    <tr class='border border-3 border-dark'>
                        <td colspan="5" class="fw-bold text-end">Jumlah Total</td>
                        <td  class='text-end'><?= $invoice['formatted_price_total']; ?></td>
                    </tr>
                    <tr class='border border-3 border-dark'>
                        <td colspan="6" class="fw-bold"><?= $invoice['harga_terbilang']; ?></td>
                    </tr>
                   
                    
                </tbody>
            </table>
            
        </div>
        <div class="row border border-dark p-2 mt-3">
                <p class="fw-bold">Note:</p>
                <p><?= $invoice['note']; ?></p>
            </div>

        <!-- Total dan Diskon -->
        

        <!-- Footer -->
        <div class="text-center mt-3">
            <p class="fw-bold">Pembayaran maksimal sampai dengan tanggal jatuh tempo tertera</p>
            <p class="fw-bold">TERIMAKASIH ATAS KEPERCAYAAN ANDA PADA PT. SANGAP OSMAN SENTOSA</p>
        </div>

        <!-- Tanda Tangan -->
        <div class="row text-center margin-custom">
            <div class="col-3">
                <p class="border-top border-dark pt-2">Hormat Kami</p>
            </div>
            <div class="col-3">
                <p class="border-top border-dark pt-2">Admin</p>
            </div>
            <div class="col-3">
                <p class="border-top border-dark pt-2">Marketing</p>
            </div>
            <div class="col-3">
                <p class="border-top border-dark pt-2">Penerima</p>
            </div>
        </div>
    </div>
<?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        function printInvoice() {
            document.querySelector('.no-print').style.display = 'none';
            window.print();
            document.querySelector('.no-print').style.display = 'block';
        }

        document.getElementById('toggleSuratJalan').addEventListener('change', function() {
            const suratJalanContainer = document.getElementById('suratJalanContainer');
            if (this.checked) {
                suratJalanContainer.style.display = 'block';
            } else {
                suratJalanContainer.style.display = 'none';
            }
        });
    </script>
</body>

</html>