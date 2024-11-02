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
    <!-- HTML2Canvas-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> 
    <!-- JSPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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
                      <th></th> <!-- Column for the expandable button -->
                      <th>UID</th>
                      <th>Barangay</th>
                      <th>Full Name</th>
                      <th>Contact Number</th>
                      <th>Precinct No.</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Rows will be loaded via AJAX for server-side processing -->
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


<?php include 'db/leaderPrint_Modal.php'; ?>
<?php include 'db/memberPrint_modal.php'; ?>
<script src="db/js/dataManagement.js"></script>
<script>
  $(document).ready(function() {
    // Initialize DataTable with server-side processing
    var table = $('#myTable').DataTable({
      "serverSide": true,
      "processing": true,
      "ajax": {
        "url": "db/server_processing.php", // Server-side script to handle data fetching
        "type": "POST"
      },
      "columns": [
        { "data": null, "defaultContent": "<button class='btn btn-sm btn-primary expandBtn'>+</button>" },
        { "data": "UID" },
        { "data": "barangay" },
        { "data": "full_name" },
        { "data": "contact_number" },
        { "data": "precint_no" },
        { "data": "UID", "render": function(data, type, row) {
            return `
             <div class="btn-group" role="group">
                <button class="btn btn-primary btn-sm" onclick="populateLeaderIDModal('${data}')" data-bs-toggle="tooltip" title="Print Leader">Print</button>
                <button type="button" value="${row.id}" class="viewLeaderBtn btn btn-info btn-sm" data-bs-toggle="tooltip" title="View Leader">View</button>
                <button type="button" value="${row.id}" class="editLeaderBtn btn btn-success btn-sm" data-bs-toggle="tooltip" title="Edit Leader">Edit</button>
                <button type="button" value="${row.id}" class="deleteLeaderBtn btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete Leader">Delete</button>
              </div>
            `;
          }
        }
      ]
    });

    // Expand button click event to show members for a leader
    $('#myTable tbody').on('click', 'button.expandBtn', function () {
      var tr = $(this).closest('tr');
      var row = table.row(tr);

      if (row.child.isShown()) {
        row.child.hide();
        $(this).text('+');
      } else {
        var leaderId = row.data().id;
        $.ajax({
          url: 'db/fetch_members.php?leader_id=' + leaderId,
          method: 'GET',
          success: function(response) {
            var result = JSON.parse(response);
            if (result.status === 200) {
              row.child(format(result.data.members)).show();
              $(this).text('-');
              $('[data-bs-toggle="tooltip"]').tooltip();
            } else {
              row.child('<div>Error: ' + result.message + '</div>').show();
            }
          }.bind(this),
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching members:', textStatus, errorThrown);
            row.child('<div>Error fetching members</div>').show();
          }
        });
      }
    });
    
// Function to format members for display in expanded rows
function format(members) {
    var html = '<h5>Members</h5><table class="table table-bordered table-striped"><thead><tr><th>UIDM</th><th>Full Name</th><th>Contact Number</th><th>Precinct</th><th>Actions</th></tr></thead><tbody>';
    $.each(members, function(index, member) {
        html += '<tr>' +
                '<td>' + member.UIDM + '</td>' +
                '<td>' + member.member_name + '</td>' +
                '<td>' + member.member_contact + '</td>' +
                '<td>' + member.member_precinct + '</td>' +
                '<td>' +
                    '<div class="btn-group" role="group">' +
                         `<button class="btn btn-primary btn-sm print-member-btn" onclick="populateMemberIDModal('${member.UIDM}')" data-bs-toggle="tooltip" title="Print Member">Print</button>` +
                    '</div>' +
                '</td>' +
                '</tr>';
    });
    html += '</tbody></table>';
    return html;
}
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
