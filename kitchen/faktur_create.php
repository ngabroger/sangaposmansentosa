<?php
    $nama_toko = $_POST['nama_toko'];
    $id_toko = $_POST['id_toko'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $owner = $_POST['owner'];
    $tanggal = $_POST['tanggal'];
    $productNames = $_POST['product_names'];
    $quantities = $_POST['quantities'];
    $kemasan = $_POST['product_kemasan'];
    $product_price = $_POST['product_price'];
   
    $tanggal_jatuh_tempo = date('Y-m-d', strtotime($tanggal . ' + 45 days'));
    
    $note = $_POST['note'];
    $total_harga = $_POST['total_harga'];

    $productNamesArray = explode(',', $productNames);
    $quantitiesArray = explode(',', $quantities);
    $kemasanArray = explode(',', $kemasan);
    $product_priceArray = explode(',', $product_price);

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
   

    $harga_terbilang = " TERBILANG ". strtoupper(terbilang($total_harga)) . " RUPIAH";
    
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur PT. Sangap Osman Sentosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        body { font-family: Times, sans-serif; color: black; }
        .invoice-container {
            
            width: 100%; /* Lebar lebih kecil */
            height: 60%; /* Setengah tinggi halaman */
            border: 1px solid black;
            padding: 20px;
            margin: 5px 0;
            box-sizing: border-box;
            overflow: hidden;
        }
        .f-bigger{
            font-size: 60px;
            font-weight: bold;
        }
        .f-l{
            font-size: 50px;
        }
        .header, .footer, .details, .table { width: 100%; }
        .header { text-align: left; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .table, .table th, .table td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
        .table th, .table td { text-align: left; }
        .details { margin-bottom: 20px; }
        .details td { padding: 3px; vertical-align: top; }
        .total { text-align: right; font-weight: bold; }
        .footer { text-align: center; margin-top: 50px; font-weight: bold; }
        @media print {
            body { zoom: 50%; } /* Sesuaikan zoom jika diperlukan */
            .row {
                display: flex;
                
                page-break-inside: avoid; /* Mencegah pemisahan elemen di tengah halaman */
            }

        }
        .margin-set{
            margin-bottom: 17%;

        }
        .ttd{
            margin-bottom:30%
        }
    </style>
  
</head>
<body>

<!-- FAKTUR  -->
<div class="invoice-container mt-4 margin-set">
    <!-- Header -->
    
    <div class="header">
        <div class="row">
            <div class="col-6 f-bigger">
            <p>PT. SANGAP OSMAN SENTOSA</p>
            <p class="">Cibonong Kradenan Jl. Kampung Pisang<br>
            No. 112 B RT.001/RW.006 Kode Pos 16913 - Cibonong - Jawa Barat<br>
            e-Mail : sangaposmansentosa@gmail.com<br>
            Telpon : 08179001304
            </p>
            </div>
            <div class=" col-6 text-end f-bigger">
               
                <p class=" text-bold " >FAKTUR</p>
            <p class="">Nomor Faktur: <?php echo $_POST['id_faktur']; ?></p>
           <p class=" text-bold">Transfer <br>
           Bank BRI(222101000449569)<br>
            An Sangap Osman Sentosa
            </p> 
            
            </div>
        </div>
        
    </div>
   

    <!-- Faktur Section -->
    <div class="row  f-l">
        <div class="col-3 m-0 ">
            <p class="">Kepada Yth,</p>
            <p class=""> <?php echo $nama_toko; ?></p>
            <p class=""><?php echo $alamat; ?></p>
            <p class="" >No. Telp: <?php echo $no_hp; ?></p>
        </div>
        <div class="col-7 text-end">
            <p class="">Nomor Cs:</p>
            <p class="">Ware House:</p>
            <p class="">Tanggal Faktur: </p>
            <p class="">Tanggal Jatuh Tempo:</p>
        </div>
        <div class="col-2 text-start">
            <p class=""><?php echo $id_toko; ?></p>
            <p class="">Rizal</p>
            <p class=""><?php echo $tanggal; ?></p>
            <p class=""><?php echo $tanggal_jatuh_tempo;?></p>
        </div>
    </div>
    

    
    <!-- Tabel Barang -->
    <table class="table f-l">
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kemasan</th>
            <th>Jumlah</th>
            <th>Harga Persatuan</th>
            <th>Jumlah</th>
        </tr>
        <?php
        $no = 1;
        for ($i = 0; $i < count($productNamesArray); $i++) {
            $productName = $productNamesArray[$i];
            $quantity = $quantitiesArray[$i];
            $kemasan = $kemasanArray[$i];
            $product_price = $product_priceArray[$i];
            $total_item_price = $product_price * $quantity;
            // Assuming you have a way to get the price per unit
            
            
            echo "<tr>";
            echo "<td>$no</td>";
            echo "<td>$productName</td>";
            echo "<td>$kemasan</td>"; // Add appropriate data if available
            echo "<td>$quantity</td>";
            echo "<td>$product_price</td>";
            echo "<td>$total_item_price</td>";
            echo "</tr>";
            $no++;
        }
        ?>
        <tr>
            <td colspan="5" class="Note f-bigger ">
                <p class="text-bold ">Note</p>
                <p class=""><?php echo $note; ?></p>
                <p class=" fst-italic f-bigger"><?php echo $harga_terbilang ?></p>

            </td>
            <td>
        
            </td>
            
        </tr>
        <tr>
            <td colspan="5" class="total">Total Harga</td>
            <td><?php echo $total_harga; ?></td>
        </tr>
        <tr>
            <td colspan="5" class="total">Diskon</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="5" class="total">Jumlah Total</td>
            <td><?php echo $total_harga; ?></td>
        </tr>
    </table>

    <div class="footer  mb-5 f-bigger">
        <p class="text-bold">Pembayaran Maksimal sampai dengan tanggal Jatuh tempo tertera</p>
        <p class="text-bold">TERIMAKASIH KEPADA KEPERCAYAAN ANDA PADA PT SANGAP OSMAN SENTOSA</p>
    </div>

    <div class="row mb-5 text-center ttd">
        <div class="col-4  mb-5text-center">
            <p class="f-l">Hormat Kami</p>
            <p class="f-l"></p>
        </div>
        <div class="col-3 mb-5">
            <p class="f-l">Gudang</p>

        </div>
        <div class="col-3 mb-5">
            <p class="f-l">Driver</p>
           
        </div>
        <div class="col-2 mb-5">
            <p class="f-l">Penerima</p>
          
        </div>
        </div>
        </div>
    <div>

</div>
<?php
function generateFakturId() {
    $part1 = sprintf("%02d", rand(0, 99));
    $part2 = sprintf("%02d", rand(0, 99));
    $part3 = sprintf("%04d", rand(0, 9999));
    return "SJN/$part1/$part2/$part3";
}
    $noJalan = generateFakturId();

?>
<!-- SURAT JALAN -->
<div class="invoice-container mt-4">
    <!-- Header -->
    
    <div class="header">
        <div class="row f-bigger">
            <div class="col-6 ">
            <p>PT. SANGAP OSMAN SENTOSA</p>
            <p class="">Cibonong Kradenan Jl. Kampung Pisang<br>
            No. 112 B RT.001/RW.006 Kode Pos 16913 - Cibonong - Jawa Barat<br>
            e-Mail : sangaposmansentosa@gmail.com<br>
            Telpon : 08179001304
            </p>
            </div>
            <div class="col-6 text-end">
            <p class="text-bold " >SURAT JALAN</p>
            <p class="">Nomor SURAT JALAN: <?php echo $noJalan; ?></p>
         
            
            </div>
        </div>
        
    </div>
   

    <!-- SURAT JALAN Section -->
    <div class="row ">
        <div class="col-3 m-0 fs-2">
            <p class="f-l">Kepada Yth,</p>
            <p class="f-l"> <?php echo $nama_toko; ?></p>
            <p class="f-l"><?php echo $alamat; ?></p>
            <p class="f-l" >No. Telp: <?php echo $no_hp; ?></p>
        </div>
 
        <div class="col-7 text-end">
            <p class="f-l">Nomor Cs:</p>
            <p class="f-l">Ware House:</p>
            <p class="f-l">Tanggal Faktur: </p>
            <p class="f-l">Pengirim:</p>
        </div>
        <div class="col-2 text-start">
            <p class="f-l"><?php echo $id_toko; ?></p>
            <p class="f-l">Rizal</p>
            <p class="f-l"><?php echo $tanggal; ?></p>
            <p class="f-l">Jonggi</p>

        </div>
        
    </div>
    

    
    <!-- Tabel Barang -->
    <table class="table f-l">
        <tr>
            <th>Banyak</th>
            <th>Kemasan</th>
            <th>Nama Barang</th>
            <th>Kendaraan</th>
            <th>No pol</th>
        </tr>
        <?php

        for ($i = 0; $i < count($productNamesArray); $i++) {
            $productName = $productNamesArray[$i];
            $quantity = $quantitiesArray[$i];
            $kemasan = $kemasanArray[$i];
            $product_price = $product_priceArray[$i];
            $total_item_price = $product_price * $quantity;
            // Assuming you have a way to get the price per unit
            
            
            echo "<tr>";
            echo "<td>$quantity</td>";
            echo "<td>$kemasan</td>"; // Add appropriate data if available
            echo "<td>$productName</td>";
            echo "<td>Mobil</td>";
            echo "<td></td>";
            echo "</tr>";
            $no++;
        }
        ?>
        <tr>
            <td colspan="6" class="Note ">
                <p class="text-bold f-l">Note</p>
                <p class="f-l"><?php echo $note; ?></p>

            </td>
            
        </tr>
        
    </table>

 

    <div class="row mb-5 text-center">
        <div class="col-4  mb-5 text-center">
            <p class="f-l">Hormat Kami</p>
            <p class="f-l"></p>
        </div>
        <div class="col-3 mb-5">
            <p class="f-l">Gudang</p>

        </div>
        <div class="col-3 mb-5">
            <p class="f-l">Driver</p>
           
        </div>
        <div class="col-2 mb-5">
            <p class="f-l">Penerima</p>
          
        </div>
        </div>
        </div>
    <div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

<script>
    function printInvoice() {
        window.print();
    }
</script>

</body>
</html>