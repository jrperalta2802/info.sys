<?php
require 'dbcon.php';

// Get parameters sent from DataTables
$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 1;
$orderDirection = isset($_POST['order'][0]['dir']) && in_array($_POST['order'][0]['dir'], ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'asc';

// Column mapping
$columns = [
    0 => 'UIDM',
    1 => 'barangay',
    2 => 'full_name',
    3 => 'member_name',
    4 => 'member_contact',
    5 => 'member_precinct'
];

$orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'UIDM';

// Total records without filtering
$totalRecordsQuery = "SELECT COUNT(*) as count FROM members";
$totalRecordsResult = mysqli_query($con, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['count'];

// Filtering and sorting
$searchQuery = '';
if (!empty($searchValue)) {
    $searchQuery = "WHERE m.UIDM LIKE '%$searchValue%' OR m.member_name LIKE '%$searchValue%' OR m.member_contact LIKE '%$searchValue%' OR m.member_precinct LIKE '%$searchValue%' OR l.full_name LIKE '%$searchValue%' OR l.barangay LIKE '%$searchValue%'";
}

// Total records with filtering
$totalFilteredRecordsQuery = "SELECT COUNT(*) as count FROM members m LEFT JOIN leaders l ON m.leader_id = l.id $searchQuery";
$totalFilteredRecordsResult = mysqli_query($con, $totalFilteredRecordsQuery);
$totalFilteredRecords = mysqli_fetch_assoc($totalFilteredRecordsResult)['count'];

// Fetch data with pagination, filtering, and sorting
$query = "SELECT m.UIDM, m.member_name, m.member_contact, m.member_precinct, l.full_name, l.barangay, m.leader_id 
          FROM members m 
          LEFT JOIN leaders l ON m.leader_id = l.id 
          $searchQuery 
          ORDER BY $orderColumn $orderDirection 
          LIMIT $start, $length";
$dataResult = mysqli_query($con, $query);

$data = [];
while ($row = mysqli_fetch_assoc($dataResult)) {
    $data[] = [
        'UIDM' => $row['UIDM'],
        'barangay' => $row['barangay'],
        'full_name' => $row['full_name'],
        'member_name' => $row['member_name'],
        'member_contact' => $row['member_contact'],
        'member_precinct' => $row['member_precinct'],
        'leader_id' => $row['leader_id'],
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
