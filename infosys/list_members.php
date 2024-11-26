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

    <title>Admin - List of Members</title>
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

<?php include 'db/viewPerson_members.php'; ?>
<?php include 'includes/nav/admin_nav.php'; ?>

<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <div class="container mt-4">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4>List of Members</h4>
              </div>
              <div class="card-body">
                <table id="myTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>UIDM</th>
                      <th>Barangay</th>
                      <th>Leader's Name</th>
                      <th>Member's Name</th>
                      <th>Contact Number</th>
                      <th>Precinct No.</th>
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
        var table = $('#myTable').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": "db/server_processing_members.php",
                "type": "POST"
            },
            "columns": [
                { "data": "UIDM" },
                { "data": "barangay" },
                { "data": "full_name" },
                { "data": "member_name" },
                { "data": "member_contact" },
                { "data": "member_precinct" },
                { "data": "leader_id", "render": function(data, type, row) {
                        return `
                          <div class="btn-group" role="group">
                            <button class="btn btn-info btn-sm view-member-btn" data-bs-toggle="tooltip" title="View Member">View</button>
                          </div>
                        `;
                    }
                }
            ],
            "order": [], // Default no initial sorting
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable ordering on the last column (Actions)
            ]
        });

 $('#myTable tbody').on('click', '.view-member-btn', function () {
    // Extract UIDM from the first cell in the same row as the clicked button
    var memberUIDM = $(this).closest('tr').find('td:first').text().trim();

    console.log("Button clicked, UIDM value:", memberUIDM);

    // Check if UIDM is valid
    if (!memberUIDM) {
        console.error("UIDM is missing or undefined. Ensure the table cell contains the UIDM.");
        return; // Stop further execution if UIDM is missing
    }

    // Call the function to fetch and display member details
    viewMemberDetails(memberUIDM);
});

// Initialize Bootstrap tooltips
$('[data-bs-toggle="tooltip"]').tooltip();

// Function to fetch and display member details in the modal
function viewMemberDetails(memberUIDM) {
    $.ajax({
        type: "GET",
        url: "db/personProcess.php?UIDM=" + memberUIDM, // Fetch member details
        success: function (response) {
            console.log("Response:", response);
            var res = typeof response === "object" ? response : jQuery.parseJSON(response);

            if (res.status === 404) {
                alert(res.message);
            } else if (res.status === 200) {
                console.log("Populating modal fields...");

                // Populate modal fields
                $("#members_view_uid").text(res.data.member.UIDM || "N/A");
                $("#members_view_full_name").text(res.data.member.full_name || "N/A");
                $("#members_view_contact_number").text(res.data.member.contact || "N/A");
                $("#members_view_birthdate").text(res.data.member.birthdate || "N/A");
                $("#members_view_precint_no").text(res.data.member.precinct || "N/A");

                // Populate Reports for Assistance table
                var reportsTableBody = $("#members_reportsTableBody");
                reportsTableBody.empty(); // Clear existing rows
                if (res.data.reports_help.length === 0) {
                    reportsTableBody.append(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">No Reports Available</td>
                        </tr>
                    `);
                } else {
                    res.data.reports_help.forEach(function (report) {
                        reportsTableBody.append(`
                            <tr>
                                <td>${report.date}</td>
                                <td>${report.time}</td>
                                <td>${report.assistance}</td>
                                <td>${report.comments}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm delete-report-btn" data-report-id="${report.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                }

                // Populate Reports for Vote table
                var votesTableBody = $("#members_votesTableBody");
                votesTableBody.empty(); // Clear existing rows
                if (res.data.votes.length === 0) {
                    votesTableBody.append(`
                        <tr>
                            <td colspan="5" class="text-center text-muted">No Reports Available</td>
                        </tr>
                    `);
                } else {
                    res.data.votes.forEach(function (vote) {
                        votesTableBody.append(`
                            <tr>
                                <td>${vote.date}</td>
                                <td>${vote.time_in}</td>
                                <td>${vote.time_out}</td>
                                <td>${vote.barangay}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-report-id="${vote.id}">View</button>
                                </td>
                            </tr>
                        `);
                    });
                }

                // Display the modal
                $("#memberViewModal").modal("show");
            }
        },
        error: function (xhr, status, error) {
            console.error("XHR Error:", xhr);
            alert("Failed to fetch member details. Please try again.");
        },
    });
}
    });
</script>


</body>
</html>
