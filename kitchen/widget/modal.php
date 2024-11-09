<?php
// Generate a unique product ID
$product_id = substr(uniqid('prod_', true), 0, 13);
$customer_id = substr(uniqid('CS_', true), 0, 13);
include('connection/db_connection.php');
?>

<div class="modal fade" id="modalItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary justify-content-center d-flex">
        <h5 class="modal-title fs-3 text-white" id="exampleModalLabel">Tambah Product</h5>
      </div>
      <div class="modal-body">
        <form action="spice/add_item.php" method="POST" id="tambahItem">
          <div class="input-group input-group-outline my-3">
            <input type="text" name="id_product" class="form-control" value="<?php echo $product_id; ?>" readonly>
          </div>
          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan Nama Barang" name="nama_product" class="form-control" required>
          </div>
          <div class="input-group input-group-outline my-3">
            <select name="type_product" class="form-control" id="roleuser" required>
              <option value="" selected disabled>Pilih Type Product</option>
              <option value="Plastik">Plastik</option>
              <option value="Galon">Galon</option>
              <option value="Kaleng">Kaleng</option>
              <option value="Botol">Botol</option>
              <option value="Pail">Pail</option>
              <option value="Drum">Drum</option>
            </select>
          </div>
          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan Harga" name="price" class="form-control" required>
          </div>
          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan Jumlah Barang" name="amount" class="form-control" required>
          </div>
          <div class="input-group input-group-outline my-3">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>


<div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary justify-content-center d-flex">
        <h5 class="modal-title fs-3 text-white" id="exampleModalLabel">Tambah Customer</h5>
      </div>
      <div class="modal-body">
        <form action="spice/add_customer.php" method="POST" id="tambahCustomer">
          <div class="input-group input-group-outline my-3">
            <input type="text" name="id_toko" class="form-control" value="<?php echo $customer_id; ?>" readonly>
          </div>
          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan Nama Toko" name="nama_toko" class="form-control" required>
          </div>
          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan  Alamat" name="alamat" class="form-control" required>
          </div>

          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan Nomor Hp" name="no_hp" class="form-control" required>
          </div>
          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan Nama Pemilik" name="owner" class="form-control" required>
          </div>
          <div class="input-group input-group-outline my-3">
            <select name="system_pembayaran" class="form-control" id="roleuser" required>
              <option value="" selected disabled>Pilih Type Product</option>
              <option value="Cicil">Cicil</option>
              <option value="Cash">Cash</option>
            </select>
          </div>
          <div class="input-group input-group-outline my-3">
            <input type="text" placeholder="Masukkan Link Lokasi" name="link_alamat" class="form-control" required>
          </div>

          <div class="input-group input-group-outline my-3">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>






<?php

$result = mysqli_query($conn, "SELECT * FROM product");
while ($d = mysqli_fetch_assoc($result)) {
  // Menghitung harga luar kota sebagai 1,2% lebih tinggi dari harga dalam kota
  $hargaLuarKota = round($d['price'] * 1.111);

  echo "
        <div class='modal fade' id='infoModal{$d['id_product']}' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header bg-primary justify-content-center d-flex'>
                        <h1 class='modal-title fs-3 text-white' id='staticBackdropLabel'>Info Produk</h1>
                    </div>
                    <div class='modal-body'>
                        <div class='form-group'>
                            <div class='row'>
                                <div class='col-12 my-2'>
                                    <strong>Kode Produk:</strong> {$d['id_product']}
                                </div>
                                <div class='col-12 my-2'>
                                    <strong>Nama Produk:</strong> {$d['nama_product']}
                                </div>
                                <div class='col-12 my-2'>
                                    <strong>Type Produk:</strong> {$d['type_product']}
                                </div>
                                <div class='col-12 my-2'>
                                    <strong>Harga Kemasan (Dalam Kota):</strong> Rp " . number_format($d['price'], 2, ',', '.') . "
                                </div>
                                <div class='col-12 my-2'>
                                    <strong>Harga Kemasan (Luar Kota):</strong> Rp " . number_format($hargaLuarKota, 2, ',', '.') . "
                                </div>
                                <div class='col-12 my-2'>
                                    <strong>Isi Kemasan:</strong> {$d['amount']}
                                </div>
                                <div class='col-12 my-2'>
                                    <strong>Total Harga:</strong> Rp " . number_format($d['total'], 2, ',', '.') . "
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div>";
}
?>