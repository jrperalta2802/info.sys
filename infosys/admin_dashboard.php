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

     
    <title>Admin Dashboard</title>
   
</head>
<body class="sb-nav-fixed">
  <?php include 'includes/nav.php'; ?>
<div id="layoutSidenav_content">
        <main>
          <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
           
            <div class="row">
              <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                  <div class="card-body">Primary Card</div>
                  <div
                    class="card-footer d-flex align-items-center justify-content-between"
                  >
                    <a class="small text-white stretched-link" href="#"
                      >View Details</a
                    >
                    <div class="small text-white">
                      <i class="fas fa-angle-right"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                  <div class="card-body">Warning Card</div>
                  <div
                    class="card-footer d-flex align-items-center justify-content-between"
                  >
                    <a class="small text-white stretched-link" href="#"
                      >View Details</a
                    >
                    <div class="small text-white">
                      <i class="fas fa-angle-right"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                  <div class="card-body">Success Card</div>
                  <div
                    class="card-footer d-flex align-items-center justify-content-between"
                  >
                    <a class="small text-white stretched-link" href="#"
                      >View Details</a
                    >
                    <div class="small text-white">
                      <i class="fas fa-angle-right"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                  <div class="card-body">Danger Card</div>
                  <div
                    class="card-footer d-flex align-items-center justify-content-between"
                  >
                    <a class="small text-white stretched-link" href="#"
                      >View Details</a
                    >
                    <div class="small text-white">
                      <i class="fas fa-angle-right"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">

              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Line Chart Example
                  </div>
                  <div class="card-body">
                    <canvas id="myLineChart" width="100%" height="40"></canvas>
                  </div>
                </div>
              </div>
 
              <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Bar Chart Example
                  </div>
                  <div class="card-body">
                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </main>
        <?php include 'includes/footer.php'; ?>
      </div>
</div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Charts -->
         <?php include 'includes/charts.php'; ?>
        

        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
     
</body>
</html>


