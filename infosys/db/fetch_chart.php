<?php
include 'dbcon.php';

// Define the barangays
$barangays = [
    'Bagbaguin', 'Bagong Barrio', 'Baka-bakahan', 'Bunsuran I', 'Bunsuran II', 'Bunsuran III',
    'Cacarong Bata', 'Cacarong Matanda', 'Cupang', 'Malibong Bata', 'Malibong Matanda', 'Manatal',
    'Mapulang Lupa', 'Masagana', 'Masuso', 'Pinagkuartelan', 'Poblacion', 'Real de Cacarong',
    'San Roque', 'Santo Niño', 'Siling Bata', 'Siling Matanda'
];

$leaderCounts = [];
$memberCounts = [];

// Fetch counts for leaders per barangay
foreach ($barangays as $barangay) {
    $leaderSql = "SELECT COUNT(*) as count FROM leaders WHERE barangay = ?";
    $stmt = $con->prepare($leaderSql);
    $stmt->bind_param("s", $barangay);
    $stmt->execute();
    $leaderResult = $stmt->get_result();
    $leaderRow = $leaderResult->fetch_assoc();
    $leaderCounts[] = $leaderRow['count'];
    $stmt->close();
}

// Fetch counts for members per barangay using a JOIN between members and leaders
foreach ($barangays as $barangay) {
    $memberSql = "
        SELECT COUNT(m.UIDM) as count
        FROM members m
        LEFT JOIN leaders l ON m.leader_id = l.id
        WHERE l.barangay = ?";
    $stmt = $con->prepare($memberSql);
    $stmt->bind_param("s", $barangay);
    $stmt->execute();
    $memberResult = $stmt->get_result();
    $memberRow = $memberResult->fetch_assoc();
    $memberCounts[] = $memberRow['count'];
    $stmt->close();
}

// Close the connection
$con->close();
?>

<script>
// Convert PHP arrays of barangays, leader counts, and member counts to JavaScript
const barangays = <?php echo json_encode($barangays); ?>;
const leaderCounts = <?php echo json_encode($leaderCounts); ?>;
const memberCounts = <?php echo json_encode($memberCounts); ?>;
</script>
