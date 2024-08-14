<?php
include('phpqrcode/qrlib.php');

if (isset($_GET['leader_id'])) {
    $leader_id = $_GET['leader_id'];

    // Fetch leader data from the database
    require 'dbcon.php';
    $sql = "SELECT UID, full_name, precint_no, barangay FROM leaders WHERE UID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $leader_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $UID = $row['UID'];
        $full_name = $row['full_name'];
        $precint_no = $row['precint_no'];
        $barangay = $row['barangay'];

        // Data to encode in the QR code
        $data = "ID $UID\nName $full_name\nPrecinct $precint_no\nAddress $barangay";

        // Generate QR code
        ob_start();
        QRcode::png($data, null, QR_ECLEVEL_L, 10);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();

        // Return the base64 image string
        echo $imageString;
    }

    $stmt->close();
    $con->close();
}
?>
