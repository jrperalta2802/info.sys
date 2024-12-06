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
    0 => 'UID', 
    1 => 'full_name',
    2 => 'contact',
    3 => 'assistance',
    4 => 'date',
    5 => 'time',
    6 => 'comments'
];

$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'UID';

// Total records without filtering
$totalRecordsQuery = "SELECT COUNT(*) as count FROM reports_help";
$totalRecordsResult = mysqli_query($con, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['count'];

// Filtering
$searchQuery = '';
if (!empty($searchValue)) {
    $searchQuery = "WHERE (UID LIKE '%$searchValue%' OR full_name LIKE '%$searchValue%' OR contact LIKE '%$searchValue%' OR assistance LIKE '%$searchValue%')";
}

// Total records with filtering
$totalFilteredRecordsQuery = "SELECT COUNT(*) as count FROM reports_help $searchQuery";
$totalFilteredRecordsResult = mysqli_query($con, $totalFilteredRecordsQuery);
$totalFilteredRecords = mysqli_fetch_assoc($totalFilteredRecordsResult)['count'];

// Fetch data with pagination, filtering, and sorting
$query = "SELECT id, UID, full_name, contact, assistance, date, time, comments 
          FROM reports_help 
          $searchQuery 
          ORDER BY $orderColumn $orderDirection 
          LIMIT $start, $length";

$dataResult = mysqli_query($con, $query);

$data = [];
while ($row = mysqli_fetch_assoc($dataResult)) {
    $data[] = [
        'UID' => $row['UID'],
        'full_name' => $row['full_name'],
        'contact' => $row['contact'],
        'assistance' => $row['assistance'],
        'date' => $row['date'],
        'time' => $row['time'],
        'comments' => $row['comments'],
        'actions' => '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row['id'] . '">Delete</button>'
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
