<?php
include 'dbcon.php'; 

// Fetch row counts from different tables
$leaderCount = 0;
$userCount = 0;
$transactionCount = 0;

$sql1 = "SELECT COUNT(*) as count FROM leaders";
$sql2 = "SELECT COUNT(*) as count FROM members";
$sql3 = "SELECT COUNT(*) as count FROM reports";
$sql4 = "SELECT COUNT(*) as count FROM users";

$result1 = $con->query($sql1);
if ($result1->num_rows > 0) {
    $row = $result1->fetch_assoc();
    $leaderCount = $row['count'];
}

$result2 = $con->query($sql2);
if ($result2->num_rows > 0) {
    $row = $result2->fetch_assoc();
    $memberCount = $row['count'];
}

$result3 = $con->query($sql3);
if ($result3->num_rows > 0) {
    $row = $result3->fetch_assoc();
    $reportsCount = $row['count'];
}

$result4 = $con->query($sql4);
if ($result4->num_rows > 0) {
    $row = $result4->fetch_assoc();
    $usersCount = $row['count'];
}

$con->close();
?>
