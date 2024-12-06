<?php include 'db/sessions/admin_session.php'; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="includes/css/styles.css" rel="stylesheet" />
    <script src="includes/js/scripts.js"></script>
    <script src="includes/js/jquery-3.7.1.min.js"></script>
    <script src="includes/js/bootstrap.bundle.min.js"></script>
    <script src="includes/js/alertify.min.js"></script>
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/css/alertify.min.css"/>
    <link rel="stylesheet"  href="includes/css/jquery.dataTables.min.css"/>
    <script src="includes/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="includes/css/font-awesome.min.css"/>
    <script src="includes/js/font-awesome.all.js" crossorigin="anonymous"></script>
    <title>Admin - List of All Reports</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
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
                <h4>All Reports</h4>
              </div>
              <div class="card-body">
                <table id="reportsTable" class="table table-bordered table-striped">
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
                    <!-- Data loaded via AJAX -->
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
        $('#reportsTable').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": "db/server_processing_reports_asst.php",
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

        // Delete functionality
        $('#reportsTable tbody').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            if (confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: 'db/delete_report_asst.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        alert(response.message || "Record deleted successfully.");
                        $('#reportsTable').DataTable().ajax.reload();
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
