<?php
require 'dbcon.php';

// Get the report_id sent from the AJAX request
$report_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Validate report_id
if ($report_id <= 0) {
    echo json_encode(['status' => 400, 'message' => 'Valid report ID is required']);
    exit;
}

// Prepare and execute the DELETE query
$query = "DELETE FROM reports WHERE report_id = ?";
$stmt = $con->prepare($query);

if ($stmt === false) {
    echo json_encode(['status' => 500, 'message' => 'Failed to prepare the query']);
    exit;
}

$stmt->bind_param('i', $report_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 200, 'message' => 'Record deleted successfully']);
} else {
    echo json_encode(['status' => 500, 'message' => 'Failed to delete record']);
}

// Clean up
$stmt->close();
$con->close();
?>
