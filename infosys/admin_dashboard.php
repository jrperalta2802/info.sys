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
    <link rel="stylesheet" type="text/css" href="includes/css/jquery.dataTables.min.css"/>

    <!-- DataTables JS -->
    <script type="text/javascript" src="includes/js/jquery.dataTables.min.js"></script>

    <!-- Font Awesome 4.7.0 -->
     <link rel="stylesheet" href="includes/css/font-awesome.min.css"/>

     <!-- Font Awesome 6.7.0 JS -->
     <script src="includes/js/font-awesome.all.js" crossorigin="anonymous"></script>

    <!-- Charts JS -->
    <script src="includes/js/chart.js"></script>
    
    <title>Admin Dashboard</title>
   
</head>
<body class="sb-nav-fixed">
  <?php include 'includes/nav/admin_nav.php'; ?>
<div id="layoutSidenav_content">
        <main>
          <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            
            <!--For displaying dashboard-->
            <?php include 'db/fetch_counts.php'?>
           
            <div class="row">
                <!--Leader Card-->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                      <div class="card-body">Leaders: <?php echo $leaderCount?></div>
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
                  
                <!--Member Card -->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                      <div class="card-body">Members: <?php echo $memberCount?></div>
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
                  
                <!--Users Card-->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                      <div class="card-body">Users: <?php echo $usersCount?></div>
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
                
                <!--Reports Card-->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                      <div class="card-body">Total Votes: <?php echo $reportsCount?></div>
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
                <!--Line Chart-->
                <!--<div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Line Chart Example
                  </div>
                  <div class="card-body">
                    <canvas id="myLineChart" width="100%" height="40"></canvas>
                  </div>
                </div>
              </div>-->
                
                <!--Leader bar chart-->
                <div class="col-xl-6">
                        <div class="card mb-4">
                          <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                                Number of leaders per barangay
                              </div>
                          <div class="card-body">
                            <canvas id="chart_leader" width="100%" height="40"></canvas>
                          </div>
                        </div>
                    </div>
            
                <!--Barangay bar chart-->
                <div class="col-xl-6">
                <div class="card mb-4">
                  <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                        Number of votes per barangay
                      </div>
                  <div class="card-body">
                    <canvas id="chart_barangay" width="100%" height="40"></canvas>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </main>
        <?php include 'includes/footer.php'; ?>
      </div>
</div>
    <!-- Charts -->
    <?php include 'db/fetch_chart.php'; ?>
    <?php include 'includes/chart_new.php'; ?>
     

</body>
</html>


