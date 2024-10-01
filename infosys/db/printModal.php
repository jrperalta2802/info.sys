<?php
require 'dbcon.php';

if (isset($_GET['leader_id'])) {
    $leader_id = $_GET['leader_id'];

    // Fetch leader data from the database
    $query = "SELECT UID, full_name, barangay, leaders_photo FROM leaders WHERE UID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($leader = $result->fetch_assoc()) {
        // Return the leader data as a JSON response
        echo json_encode([
            'status' => 200,
            'uid' => $leader['UID'],
            'full_name' => $leader['full_name'],
            'barangay' => $leader['barangay'],
            'photo' => '/info.sys/infosys/db/uploads/leaders/' . $leader['leaders_photo']
        ]);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Leader not found']);
    }

    $stmt->close();
    $con->close();
}
?>
