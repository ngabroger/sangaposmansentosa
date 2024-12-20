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
    <style>
        body {
            font-family: Arial, sans-serif;
            color: black;
            zoom: 15%;
            padding: 100px;
        }

        .invoice-container {
            width: 100%;
            height: 60%;
            border: 1px solid black;
            padding: 20px;
            margin: 5px 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .f-bigger {
            font-size: 85px;
            font-weight: bold;
            letter-spacing: 3.5px;
        }

        .f-l {
            font-size: 75px;
            letter-spacing: 15.5px;
        }

        .header,
        .footer,
        .details,
        .table {
            width: 100%;
        }

        .header {
            text-align: left;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }

        .table th,
        .table td {
            text-align: left;
            font-weight: bold;
            padding-left: 10px;
        }
        table th:nth-child(3), .table td:nth-child(5) {
            padding-left: 20px;
            width: 10%;
        }
        .table th:nth-child(4), .table td:nth-child(5) {
            padding-left: 20px;
            width: 5%;
        }


        .table th:nth-child(5), .table td:nth-child(5) {
            padding-left: 20px;
            width: 18%;
        }

        .table th:nth-child(6), .table td:nth-child(6) {
            width: 20%;
        }

        .details {
            margin-bottom: 20px;
        }

        .details td {
            padding: 3px;
            vertical-align: top;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-weight: bold;
        }

        @media print {
            body {
                zoom: 20%;
            }

            .row {
                display: flex;
                page-break-inside: avoid;
            }

            .no-print {
                display: none;
            }
        }

        .margin-set {
            margin-bottom: 7%;
        }

        .ttd .col-4,
        .ttd .col-3,
        .ttd .col-2 {
            border: 1px solid black;
            padding-bottom: 200px;
        }

        .no-print {
            display: none;
        }
    </style>
</head>

<body>
<?php
$invoices = []; // Array untuk menampung semua data faktur

foreach ($id_faktur_array as $id_faktur) {
    $sql = "SELECT f.id_faktur, c.nama_toko, c.alamat, c.no_hp, c.id_toko, c.owner, f.tanggal, f.note, f.total_harga, c.nama_sales, c.area_lokasi,
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
        $formatted_price_total = "Rp " . number_format((float)$row['total_harga'], 0, ',', '.');
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
            'note' => $row['note'],
            'harga_terbilang' => $harga_terbilang,
            'formatted_price_total' => $formatted_price_total
        ];
    }
}
?>
<?php foreach ($invoices as $invoice): ?>
    <div class="invoice-container mt-4 margin-set ">
        <!-- Header -->
        <div class="header">
            <div class="row">
                <div class="col-6 f-bigger">
                    <p style="font-size : 120px">PT. SANGAP OSMAN SENTOSA</p>
                    <p>
                        Cibonong Kradenan Jl. Kampung Pisang<br>
                        No. 112 B RT.001/RW.006 Kode Pos 16913 - Cibonong - Jawa Barat<br>
                        e-Mail : sangaposmansentosa@gmail.com<br>
                        Telpon : 08179001304
                    </p>
                </div>
                <div class="col-6 text-end f-bigger">
                    <div class="w-100 text-center justify-content-end">
                        <p class="text-bold border border-dark" style="font-size : 150px">FAKTUR</p>
                        <p class="border border-dark">Nomor Faktur: <?= $invoice['id_faktur']; ?></p>
                    </div>
                    <p class="text-bold">Transfer <br>
                        Bank BRI(222101000449569)<br>
                        An Sangap Osman Sentosa
                    </p>
                </div>
            </div>
        </div>

        <!-- Faktur Section -->
        <div class="row f-l border-dark mb-5">
            <div class="col-6 m-0 border border-dark">
                <p class="fw-bold">Kepada Yth,</p>
                <p><?= $invoice['nama_toko']; ?></p>
                <p><?= $invoice['alamat']; ?></p>
                <p>No. Telp: <?= $invoice['no_hp']; ?></p>
            </div>
            <div class="row col-6 border border-dark">
                <div class="col-8 text-end">
                    <p>Nomor Cs:</p>
                    <p>Sales Penjualan:</p>
                    <p>Tanggal Faktur: </p>
                    <p>Tanggal Jatuh Tempo:</p>
                </div>
                <div class="col-4 text-start ps-2 font-bold">
                    <p><?= $invoice['id_toko']; ?></p>
                    <p><?= $invoice['nama_sales']; ?></p>
                    <p><?= $invoice['tanggal']; ?></p>
                    <p><?= $invoice['tanggal_jatuh_tempo']; ?></p>
                </div>
            </div>
        </div>
        <!-- Tabel Barang -->
        <table class="table f-l text-center">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kemasan</th>
                <th>Jumlah</th>
                <th>Harga Persatuan</th>
                <th>Jumlah harga</th>
            </tr>
            <?php 
            $no = 1; 
            foreach ($invoice['productNamesArray'] as $i => $productName):
                $quantity = $invoice['quantitiesArray'][$i];
                $kemasan = $invoice['kemasanArray'][$i];
                $formatted_price_product = $invoice['formatted_price_productArray'][$i];
                $total_item_price = $invoice['product_priceArray'][$i] * $quantity;
                $formatted_totalItemPrice = "Rp " . number_format((float)$total_item_price, 0, ',', '.');
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $productName; ?></td>
                <td><?= $kemasan; ?></td>
                <td><?= $quantity; ?></td>
                <td class="ps-5"><?= $formatted_price_product; ?></td>
                <td class="ps-5"><?= $formatted_totalItemPrice; ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5" class="Note f-bigger">
                    <p class="text-bold">Note</p>
                    <p><?= $invoice['note']; ?></p>
                    <p class="fst-italic f-bigger"><?= $invoice['harga_terbilang']; ?></p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" class="total">Total Harga</td>
                <td class="ps-5"><?= $invoice['formatted_price_total']; ?></td>
            </tr>
            <tr>
                <td colspan="5" class="total">Diskon</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" class="total">Jumlah Total</td>
                <td class="ps-5 fw-bolder"><?= $invoice['formatted_price_total']; ?></td>
            </tr>
        </table>

        <div class="footer mb-5 f-bigger">
            <p class="text-bold">Pembayaran Maksimal sampai dengan tanggal Jatuh tempo tertera</p>
            <p class="text-bold">TERIMAKASIH KEPADA KEPERCAYAAN ANDA PADA PT SANGAP OSMAN SENTOSA</p>
        </div>

        <div class="row mb-5 text-center ttd">
            <div class="col-4 mb-5 text-center">
                <p class="f-l border-dark border-bottom">Hormat Kami</p>
            </div>
            <div class="col-3 mb-5">
                <p class="f-l border-dark border-bottom">Admin</p>
            </div>
            <div class="col-3 mb-5">
                <p class="f-l border-dark border-bottom">Marketing</p>
            </div>
            <div class="col-2 mb-5">
                <p class="f-l border-dark border-bottom">Penerima</p>
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