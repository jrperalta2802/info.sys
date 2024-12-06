<?php
require 'dbcon.php';

// Get parameters sent from DataTables
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$orderDirection = isset($_POST['order'][0]['dir']) && in_array($_POST['order'][0]['dir'], ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'asc';

// Column mapping
$columns = [
    0 => 'report_id',
    1 => 'UID',
    2 => 'date',
    3 => 'time_in',
    4 => 'time_out',
    5 => 'barangay',
    6 => 'goods'
];

$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'report_id';

// Total records without filtering
$totalRecordsQuery = "SELECT COUNT(*) as count FROM reports";
$totalRecordsResult = mysqli_query($con, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['count'];

// Filtering
$searchQuery = '';
if (!empty($searchValue)) {
    $searchQuery = "WHERE (UID LIKE '%$searchValue%' OR barangay LIKE '%$searchValue%' OR date LIKE '%$searchValue%')";
}

// Total records with filtering
$totalFilteredRecordsQuery = "SELECT COUNT(*) as count FROM reports $searchQuery";
$totalFilteredRecordsResult = mysqli_query($con, $totalFilteredRecordsQuery);
$totalFilteredRecords = mysqli_fetch_assoc($totalFilteredRecordsResult)['count'];

// Fetch data with pagination, filtering, and sorting
$query = "SELECT report_id, UID, date, time_in, time_out, barangay, goods 
          FROM reports 
          $searchQuery 
          ORDER BY $orderColumn $orderDirection 
          LIMIT $start, $length";

$dataResult = mysqli_query($con, $query);

$data = [];
while ($row = mysqli_fetch_assoc($dataResult)) {
    $data[] = [
        'report_id' => $row['report_id'],
        'UID' => $row['UID'],
        'date' => $row['date'],
        'time_in' => $row['time_in'],
        'time_out' => $row['time_out'],
        'barangay' => $row['barangay'],
        'goods' => $row['goods'] == 1 ? 'Yes' : 'No', // Convert goods to human-readable format
        'actions' => '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row['report_id'] . '">Delete</button>'
    ];
}

// JSON response
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
]);

exit;
