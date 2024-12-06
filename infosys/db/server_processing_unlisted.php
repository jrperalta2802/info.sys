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
    0 => 'id', 
    1 => 'UID',
    2 => 'full_name',
    3 => 'contact',
    4 => 'assistance',
    5 => 'date',
    6 => 'time',
    7 => 'comments'
];

$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'id';

// Total records without filtering
$totalRecordsQuery = "SELECT COUNT(*) as count FROM reports_help WHERE UID NOT LIKE 'UID%' AND UID NOT LIKE 'UIDM%'";
$totalRecordsResult = mysqli_query($con, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['count'];

// Filtering and sorting
$searchQuery = '';
if (!empty($searchValue)) {
    $searchQuery = "AND (id LIKE '%$searchValue%' OR UID LIKE '%$searchValue%' OR full_name LIKE '%$searchValue%')";
}

// Total records with filtering
$totalFilteredRecordsQuery = "SELECT COUNT(*) as count FROM reports_help WHERE UID NOT LIKE 'UID%' AND UID NOT LIKE 'UIDM%' $searchQuery";
$totalFilteredRecordsResult = mysqli_query($con, $totalFilteredRecordsQuery);
$totalFilteredRecords = mysqli_fetch_assoc($totalFilteredRecordsResult)['count'];

// Fetch data with pagination, filtering, and sorting
$query = "SELECT id, UID, full_name, contact, assistance, date, time, comments 
          FROM reports_help 
          WHERE UID NOT LIKE 'UID%' AND UID NOT LIKE 'UIDM%' 
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

// JSON response to DataTables
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilteredRecords,
    "data" => $data
]);

exit;
?>
