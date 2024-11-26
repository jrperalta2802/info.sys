<?php
include 'dbcon.php';

// Get parameters from DataTables
$leader_id = $_POST['leader_id'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
$orderColumnIndex = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];

// Map column index to database column name
$columns = array("date", "time", "assistance", "comments");
$orderColumn = $columns[$orderColumnIndex];

// Construct the base SQL query
$sql = "SELECT * FROM reports_help WHERE UID = '$leader_id'";

// Apply search filter if provided
if (!empty($searchValue)) {
    $sql .= " AND (date LIKE '%$searchValue%' OR 
                   time LIKE '%$searchValue%' OR 
                   assistance LIKE '%$searchValue%' OR 
                   comments LIKE '%$searchValue%')";
}

// Get the total number of filtered records
$totalFilteredQuery = mysqli_query($con, $sql);
$totalFiltered = mysqli_num_rows($totalFilteredQuery);

// Apply ordering and limit for pagination
$sql .= " ORDER BY $orderColumn $orderDirection LIMIT $start, $length";

// Fetch the records
$data = array();
$query = mysqli_query($con, $sql);
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

// Get the total number of records without filtering
$totalRecordsQuery = mysqli_query($con, "SELECT COUNT(*) as count FROM reports_help WHERE UID = '$leader_id'");
$totalRecords = mysqli_fetch_assoc($totalRecordsQuery)['count'];

// Prepare the response in DataTables format
$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
);

// Send JSON response
echo json_encode($response);
?>
