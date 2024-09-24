<?php
include('phpqrcode/qrlib.php');
require 'dbcon.php';

// Path to the ID card template image (replace with your actual path)
$template_image = '/path_to_your_template/template.png';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $is_leader = $_GET['type'] === 'leader';

    // Fetch data based on whether it's a leader or member
    if ($is_leader) {
        $sql = "SELECT UID, full_name, barangay, precint_no FROM leaders WHERE UID = ?";
    } else {
        $sql = "SELECT UIDM, member_name, member_precinct, leader_id FROM members WHERE UIDM = ?";
    }

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Extract data
        $uid = $is_leader ? $row['UID'] : $row['UIDM'];
        $name = $is_leader ? $row['full_name'] : $row['member_name'];
        $precinct = $is_leader ? $row['precint_no'] : $row['member_precinct'];
        $barangay = $is_leader ? $row['barangay'] : getBarangayForLeader($row['leader_id'], $con);
        
        // Generate QR code
        $qr_data = $is_leader ? "Leader UID: $uid\nName: $name\nBarangay: $barangay\nPrecinct: $precinct" :
            "Leader UID: " . getLeaderUID($row['leader_id'], $con) . "\nMember UIDM: $uid\nName: $name\nBarangay: $barangay\nPrecinct: $precinct";
        
        ob_start();
        QRcode::png($qr_data, null, QR_ECLEVEL_L, 4);
        $qr_image = imagecreatefromstring(ob_get_contents());
        ob_end_clean();
        
        // Create ID card from template
        $id_card = imagecreatefrompng($template_image);
        $black = imagecolorallocate($id_card, 0, 0, 0);
        $font_path = '/path_to_your_fonts/font.ttf'; // Replace with the actual font path
        
        // Add text to the ID card (adjust coordinates as necessary)
        imagettftext($id_card, 24, 0, 150, 200, $black, $font_path, $name);       // Full Name
        imagettftext($id_card, 20, 0, 150, 250, $black, $font_path, $barangay);   // Barangay
        imagettftext($id_card, 20, 0, 150, 300, $black, $font_path, $precinct);   // Precinct
        imagettftext($id_card, 20, 0, 150, 350, $black, $font_path, $uid);        // UID or UIDM
        
        // Overlay the QR code (adjust coordinates to position it correctly)
        imagecopy($id_card, $qr_image, 500, 350, 0, 0, imagesx($qr_image), imagesy($qr_image));

        // Set the correct content type for image output
        header('Content-Type: image/png');
        
        // Output the final ID card
        imagepng($id_card);
        
        // Clean up
        imagedestroy($id_card);
        imagedestroy($qr_image);
    } else {
        echo "No data found.";
    }

    $stmt->close();
}

// Helper function to get Barangay for a Leader
function getBarangayForLeader($leader_id, $con) {
    $sql = "SELECT barangay FROM leaders WHERE UID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['barangay'] : '';
}

// Helper function to get Leader UID
function getLeaderUID($leader_id, $con) {
    $sql = "SELECT UID FROM leaders WHERE UID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['UID'] : '';
}
?>
