<?php
session_start();

include('connection/db_connection.php');

// Query untuk mendapatkan data layanan
$sql = "SELECT id_product, nama_product, price, price_luarkota FROM product";
$result = $conn->query($sql);

$sqlCustomer = "SELECT id_toko, nama_toko , description, area_lokasi FROM customer";
$resultCustomer = $conn->query($sqlCustomer); // Execute the query


$sql = "SELECT id_product, nama_product, price, price_luarkota FROM product";
$result = $conn->query($sql);

$options = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $options[] = [
      'id' => $row["id_product"],
      'name' => $row["nama_product"],
      'price' => $row["price"],
      'price_luarkota' => $row["price_luarkota"]
    ];
  }
}
$options_json = json_encode($options);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../resources/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../resources/img/favicon.png">
  <title>
    CREATE FAKTUR BARANG
  </title>
  <?php include 'assets/header.php'; ?>
</head>
<style>
  /* Untuk Chrome, Safari, Edge, Opera */
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Untuk Firefox */
  input[type="number"] {
    -moz-appearance: textfield;
  }
</style>

<body class="g-sidenav-show  bg-gray-200">
  <?php include 'widget/navbar.php'; ?>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create Faktur</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Create Faktur</h6>
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
            <li class="nav-item px-3 d-flex align-items-center justify-content-center ">
              <div class="form-check form-switch align-items-center">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                <label class="form-check-label" for="flexSwitchCheckDefault">Luar Kota </label>
              </div>
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
      <div class="card">
        <div class="justify-content-center d-flex ">
          <div class="row">
            <p class="my-2 text-bold fs-3 ">Pembuatan Faktur</p>
          </div>
        </div>
        <div class="container-fluid">
          <div><a href="faktur_history.php" class="btn btn-primary d-flex justify-content-center align-item-center">History Faktur</a></div>
          <form action="spice/add_faktur.php" method="post">
            <div class="px-5">
              <div class="input-group input-group-outline my-4">
                <div class="form-check form-switch align-items-center">
                  <input class="form-check-input" type="checkbox" role="switch" id="autoIdSwitch">
                  <label class="form-check-label" for="autoIdSwitch">Generate ID Faktur Otomatis</label>
                </div>
              </div>
              <div class="input-group input-group-outline my-4" id="idFakturContainer">
                <input type="text" name="id_faktur" id="id_faktur" class="form-control" placeholder="ID Faktur" required>
              </div>
              <div class="input-group input-group-outline my-4  ">

                <select class="form-control" name="id_toko" id="id_toko">
                  <option value="" selected disabled>Pilih Toko</option>
                  <?php
                  // Check if there are results from the query
                  if ($resultCustomer->num_rows > 0) {
                    while ($row = $resultCustomer->fetch_assoc()) {
                      echo "<option value='" . $row['id_toko'] . "' data-description='" . $row['description'] . "' data-area='" . $row['area_lokasi'] . "'>" . $row['nama_toko'] . "</option>";;
                    }
                  } else {
                    echo "<option value='' disabled>No stores available</option>";
                  }
                  ?>
                </select>
              </div>
              <div id="selectContainer" class="my-4">
                <div class="input-group input-group-outline my-4">
                  <select class="form-control" name="jenis_barang[]">
                    <option value="" selected disabled>Pilih Barang</option>
                    <?php foreach ($options as $option): ?>
                      <option value="<?= $option['id'] ?>" data-price="<?= $option['price'] ?>" data-price_luarkota="<?= $option['price_luarkota'] ?>">
                        <?= $option['name'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <input type="number" class="form-control ms-2" name="jumlah[]" min="1" value="1" required>
                  <input type="hidden" name="harga[]" class="harga-input" value="">
                </div>
              </div>

              <div class="input-group input-group-outline my-4  ">
                <input type="date" name="tanggal_customer" id="tanggal_customer" class="form-control" required>
              </div>
              <div class="input-group input-group-outline my-4  ">
                <textarea placeholder="Catatan" name="note" id="notepad" class="form-control" required></textarea>
              </div>
              <div class="input-group input-group-outline my-4  ">
                <input type="number" name="discount" id="discount" class="form-control" placeholder="Discount" min="0">
              </div>
              <div>
                <p class="text-end"><span id="totalHarga">0</span></p>

              </div>
              <div class="d-flex justify-content-evenly  ">
                <button type="button" class="btn btn-primary " id="addSelectBtn">Tambah Barang</button>
                <button type="submit" class="btn bg-gradient-primary btn-link text-end text-center">Buat Faktur</button>
              </div>
            </div>
            <input type="hidden" name="total_harga" id="totalHargaHidden">
            <input type="hidden" name="luar_kota" id="luarKotaHidden" value="0">
          </form>
        </div>

      </div>
    </div>
  </main>


  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const container = document.getElementById('selectContainer');
      const totalHargaElem = document.getElementById('totalHarga');
      const totalHargaHidden = document.getElementById('totalHargaHidden');
      const switchElement = document.getElementById('flexSwitchCheckDefault');
      const tokoSelect = document.getElementById('id_toko');
      const noteTextarea = document.getElementById('notepad');
      const discountInput = document.getElementById('discount');
      const autoIdSwitch = document.getElementById('autoIdSwitch');
      const idFakturInput = document.getElementById('id_faktur');
      const luarKotaHidden = document.getElementById('luarKotaHidden');

      function generateFakturId() {
        const part1 = String(Math.floor(Math.random() * 100)).padStart(2, '0');
        const part2 = String(Math.floor(Math.random() * 100)).padStart(2, '0');
        const part3 = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
        return `IVC/${part1}/${part2}/${part3}`;
      }

      function updateTotal() {
        let total = 0;
        const useLuarKotaPrice = switchElement.checked;

        container.querySelectorAll('.input-group').forEach(group => {
          const select = group.querySelector('select');
          const quantityInput = group.querySelector('input[name="jumlah[]"]');
          const hiddenPriceInput = group.querySelector('input[name="harga[]"]');

          const selectedOption = select.options[select.selectedIndex];
          const productName = selectedOption.text;
          let price = parseFloat(useLuarKotaPrice ? selectedOption.getAttribute('data-price_luarkota') : selectedOption.getAttribute('data-price')) || 0;
          const quantity = parseInt(quantityInput.value) || 0;

          if (productName === 'Cat Juntax G') {
            if (quantity >= 1 && quantity <= 55) {
              price = parseFloat(useLuarKotaPrice ? 64975 : 56500);
            } else if (quantity >= 56 && quantity <= 105) {
              price = parseFloat(useLuarKotaPrice ? 59975 : 52500);
            } else if (quantity >= 106) {
              price = parseFloat(useLuarKotaPrice ? 56500 : 49500);
            }
          }
          if (productName === 'Cat Juntax G SPW') {
            if (quantity >= 56 && quantity <= 105) {
              price = parseFloat(useLuarKotaPrice ? 59975 : 52500);
            } else if (quantity >= 106) {
              price = parseFloat(useLuarKotaPrice ? 56500 : 49500);
            }
            
          }


          hiddenPriceInput.value = price;
          total += price * quantity;
        });

        const discount = parseFloat(discountInput.value) || 0;
        total -= discount;

        totalHargaElem.textContent = `Total: Rp ${total.toLocaleString('id-ID')}`;
        totalHargaHidden.value = total;
      }

      tokoSelect.addEventListener('change', function() {
        const selectedOption = tokoSelect.options[tokoSelect.selectedIndex];
        const description = selectedOption.getAttribute('data-description');
        noteTextarea.value = description ? description : '';
        updateTotal();
      });

      container.addEventListener('change', updateTotal);
      container.addEventListener('input', updateTotal);
      switchElement.addEventListener('change', function() {
        luarKotaHidden.value = switchElement.checked ? '1' : '0';
        updateTotal();
      });
      discountInput.addEventListener('input', updateTotal);

      document.getElementById('addSelectBtn').addEventListener('click', function() {
        var newDiv = document.createElement('div');
        newDiv.className = 'input-group input-group-outline my-4';

        var newSelect = document.createElement('select');
        newSelect.className = 'form-control';
        newSelect.name = 'jenis_barang[]';
        newSelect.required = true;

        var defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = 'Pilih Jenis Layanan';
        defaultOption.disabled = true;
        defaultOption.selected = true;
        newSelect.appendChild(defaultOption);

        var options = <?= $options_json ?>;
        options.forEach(function(option) {
          var opt = document.createElement('option');
          opt.value = option.id;
          opt.setAttribute('data-price', option.price);
          opt.setAttribute('data-price_luarkota', option.price_luarkota);
          opt.text = option.name;
          newSelect.appendChild(opt);
        });

        var quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.className = 'form-control ms-2';
        quantityInput.name = 'jumlah[]';
        quantityInput.min = '1';
        quantityInput.value = '1';
        quantityInput.required = true;

        var hiddenPriceInput = document.createElement('input');
        hiddenPriceInput.type = 'hidden';
        hiddenPriceInput.name = 'harga[]';
        hiddenPriceInput.className = 'harga-input';
        hiddenPriceInput.value = '';

        var deleteButton = document.createElement('button');
        deleteButton.className = 'btn btn-danger m-0 p-2';
        deleteButton.textContent = 'Hapus';
        deleteButton.type = 'button';
        deleteButton.addEventListener('click', function() {
          container.removeChild(newDiv);
          updateTotal();
        });

        newDiv.appendChild(newSelect);
        newDiv.appendChild(quantityInput);
        newDiv.appendChild(hiddenPriceInput);
        newDiv.appendChild(deleteButton);
        container.appendChild(newDiv);
      });

      autoIdSwitch.addEventListener('change', function() {
        if (autoIdSwitch.checked) {
          idFakturInput.value = generateFakturId();
          idFakturInput.readOnly = true;
        } else {
          idFakturInput.value = '';
          idFakturInput.readOnly = false;
        }
      });
    });
  </script>



  <?php include 'assets/footer.php'; ?>

</body>

</html>
