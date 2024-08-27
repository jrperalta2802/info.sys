<?php
include('phpqrcode/qrlib.php');
require 'dbcon.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];
    
    // Fetch member data
    $sql = "SELECT UIDM, member_name, member_precinct, leader_id FROM members WHERE UIDM = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($memberRow = $result->fetch_assoc()) {
        $memberUIDM = $memberRow['UIDM'];
        $memberName = $memberRow['member_name'];
        $memberPrecinct = $memberRow['member_precinct'];
        $leader_id = $memberRow['leader_id'];

        // Fetch leader data using leader_id
        $sqlLeader = "SELECT UID, barangay FROM leaders WHERE id = ?";
        $stmtLeader = $con->prepare($sqlLeader);
        $stmtLeader->bind_param("i", $leader_id);
        $stmtLeader->execute();
        $resultLeader = $stmtLeader->get_result();

        if ($leaderRow = $resultLeader->fetch_assoc()) {
            $leaderUID = $leaderRow['UID'];
            $leaderBarangay = $leaderRow['barangay'];

            // Data to encode in the QR code
            $data = "Leader UID: $leaderUID\nMember UIDM: $memberUIDM\nName: $memberName\nPrecinct: $memberPrecinct\nBarangay: $leaderBarangay";

            // Generate QR code
            ob_start();
            QRcode::png($data, null, QR_ECLEVEL_L, 10);
            $imageString = base64_encode(ob_get_contents());
            ob_end_clean();

            // Return the base64 image string
            echo $imageString;
        } else {
            // Handle the case where the leader is not found
            echo "ERROR: Leader not found.";
        }

        $stmtLeader->close();
    } else {
        // Handle the case where the member is not found
        echo "ERROR: Member not found.";
    }

    $stmt->close();
    $con->close();
} else {
    echo "ERROR: Invalid parameters.";
}
?>
