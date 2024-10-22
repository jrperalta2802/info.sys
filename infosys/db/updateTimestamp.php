<?php
require 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['uid'];
    $type = $_POST['type'];
    $currentTimestamp = date('Y-m-d H:i:s'); // Current timestamp

    if ($type === 'leader') {
        // Update timestamp for leader
          $query = "UPDATE leaders SET printLeader_timestamp = ? WHERE UID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $currentTimestamp, $uid);
    } elseif ($type === 'member') {
        // Update timestamp for member
       $query = "UPDATE members SET printMember_timestamp = ? WHERE UIDM = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $currentTimestamp, $uid);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 200, 'message' => 'Timestamp updated successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Failed to update timestamp']);
    }

    $stmt->close();
    $con->close();
}
?>
