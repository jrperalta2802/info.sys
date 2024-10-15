<?php include 'db/sessions/admin_session.php'; ?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="includes/css/styles.css" rel="stylesheet" />
    <script src="includes/js/scripts.js"></script>
    <!-- jQuery -->
    <script src="includes/js/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="includes/js/bootstrap.bundle.min.js"></script>

    <!-- AlertifyJS -->
    <script src="includes/js/alertify.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">

    <!-- AlertifyJS CSS -->
    <link rel="stylesheet" href="includes/css/alertify.min.css"/>

    <!-- DataTables CSS -->
    <link rel="stylesheet"  href="includes/css/jquery.dataTables.min.css"/>

    <!-- DataTables JS -->
    <script src="includes/js/jquery.dataTables.min.js"></script>

    <!-- Font Awesome 4.7.0 -->
     <link rel="stylesheet" href="includes/css/font-awesome.min.css"/>

    <!-- Font Awesome 6.7.0 JS -->
    <script src="includes/js/font-awesome.all.js" crossorigin="anonymous"></script>

    <!-- HTML2Canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>



    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">

    <title>Admin - List of Leaders</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .card-body {
            padding: 15px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table .btn {
            margin-right: 5px;
        }
        .dataTables_wrapper {
            padding: 10px 0;
            margin-top: 20px;
        }
        .dataTables_length {
            float: left;
            margin-bottom: 20px;
        }
        .dataTables_filter {
            float: right;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .dataTables_length,
            .dataTables_filter {
                margin-bottom: 10px;
                width: 100%;
                text-align: center;
            }
        }
        .img-thumbnail {
            width: 150px;
            height: auto;
            }
                        
    </style>
</head>
</head>
<body class="sb-nav-fixed">

<?php include 'db/addPerson.php'; ?>
<?php include 'db/editPerson.php'; ?>
<?php include 'db/viewPerson.php'; ?>
<?php include 'includes/nav/admin_nav.php'; ?>

<div id="layoutSidenav_content">
  <main>
          <div class="container-fluid px-4">
         <div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>List of Leaders</h4>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#leaderAddModal">
                        Add <i class="fa fa-plus-square"></i> 
                    </button>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                 <th>UID</th>
                                <th>Barangay</th>
                                <th>Full Name</th>
                                <th>Contact Number</th>
                                <th>Precinct No.</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require 'db/dbcon.php';

                            $query = "SELECT * FROM leaders";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                foreach($query_run as $leader) {
                                    ?>
                                    <tr>
                                         <td><?= $leader['UID'] ?></td>
                                        <td><?= $leader['barangay'] ?></td>
                                        <td><?= $leader['full_name'] ?></td>
                                        <td><?= $leader['contact_number'] ?></td>
                                        <td><?= $leader['precint_no'] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printIdModal" onclick="populateIDModal('leader', '<?= $leader['UID'] ?>')"><i class="fa-solid fa-print"></i></button>
                                                <button type="button" value="<?= $leader['id']; ?>" class="viewLeaderBtn btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Leader">View</button>
                                                <button type="button" value="<?= $leader['id']; ?>" class="editLeaderBtn btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Leader">Edit</button>
                                                <button type="button" value="<?= $leader['id']; ?>" class="deleteLeaderBtn btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Leader">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'includes/footer.php'; ?>
</div>
</main>

<!-- Print ID Modal -->
<div class="modal fade" id="printIdModal" tabindex="-1" aria-labelledby="printIdModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: none; width: 650px;">
    <div class="modal-content" style="border: none; padding: 20px;">
      <div class="modal-header">
        <h5 class="modal-title" id="printIdModalLabel">ID Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3 d-flex justify-content-center">
        <!-- ID Card Structure -->
        <div id="id-card" style="background-color: #ba0017; color: white; padding: 10px; border-radius: 10px; width: 3.375in; height: 2.125in; text-align: left; position: relative;">
          <!-- Logo and Header -->
          <div style="position: absolute; top: 15px; left: 12px; display: flex; align-items: center;">
            <img src="db/uploads/logo.png" alt="Logo" style="width: 0.65in; height: auto; margin-right: 8px;">
            <h4 style="margin: 0; font-size: 0.16in;">Patuloy na Maglilingkod sa Inyo!!</h4>
          </div>

          <!-- Main Content -->
          <div style="display: flex; align-items: center; margin-top: 70px;"> <!-- Moved content down -->
            <!-- Leader's Photo -->
            <div style="flex: 1;">
              <img id="print_leader_photo" src="" alt="Leader's Photo" style="width: 1in; height: 1in; border-radius: 5px; background-color: white;">
            </div>

            <!-- Full Name, Barangay, and UID -->
            <div style="flex: 2; padding-left: 10px;">
              <h3 id="print_full_name" style="margin: 0; font-size: 0.22in;"></h3>
              <p id="print_barangay" style="font-size: 0.18in; margin: 3px 0;"></p>
              <p id="print_uid" style="font-size: 0.15in; margin: 0;"></p>
            </div>

            <!-- QR Code -->
            <div style="flex: 1; text-align: right; position: absolute; bottom: 10px; right: 10px;">
              <img id="print_qr_code" src="" alt="QR Code" style="width: 0.75in; height: 0.75in;">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveAsJPG()">Save as JPG</button>
      </div>
    </div>
  </div>
</div>

<script>
function saveAsJPG() {
    var element = document.getElementById('id-card'); // The ID card element
    var uid = document.getElementById('print_uid').innerText;
    html2canvas(element, {
        scale: 4,
        useCORS: true, // Enable CORS to allow cross-origin images to render
        allowTaint: false,
        backgroundColor: null, // Ensure background color is correctly captured
        width: element.offsetWidth,
        height: element.offsetHeight
    }).then(function(canvas) {
        var imgData = canvas.toDataURL('image/jpeg', 1.0); // Convert canvas to JPEG format
        var link = document.createElement('a');
        link.href = imgData;
        link.download = uid ? uid + '.jpg' : 'ID-Card.jpg'; // Name of the downloaded file
        link.click();
    });
}
</script>



<script src="db/js/dataManagement.js"></script>


<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "order": [], // Default no initial sorting
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable ordering on the last column (Actions)
            ]
        });

        $('[data-bs-toggle="tooltip"]').tooltip();

        window.history.pushState(null, '', window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, '', window.location.href);
        };
    });
</script>

<script>
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    window.history.pushState(null, '', window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, '', window.location.href);
    };
</script>


</body>
</html>
