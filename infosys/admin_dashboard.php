<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!doctype html>
<html lang="en">
<head>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <title>Admin Dashboard - Information System</title>
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
            padding: 10px; /* Adjust the padding as needed */
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table .btn {
            margin-right: 5px;
        }
        .dataTables_wrapper {
    padding: 0px 0; /* Adjust padding as necessary */
    margin-top: 20px; /* Add some margin to separate controls from the table */
}

.dataTables_length {
    float: left; /* Align "Show entries" to the left */
    margin-bottom: 20px;
}

.dataTables_filter {
    float: right; /* Align the search bar to the right */
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .dataTables_length,
    .dataTables_filter {
        margin-bottom: 10px; /* Add space below controls for smaller screens */
        width: 100%; /* Make controls full-width */
        text-align: center; /* Center-align the controls */
    }
}
    </style>
</head>
</head>
<body>

<?php include 'db/addPerson.php'; ?>
<?php include 'db/editPerson.php'; ?>
<?php include 'db/viewPerson.php'; ?>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Welcome, <?php echo $_SESSION['username']; ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>List of Leaders</h4>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#leaderAddModal">
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
                                <th>Birthdate</th>
                                <th>Age</th>
                                <th>Civil Status</th>
                                <th>Sex</th>
                                <th>Address</th>
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
                                        <td><?= $leader['birthdate'] ?></td>
                                        <td><?= $leader['age'] ?></td>
                                        <td><?= $leader['civil_status'] ?></td>
                                        <td><?= $leader['sex'] ?></td>
                                        <td><?= $leader['address'] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
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
