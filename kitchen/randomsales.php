<?php
include('connection/db_connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Sales</title>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Select Invoice</h2>
        <form id="randomSalesForm" method="POST" action="process_random_sales.php">
            <div class="form-group">
                <label for="id_faktur">Invoice ID</label>
                <select class="js-example-basic-single form-control" id="id_faktur" name="id_faktur" required>
                    <option value="">Select Invoice</option>
                    <?php
                    // Fetch invoice IDs from the database
                    $sql = "SELECT id_faktur FROM faktur WHERE id_faktur NOT IN (SELECT id_faktur FROM sales WHERE sisa_tagihan = 0)";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id_faktur'] . "'>" . $row['id_faktur'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No Invoice Available</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2 on the select element
            $('.js-example-basic-single').select2({
                placeholder: 'Select Invoice',
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>
</body>

</html>
