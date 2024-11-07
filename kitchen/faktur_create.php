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
   
  
    
    $note = $_POST['note'];
    $total_harga = $_POST['total_harga'];

    $productNamesArray = explode(',', $productNames);
    $quantitiesArray = explode(',', $quantities);
    $kemasanArray = explode(',', $kemasan);
    $product_priceArray = explode(',', $product_price);
   
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur PT. Sangap Osman Sentosa</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-container {
            
            width: 100%; /* Lebar lebih kecil */
            height: 50%; /* Setengah tinggi halaman */
            border: 1px solid black;
            padding: 20px;
            margin: 5px 0;
            box-sizing: border-box;
            overflow: hidden;
        }
        .header, .footer, .details, .table { width: 100%; }
        .header { text-align: left; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .table, .table th, .table td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
        .table th, .table td { text-align: left; }
        .details { margin-bottom: 20px; }
        .details td { padding: 3px; vertical-align: top; }
        .total { text-align: right; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-weight: bold; }
        @media print {
            body { zoom: 33%; } /* Sesuaikan zoom jika diperlukan */
            .row {
                display: flex;
                
                page-break-inside: avoid; /* Mencegah pemisahan elemen di tengah halaman */
            }

        }
        .margin-set{
            margin-bottom: 17%;

        }
    </style>
    <?php include 'assets/header.php'; ?>
</head>
<body>

<!-- FAKTUR  -->
<div class="invoice-container mt-4 margin-set">
    <!-- Header -->
    
    <div class="header">
        <div class="row">
            <div class="col-6">
            <h2>PT. SANGAP OSMAN SENTOSA</h2>
            <p class="fs-3">Cibonong Kradenan Jl. Kampung Pisang<br>
            No. 112 B RT.001/RW.006 Kode Pos 16913 - Cibonong - Jawa Barat<br>
            e-Mail : sangaposmansentosa@gmail.com<br>
            Telpon : 08179001304
            </p>
            </div>
            <div class="col-6 text-end">
            <p class="fs-1 text-bold " >FAKTUR</p>
            <p class="fs-3">Nomor Faktur: <?php echo $_POST['id_faktur']; ?></p>
           <p class="fs-3">METODE PEMBAYARAN <br>
           Bank BRI(222101000449569)<br>
            An Sangap Osman Sentosa
            </p> 
            
            </div>
        </div>
        
    </div>
   

    <!-- Faktur Section -->
    <div class="row ">
        <div class="col-3 m-0 fs-2">
            <p class="fs-4">Kepada Yth,</p>
            <p class="fs-4"> <?php echo $nama_toko; ?></p>
            <p class="fs-4"><?php echo $alamat; ?></p>
            <p class="fs-4" >No. Telp: <?php echo $no_hp; ?></p>
        </div>
        <div class="col-4 text-end">
            <p class="fs-4">Nomor Cs:</p>
            <p class="fs-4">Ware House:</p>
            <p class="fs-4">Tanggal Faktur: </p>
            <p class="fs-4">Pengirim:</p>
        </div>
        <div class="col-4 text-start">
            <p class="fs-4"><?php echo $id_toko; ?></p>
            <p class="fs-4">Rizal</p>
            <p class="fs-4"><?php echo $tanggal; ?></p>
            <p class="fs-4">Jonggi</p>
        </div>
    </div>
    

    
    <!-- Tabel Barang -->
    <table class="table fs-3">
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
            <td colspan="5" class="Note ">
                <p class="text-bold fs-3">Note</p>
                <p class="fs-3"><?php echo $note; ?></p>

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

    <div class="footer  mb-5">
        <p class="fs-3 text-bold">Pembayaran Maksimal sampai dengan tanggal Jatuh tempo tertera</p>
        <p class="fs-3 text-bold">TERIMAKASIH KEPADA KEPERCAYAAN ANDA PADA PT SANGAP OSMAN SENTOSA</p>
    </div>

    <div class="row mb-5 text-center">
        <div class="col-4  mb-5text-center">
            <p class="fs-3">Hormat Kami</p>
            <p class="fs-3"></p>
        </div>
        <div class="col-3 mb-5">
            <p class="fs-3">Gudang</p>

        </div>
        <div class="col-3 mb-5">
            <p class="fs-3">Driver</p>
           
        </div>
        <div class="col-2 mb-5">
            <p class="fs-3">Penerima</p>
          
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
        <div class="row">
            <div class="col-6">
            <h2>PT. SANGAP OSMAN SENTOSA</h2>
            <p class="fs-3">Cibonong Kradenan Jl. Kampung Pisang<br>
            No. 112 B RT.001/RW.006 Kode Pos 16913 - Cibonong - Jawa Barat<br>
            e-Mail : sangaposmansentosa@gmail.com<br>
            Telpon : 08179001304
            </p>
            </div>
            <div class="col-6 text-end">
            <p class="fs-1 text-bold " >SURAT JALAN</p>
            <p class="fs-3">Nomor SURAT JALAN: <?php echo $noJalan; ?></p>
         
            
            </div>
        </div>
        
    </div>
   

    <!-- SURAT JALAN Section -->
    <div class="row ">
        <div class="col-3 m-0 fs-2">
            <p class="fs-4">Kepada Yth,</p>
            <p class="fs-4"> <?php echo $nama_toko; ?></p>
            <p class="fs-4"><?php echo $alamat; ?></p>
            <p class="fs-4" >No. Telp: <?php echo $no_hp; ?></p>
        </div>
        <div class="col-4 text-end">
            <p class="fs-4">Nomor Cs:</p>
            <p class="fs-4">Ware House:</p>
            <p class="fs-4">Tanggal Faktur: </p>
            <p class="fs-4">Pengirim:</p>
        </div>
        <div class="col-4 text-start">
            <p class="fs-4"><?php echo $id_toko; ?></p>
            <p class="fs-4">Rizal</p>
            <p class="fs-4"><?php echo $tanggal; ?></p>
            <p class="fs-4">Jonggi</p>
        </div>
    </div>
    

    
    <!-- Tabel Barang -->
    <table class="table fs-3">
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
            <td colspan="4" class="Note ">
                <p class="text-bold fs-3">Note</p>
                <p class="fs-3"><?php echo $note; ?></p>

            </td>
            
        </tr>
        
    </table>

 

    <div class="row mb-5 text-center">
        <div class="col-4  mb-5text-center">
            <p class="fs-3">Hormat Kami</p>
            <p class="fs-3"></p>
        </div>
        <div class="col-3 mb-5">
            <p class="fs-3">Gudang</p>

        </div>
        <div class="col-3 mb-5">
            <p class="fs-3">Driver</p>
           
        </div>
        <div class="col-2 mb-5">
            <p class="fs-3">Penerima</p>
          
        </div>
        </div>
        </div>
    <div>

</div>

<script>
    function printInvoice() {
        window.print();
    }
</script>

</body>
</html>