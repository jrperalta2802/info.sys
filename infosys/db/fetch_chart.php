<?php
include 'dbcon.php';

// Fetch leaders per barangay
$barangays = [
    'Bagbaguin', 'Bagong Barrio', 'Baka-bakahan', 'Bunsuran I', 'Bunsuran II', 'Bunsuran III',
    'Cacarong Bata', 'Cacarong Matanda', 'Cupang', 'Malibong Bata', 'Malibong Matanda', 'Manatal',
    'Mapulang Lupa', 'Masagana', 'Masuso', 'Pinagkuartelan', 'Poblacion', 'Real de Cacarong',
    'San Roque', 'Santo Niño', 'Siling Bata', 'Siling Matanda'
];

$leaderCounts = [];
$voteCounts = [];

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

// Fetch counts for votes per barangay
foreach ($barangays as $barangay) {
    $voteSql = "SELECT COUNT(*) as count FROM reports WHERE barangay = ?";
    $stmt = $con->prepare($voteSql);
    $stmt->bind_param("s", $barangay);
    $stmt->execute();
    $voteResult = $stmt->get_result();
    $voteRow = $voteResult->fetch_assoc();
    $voteCounts[] = $voteRow['count'];
    $stmt->close();
}

// Close the connection
$con->close();
?>

<script>
// Convert PHP arrays of barangays, leader counts, and vote counts to JavaScript
const barangays = <?php echo json_encode($barangays); ?>;
const leaderCounts = <?php echo json_encode($leaderCounts); ?>;
const voteCounts = <?php echo json_encode($voteCounts); ?>;
</script>
