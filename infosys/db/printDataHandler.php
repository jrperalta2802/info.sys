<?php
require 'dbcon.php';

if (isset($_GET['leader_id'])) {
    $leader_id = $_GET['leader_id'];

    // Fetch leader data
$query = "SELECT UID, full_name, barangay, leaders_photo, precint_no, printLeader_timestamp FROM leaders WHERE UID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($leader = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 200,
            'uid' => $leader['UID'],
            'precinct' => $leader['precint_no'],
            'full_name' => $leader['full_name'],
            'barangay' => $leader['barangay'],
            'photo' => '/info.sys/infosys/db/uploads/leaders/' . $leader['leaders_photo'],
            'printLeader_timestamp' => $leader['printLeader_timestamp']
        ]);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Leader not found']);
    }

    $stmt->close();
} elseif (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

     // Fetch member data
   $query = "SELECT m.UIDM, m.member_name, m.member_precinct, m.member_photo, l.barangay, m.printMember_timestamp
          FROM members m
          JOIN leaders l ON m.leader_id = l.id
          WHERE m.UIDM = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($member = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 200,
            'uid' => $member['UIDM'],
            'precinct' => $member['member_precinct'],
            'full_name' => $member['member_name'],
            'barangay' => $member['barangay'],
            'photo' => '/info.sys/infosys/db/uploads/members/' . $member['member_photo'],
            'printMember_timestamp' => $member['printMember_timestamp']
        ]);
} else {
    echo json_encode(['status' => 500, 'message' => 'Member not found']);
}

    $stmt->close();
}

$con->close();
?>
