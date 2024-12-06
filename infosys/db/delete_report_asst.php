<?php
require 'dbcon.php';

// Get the UID sent from the AJAX request
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Validate UID
if ($id <= 0) {
    echo json_encode(['status' => 400, 'message' => 'Valid id is required']);
    exit;
}

// Prepare and execute the DELETE query
$query = "DELETE FROM reports_help WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 200, 'message' => 'Record deleted successfully']);
} else {
    echo json_encode(['status' => 500, 'message' => 'Failed to delete record']);
}

$stmt->close();
$con->close();
?>
