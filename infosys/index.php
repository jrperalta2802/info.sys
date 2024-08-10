<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Information System</title>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
</head>
<body>

<?php include 'db/addPerson.php'; ?>
<?php include 'db/editPerson.php'; ?>
<?php include 'db/viewPerson.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>List of Leaders
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#leaderAddModal">
                            Add
                        </button>
                    </h4>
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
                                            <button type="button" value="<?= $leader['id']; ?>" class="viewLeaderBtn btn btn-info btn-sm">View</button>
                                            <button type="button" value="<?= $leader['id']; ?>" class="editLeaderBtn btn btn-success btn-sm">Edit</button>
                                            <button type="button" value="<?= $leader['id']; ?>" class="deleteLeaderBtn btn btn-danger btn-sm">Delete</button>
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
</body>
</html>
