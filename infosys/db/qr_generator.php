<?php
include('phpqrcode/qrlib.php');
require 'dbcon.php';




if ($con->connect_error) {
    die("connection failed: " . $con->connect_error);
}

// Query to fetch data for a leader
$sql = "SELECT UID, full_name, precint_no, barangay FROM leaders WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id);
$id = 17; // Replace with the actual ID or fetch dynamically
$stmt->execute();
$result = $stmt->get_result();  

if ($row = $result->fetch_assoc()) {
    $UID = $row['UID'];
    $full_name = $row['full_name'];
    $precint_no = $row['precint_no'];
    $barangay = $row['barangay'];

    // Data to encode
   $data = "ID $UID\nName $full_name\nPrecinct $precint_no\nAddress $barangay";
    echo "Data to encode: " . $data;
    
    // Generate QR code
    $filePath = 'qrcodes/' . $UID . '.png';
    QRcode::png($data, $filePath, QR_ECLEVEL_Q, 5);

    echo '<img src="' . $filePath . '" alt="QR Code">';
} else {
    echo "No data found!";
}

$stmt->close();
$con->close();

?>
