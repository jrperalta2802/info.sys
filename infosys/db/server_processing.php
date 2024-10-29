<?php
require 'dbcon.php';

// Get parameters sent from DataTables
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Total records without filtering
$totalRecordsQuery = "SELECT COUNT(*) as count FROM leaders";
$totalRecordsResult = mysqli_query($con, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['count'];

// Filtering and sorting
$searchQuery = '';
if (!empty($searchValue)) {
    $searchQuery = "WHERE UID LIKE '%$searchValue%' OR barangay LIKE '%$searchValue%' OR full_name LIKE '%$searchValue%' OR contact_number LIKE '%$searchValue%' OR precint_no LIKE '%$searchValue%'";
}

// Total records with filtering
$totalFilteredRecordsQuery = "SELECT COUNT(*) as count FROM leaders $searchQuery";
$totalFilteredRecordsResult = mysqli_query($con, $totalFilteredRecordsQuery);
$totalFilteredRecords = mysqli_fetch_assoc($totalFilteredRecordsResult)['count'];

// Fetch data with pagination, filtering, and sorting
$query = "SELECT id, UID, barangay, full_name, contact_number, precint_no FROM leaders $searchQuery LIMIT $start, $length";
$dataResult = mysqli_query($con, $query);

$data = [];
while ($row = mysqli_fetch_assoc($dataResult)) {
    $data[] = [
        'id' => $row['id'],  // Add the id column here
        'UID' => $row['UID'],
        'barangay' => $row['barangay'],
        'full_name' => $row['full_name'],
        'contact_number' => $row['contact_number'],
        'precinct_no' => $row['precint_no'],
        'actions' => '', // Actions are handled in the DataTables initialization
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
