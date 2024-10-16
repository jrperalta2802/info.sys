<?php
require 'dbcon.php';

if (isset($_GET['leader_id'])) {
    $leader_id = $_GET['leader_id'];

    // Fetch leader data from the database
    $query = "SELECT UID, full_name, barangay, leaders_photo, precint_no FROM leaders WHERE UID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($leader = $result->fetch_assoc()) {
        // Return the leader data as a JSON response
        echo json_encode([
            'status' => 200,
            'uid' => $leader['UID'],
            'precinct' => $leader['precint_no'],
            'full_name' => $leader['full_name'],
            'barangay' => $leader['barangay'],
            'photo' => '/info.sys/infosys/db/uploads/leaders/' . $leader['leaders_photo']
        ]);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Leader not found']);
    }

    $stmt->close();
} elseif (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

    // Fetch member data from the database
    $query = "SELECT UIDM, member_name, member_precinct, member_photo FROM members WHERE UIDM = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($member = $result->fetch_assoc()) {
        // Return the member data as a JSON response
        echo json_encode([
            'status' => 200,
            'uid' => $member['UIDM'], 
            'precinct' => $member['member_precinct'],
            'full_name' => $member['member_name'],
            'barangay' => $member['member_precinct'], // This should be the leader barangay
            'photo' => '/info.sys/infosys/db/uploads/members/' . $member['member_photo']
        ]);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Member not found']);
    }

    $stmt->close();
}

$con->close();
?>
