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
    <script src="includes/js/jquery-3.7.1.min.js"></script>
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

    <title>Admin - List of Unlisted People</title>
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

<?php include 'includes/nav/admin_nav.php'; ?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <div class="container mt-4">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Reports for Unlisted People</h4>
              </div>
              <div class="card-body">
                <table id="unlistedTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>UID</th>
                      <th>Full Name</th>
                      <th>Contact</th>
                      <th>Assistance</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Comments</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Data will be loaded via AJAX for server-side processing -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include 'includes/footer.php'; ?>
    </div>
  </main>
</div>
<script>
    $(document).ready(function() {
    $('#unlistedTable').DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "db/server_processing_unlisted.php",
            "type": "POST"
        },
        "columns": [
            { "data": "UID" },
            { "data": "full_name" },
            { "data": "contact" },
            { "data": "assistance" },
            { "data": "date" },
            { "data": "time" },
            { "data": "comments" },
            { "data": "actions", "orderable": false, "searchable": false }
        ],
        "order": [] // Disable initial sorting
    });

    // Handle delete button click
    $('#unlistedTable tbody').on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                url: 'db/delete_report.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    alert(response.message || "Record deleted successfully.");
                    $('#unlistedTable').DataTable().ajax.reload(); // Reload table
                },
                error: function(xhr, status, error) {
                    alert("Failed to delete record. Please try again.");
                }
            });
        }
    });
});

</script>

</body>
</html>
