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
                            <button type="button" value="${data}" class="viewLeaderBtn btn btn-info btn-sm" data-bs-toggle="tooltip" title="View Leader">View</button>
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

        // Delegated event handling for dynamically loaded buttons
        $('#myTable tbody').on('click', '.viewLeaderBtn', function() {
            var leaderId = $(this).val(); // Get leader_id from button value
            viewLeaderDetails(leaderId); // Call view function with leader_id
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    // Function to handle viewing leader details
    function viewLeaderDetails(leaderId) {
        $.ajax({
            type: "GET",
            url: "db/personProcess.php?leader_id=" + leaderId,
            success: function(response) {
                var res = typeof response === "object" ? response : jQuery.parseJSON(response);
                if (res.status == 404) {
                    alert(res.message);
                } else if (res.status == 200) {
                    $("#view_leader_photo").attr("src", "/info.sys/infosys/db/uploads/leaders/" + res.data.leader.leaders_photo);
                    $("#view_barangay").text(res.data.leader.barangay);
                    $("#view_full_name").text(res.data.leader.full_name);
                    $("#view_precint_no").text(res.data.leader.precint_no);
                    $("#view_contact_number").text(res.data.leader.contact_number);
                    $("#view_address").text(res.data.leader.address);
                    $("#view_birthdate").text(res.data.leader.birthdate);
                    $("#view_age").text(res.data.leader.age);
                    $("#view_civil_status").text(res.data.leader.civil_status);
                    $("#view_sex").text(res.data.leader.sex);
                    $("#view_uid").text(res.data.leader.UID);

                    // Populate members table if needed
                    var membersTableBody = $("#membersTableBody");
                    membersTableBody.empty();

                    res.data.members.forEach(function(member) {
                        var row = `
                            <tr>
                                <td><img src="/info.sys/infosys/db/uploads/members/${member.member_photo}" alt="" class="img-fluid rounded" style="max-width: 50px;"></td>
                                <td>${member.UIDM}</td>
                                <td>${member.member_name}</td>
                                <td>${member.member_birthdate}</td>
                                <td>${member.member_contact}</td>
                                <td>${member.member_precinct}</td>
                            </tr>`;
                        membersTableBody.append(row);
                    });

                    // Show the modal
                    $("#leaderViewModal").modal("show");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching leader details:", textStatus, errorThrown);
            }
        });
    }
</script>


</body>
</html>
