<?php include 'db/sessions/user_session.php'; ?>

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
    <link rel="stylesheet" type="text/css" href="includes/css/jquery.dataTables.min.css"/>

    <!-- DataTables JS -->
    <script type="text/javascript" src="includes/js/jquery.dataTables.min.js"></script>

    <!-- Font Awesome 4.7.0 -->
     <link rel="stylesheet" href="includes/css/font-awesome.min.css"/>

     <!-- Font Awesome 6.7.0 JS -->
     <script src="includes/js/font-awesome.all.js" crossorigin="anonymous"></script>

    <title>User Dashboard - Information System</title>
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
        
    </style>
</head>
<body class="sb-nav-fixed">

<?php include 'db/addPerson.php'; ?>
<?php include 'db/editPerson.php'; ?>
<?php include 'db/viewPerson.php'; ?>
<?php include 'includes/nav/user_nav.php'; ?>

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
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
                <div class="card-body">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
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
                                        <td><?= $leader['barangay'] ?></td>
                                        <td><?= $leader['full_name'] ?></td>
                                        <td><?= $leader['contact_number'] ?></td>
                                        <td><?= $leader['precint_no'] ?></td>
                                        <td>
                                            <button type="button" value="<?= $leader['id']; ?>" class="viewLeaderBtn btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="View Leader">View</button>
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

<?php include 'db/crud_script.php'; ?>
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
