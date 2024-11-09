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
              <option value="Cup">Cup</option>
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
          <div class="row">
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <input type="text" name="id_toko" class="form-control" value="<?php echo $customer_id; ?>" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <input type="text" placeholder="Masukkan Nama Toko" name="nama_toko" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <input type="text" placeholder="Masukkan Nama Sales" name="nama_sales" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <input type="text" placeholder="Masukkan Alamat" name="alamat" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <select name="area_lokasi" class="form-control" id="roleuser" required>
                  <option value="" selected disabled>Pilih Lokasi Pengiriman</option>
                  <option value="Luar Kota">Luar Kota</option>
                  <option value="Dalam Kota">Dalam Kota</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <input type="text" placeholder="Masukkan Nomor Hp" name="no_hp" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <input type="text" placeholder="Masukkan Nama Pemilik" name="owner" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-outline my-3">
                <select name="system_pembayaran" class="form-control" id="roleuser" required>
                  <option value="" selected disabled>Pilih Type Pembayaran</option>
                  <option value="Cicil">Cicil</option>
                  <option value="Cash">Cash</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="input-group input-group-outline my-3">
                <input type="text" placeholder="Masukkan Link Lokasi" name="link_alamat" class="form-control" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="input-group input-group-outline my-3">
                <textarea type="text" placeholder="Masukkan Deskripsi" name="description" class="form-control" required></textarea>
              </div>
            </div>
            <div class="col-md-12">
              <div class="input-group input-group-outline my-3">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </div>
        </form>
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
                        <button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editModal{$d['id_product']}'>Edit</button>
                        <button type='button' class='btn btn-danger' onclick='deleteProduct(\"{$d['id_product']}\")'>Delete</button>
                    </div>
                </div>
            </div>
        </div>";

  // Edit Modal
  echo "
        <div class='modal fade' id='editModal{$d['id_product']}' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header bg-warning justify-content-center d-flex'>
                        <h5 class='modal-title fs-3 text-white' id='editModalLabel'>Edit Produk</h5>
                    </div>
                    <div class='modal-body'>
                        <form action='spice/edit_item.php' method='POST'>
                            <input type='hidden' name='id_product' value='{$d['id_product']}'>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='nama_product' class='form-control' value='{$d['nama_product']}' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <select name='type_product' class='form-control' required>
                                    <option value='Plastik' " . ($d['type_product'] == 'Plastik' ? 'selected' : '') . ">Plastik</option>
                                    <option value='Galon' " . ($d['type_product'] == 'Galon' ? 'selected' : '') . ">Galon</option>
                                    <option value='Kaleng' " . ($d['type_product'] == 'Kaleng' ? 'selected' : '') . ">Kaleng</option>
                                    <option value='Botol' " . ($d['type_product'] == 'Botol' ? 'selected' : '') . ">Botol</option>
                                    <option value='Pail' " . ($d['type_product'] == 'Pail' ? 'selected' : '') . ">Pail</option>
                                    <option value='Drum' " . ($d['type_product'] == 'Drum' ? 'selected' : '') . ">Drum</option>
                                </select>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='price' class='form-control' value='{$d['price']}' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='price_luarkota' class='form-control' value='{$d['price_luarkota']}' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <input type='text' name='amount' class='form-control' value='{$d['amount']}' required>
                            </div>
                            <div class='input-group input-group-outline my-3'>
                                <button type='submit' class='btn btn-warning'>Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>";
}
?>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
  <script>
    alert('Product deleted successfully.');
  </script>
<?php endif; ?>

<script>
  function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
      window.location.href = 'spice/delete_item.php?id=' + id;
    }
  }
</script>