<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//test push

require 'dbcon.php';

// Enable output buffering
ob_start();
header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['save_leader'])) {
    // Validate and sanitize inputs
    $barangay = mysqli_real_escape_string($con, $_POST['barangay']);
    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
    $contact_number = mysqli_real_escape_string($con, $_POST['contact_number']);
    $precint_no = mysqli_real_escape_string($con, $_POST['precint_no']);
    $birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
    $address = mysqli_real_escape_string($con, $_POST['address']);

    // Handle leader's photo upload
    if (isset($_FILES['leaders_photo']) && $_FILES['leaders_photo']['error'] == 0) {
        $leaders_photo_name = $_FILES['leaders_photo']['name'];
        $leaders_photo_tmp = $_FILES['leaders_photo']['tmp_name'];
        $leaders_photo_path = 'uploads/leaders/' . $leaders_photo_name;
        if (!move_uploaded_file($leaders_photo_tmp, $leaders_photo_path)) {
            echo json_encode(['status' => 500, 'message' => 'Failed to upload leader photo']);
            exit();
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Leader photo is required']);
        exit();
    }

    $query = "INSERT INTO leaders (barangay, full_name, contact_number, precint_no, birthdate, age, civil_status, sex, address, leaders_photo) 
              VALUES ('$barangay', '$full_name', '$contact_number', '$precint_no', '$birthdate', '$age', '$civil_status', '$sex', '$address', '$leaders_photo_name')";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $leader_id = mysqli_insert_id($con);
        $member_name = $_POST['member_name'];
        $member_birthdate = $_POST['member_birthdate'];
        $member_contact = $_POST['member_contact'];
        $member_precinct = $_POST['member_precinct'];

        foreach ($member_name as $index => $name) {
            $name = mysqli_real_escape_string($con, $name);
            $birthdate = mysqli_real_escape_string($con, $member_birthdate[$index]);
            $contact = mysqli_real_escape_string($con, $member_contact[$index]);
            $precinct = mysqli_real_escape_string($con, $member_precinct[$index]);

            // Handle member's photo upload
            if (isset($_FILES['member_photo']['name'][$index]) && $_FILES['member_photo']['error'][$index] == 0) {
                $member_photo_name = $_FILES['member_photo']['name'][$index];
                $member_photo_tmp = $_FILES['member_photo']['tmp_name'][$index];
                $member_photo_path = 'uploads/members/' . $member_photo_name;
                if (!move_uploaded_file($member_photo_tmp, $member_photo_path)) {
                    echo json_encode(['status' => 500, 'message' => 'Failed to upload member photo']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 400, 'message' => 'Member photo is required']);
                exit();
            }

            $query = "INSERT INTO members (leader_id, member_name, member_birthdate, member_contact, member_precinct, member_photo)
                      VALUES ('$leader_id', '$name', '$birthdate', '$contact', '$precinct', '$member_photo_name')";

            $query_run = mysqli_query($con, $query);
        }

        echo json_encode(['status' => 200, 'message' => 'Leader and members created successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Leader not created']);
    }
}

//fix error naming con "conn"
// Assuming you've already included the database connection
if (isset($_POST['update_leader'])) {
    // Validate and sanitize input data
    $leader_id = mysqli_real_escape_string($con, $_POST['leader_id']);
    $barangay = mysqli_real_escape_string($con, $_POST['barangay']);
    $contact_number = mysqli_real_escape_string($con, $_POST['contact_number']);
    $precint_no = mysqli_real_escape_string($con, $_POST['precint_no']);
    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
    $birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);

    // Handle file upload for leader photo
    $leaders_photo = isset($_FILES['leaders_photo']['name']) ? $_FILES['leaders_photo']['name'] : '';
    $leader_photo_tmp = isset($_FILES['leaders_photo']['tmp_name']) ? $_FILES['leaders_photo']['tmp_name'] : '';
    $leader_photo_folder = 'uploads/leaders/' . $leaders_photo;

    $leader_photo_query = "";
    if ($leaders_photo) {
        if (move_uploaded_file($leader_photo_tmp, $leader_photo_folder)) {
            $leader_photo_query = ", leaders_photo='$leader_photo_folder'";
        } else {
            error_log("Failed to move uploaded file for leader photo.");
        }
    }

    // Use a prepared statement for updating the leader
    $update_leader_stmt = $con->prepare(
        "UPDATE leaders 
        SET barangay=?, contact_number=?, precint_no=?, full_name=?, birthdate=?, age=?, address=?, civil_status=?, sex=? $leader_photo_query
        WHERE id=?"
    );
    $update_leader_stmt->bind_param("sssssssssi", $barangay, $contact_number, $precint_no, $full_name, $birthdate, $age, $address, $civil_status, $sex, $leader_id);

    if ($update_leader_stmt->execute()) {
        // Delete existing members
        $delete_members_query = "DELETE FROM members WHERE leader_id='$leader_id'";
        if (!mysqli_query($con, $delete_members_query)) {
            error_log("Error deleting members: " . mysqli_error($con));
        }

        // Insert new members
        foreach ($_POST['member_name'] as $index => $member_name) {
            $member_birthdate = mysqli_real_escape_string($con, $_POST['member_birthdate'][$index]);
            $member_contact = mysqli_real_escape_string($con, $_POST['member_contact'][$index]);
            $member_precinct = mysqli_real_escape_string($con, $_POST['member_precinct'][$index]);

            $member_photo = isset($_FILES['member_photo']['name'][$index]) ? $_FILES['member_photo']['name'][$index] : '';
            $member_photo_tmp = isset($_FILES['member_photo']['tmp_name'][$index]) ? $_FILES['member_photo']['tmp_name'][$index] : '';
            $member_photo_folder = 'uploads/members/' . $member_photo;

            if ($member_photo) {
                if (!move_uploaded_file($member_photo_tmp, $member_photo_folder)) {
                    error_log("Failed to move uploaded file for member photo at index $index.");
                }
            } else {
                $member_photo_folder = isset($_POST['existing_member_photo'][$index]) ? $_POST['existing_member_photo'][$index] : '';
            }

            $member_stmt = $con->prepare(
                "INSERT INTO members (leader_id, member_name, member_birthdate, member_contact, member_precinct, member_photo) 
                VALUES (?, ?, ?, ?, ?, ?)"
            );
            $member_stmt->bind_param("isssss", $leader_id, $member_name, $member_birthdate, $member_contact, $member_precinct, $member_photo_folder);

            if (!$member_stmt->execute()) {
                error_log("Error inserting member: " . $member_stmt->error);
            }
        }

        echo json_encode(['status' => 200, 'message' => 'Leader updated successfully!']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Error updating leader: ' . $update_leader_stmt->error]);
    }
}
if(isset($_GET['leader_id'])) {
    $leader_id = $_GET['leader_id'];
    $leader_query = "SELECT * FROM leaders WHERE id = '$leader_id'";
    $leader_result = mysqli_query($con, $leader_query);

    if(mysqli_num_rows($leader_result) == 1) {
        $leader = mysqli_fetch_assoc($leader_result);

        $members_query = "SELECT * FROM members WHERE leader_id = '$leader_id'";
        $members_result = mysqli_query($con, $members_query);
        $members = mysqli_fetch_all($members_result, MYSQLI_ASSOC);

        echo json_encode(['status' => 200, 'data' => ['leader' => $leader, 'members' => $members]]);
    } else {
        echo json_encode(['status' => 404, 'message' => 'Leader not found']);
    }
}
if (isset($_POST['delete_leader'])) {
    $leader_id = mysqli_real_escape_string($con, $_POST['leader_id']);

    $query = "DELETE FROM leaders WHERE id='$leader_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        echo json_encode(['status' => 200, 'message' => 'Leader deleted successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Leader not deleted']);
    }
}

ob_end_flush();
?>
