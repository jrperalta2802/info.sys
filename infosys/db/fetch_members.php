
<?php
require 'dbcon.php'; // Ensure this points to your database connection file

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['leader_id'])) {
    $leader_id = (int)$_GET['leader_id']; // Cast to integer for safety
    error_log("Received leader_id: " . $leader_id); // Log the leader_id

    // Fetch members associated with the leader
    $members_query = "SELECT * FROM members WHERE leader_id = '$leader_id'"; // Fetch members designated to this leader
    $members_result = mysqli_query($con, $members_query);

    // Check for errors in the query
    if (!$members_result) {
        echo json_encode(['status' => 500, 'message' => 'Database query error: ' . mysqli_error($con)]);
        exit;
    }

    // Fetch all members into an associative array
    $members = mysqli_fetch_all($members_result, MYSQLI_ASSOC);

    // Return the data as a JSON response
    echo json_encode([
        'status' => 200,
        'data' => [
            'members' => $members // Return the members
        ]
    ]);
} else {
    echo json_encode(['status' => 400, 'message' => 'No leader ID provided']);
}

// Close database connection
mysqli_close($con);
?>
