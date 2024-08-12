<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'dbcon.php';

ob_start();
header('Content-Type: application/json');

if (isset($_POST['save_leader'])) {
    $barangay = mysqli_real_escape_string($con, $_POST['barangay']);
    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
    $contact_number = mysqli_real_escape_string($con, $_POST['contact_number']);
    $precint_no = mysqli_real_escape_string($con, $_POST['precint_no']);
    $birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $civil_status = mysqli_real_escape_string($con, $_POST['civil_status']);
    $sex = mysqli_real_escape_string($con, $_POST['sex']);
    $address = mysqli_real_escape_string($con, $_POST['address']);

    // Check if leader already exists
    $check_query = "SELECT id FROM leaders WHERE full_name = ? AND contact_number = ? AND precint_no = ?";
    $stmt = $con->prepare($check_query);
    $stmt->bind_param("sss", $full_name, $contact_number, $precint_no);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 400, 'message' => 'Leader already exists']);
        exit();
    }

    $stmt->close();

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

    // Insert leader data
    $query = "INSERT INTO leaders (barangay, full_name, contact_number, precint_no, birthdate, age, civil_status, sex, address, leaders_photo) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssssssss", $barangay, $full_name, $contact_number, $precint_no, $birthdate, $age, $civil_status, $sex, $address, $leaders_photo_name);
    if ($stmt->execute()) {
        $leader_id = $stmt->insert_id;

        foreach ($_POST['member_name'] as $index => $name) {
            $name = mysqli_real_escape_string($con, $name);
            $member_birthdate = mysqli_real_escape_string($con, $_POST['member_birthdate'][$index]);
            $member_contact = mysqli_real_escape_string($con, $_POST['member_contact'][$index]);
            $member_precinct = mysqli_real_escape_string($con, $_POST['member_precinct'][$index]);

            // Check if member already exists
            $check_member_query = "SELECT id FROM members WHERE leader_id = ? AND member_name = ? AND member_contact = ?";
            $stmt = $con->prepare($check_member_query);
            $stmt->bind_param("iss", $leader_id, $name, $member_contact);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // If member already exists, skip to the next one
                continue;
            }

            $stmt->close();

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

            // Insert member data
            $query = "INSERT INTO members (leader_id, member_name, member_birthdate, member_contact, member_precinct, member_photo) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("isssss", $leader_id, $name, $member_birthdate, $member_contact, $member_precinct, $member_photo_name);
            $stmt->execute();
        }

        echo json_encode(['status' => 200, 'message' => 'Leader and members created successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Leader not created']);
    }
    $stmt->close();
}

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

    // Define the upload directory
    $upload_dir = 'uploads/leaders/';
    $leader_photo_name = basename($leaders_photo); // Extract only the file name
    $leader_photo_folder = $upload_dir . $leader_photo_name;

    $old_leader_photo = ''; // Initialize variable to store the old photo path

    // Fetch the old photo path from the database
    $result = mysqli_query($con, "SELECT leaders_photo FROM leaders WHERE id='$leader_id'");
    if ($row = mysqli_fetch_assoc($result)) {
        $old_leader_photo = $row['leaders_photo'];
    }

    // Ensure the directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
    }

    // Handle file replacement
    if ($leaders_photo) {
        if (move_uploaded_file($leader_photo_tmp, $leader_photo_folder)) {
            // Delete old photo if it exists and is not the same as the new one
            if ($old_leader_photo && file_exists($upload_dir . $old_leader_photo) && $old_leader_photo !== $leader_photo_name) {
                unlink($upload_dir . $old_leader_photo);
            }
            // Store only the file name in the database
            $leader_photo_query = ", leaders_photo='$leader_photo_name'";
        } else {
            error_log("Failed to move uploaded file for leader photo.");
            $leader_photo_query = ''; // Don't update the photo if upload fails
        }
    } else {
        // Use existing photo if no new photo is uploaded
        $leader_photo_query = '';
    }

    // Prepare and execute the update query
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

            // Handle file upload for member photo
            $member_photo = isset($_FILES['member_photo']['name'][$index]) ? $_FILES['member_photo']['name'][$index] : '';
            $member_photo_tmp = isset($_FILES['member_photo']['tmp_name'][$index]) ? $_FILES['member_photo']['tmp_name'][$index] : '';

            // Define the upload directory and extract the filename
            $upload_dir = 'uploads/members/';
            $member_photo_name = basename($member_photo); // Extract only the file name
            $member_photo_folder = $upload_dir . $member_photo_name;

            // Handle file replacement
            if ($member_photo) {
                if (move_uploaded_file($member_photo_tmp, $member_photo_folder)) {
                    // Delete old photo if it exists and is not the same as the new one
                    $old_member_photo = isset($_POST['existing_member_photo'][$index]) ? $_POST['existing_member_photo'][$index] : '';
                    if ($old_member_photo && file_exists($upload_dir . $old_member_photo) && $old_member_photo !== $member_photo_name) {
                        unlink($upload_dir . $old_member_photo);
                    }
                } else {
                    error_log("Failed to move uploaded file for member photo at index $index.");
                    $member_photo_name = ''; // Set to empty if upload fails
                }
            } else {
                // Use existing photo if no new photo is uploaded
                $member_photo_name = isset($_POST['existing_member_photo'][$index]) ? $_POST['existing_member_photo'][$index] : '';
            }

            // Prepare and execute the database insert query
            $member_stmt = $con->prepare(
                "INSERT INTO members (leader_id, member_name, member_birthdate, member_contact, member_precinct, member_photo) 
                VALUES (?, ?, ?, ?, ?, ?)"
            );
            $member_stmt->bind_param("isssss", $leader_id, $member_name, $member_birthdate, $member_contact, $member_precinct, $member_photo_name);

            if (!$member_stmt->execute()) {
                error_log("Error inserting member: " . $member_stmt->error);
            }
        }

        echo json_encode(['status' => 200, 'message' => 'Leader updated successfully!']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Error updating leader: ' . $update_leader_stmt->error]);
    }
}
if (isset($_GET['leader_id'])) {
    $leader_id = $_GET['leader_id'];
    $leader_query = "SELECT * FROM leaders WHERE id = '$leader_id'";
    $leader_result = mysqli_query($con, $leader_query);

    if (mysqli_num_rows($leader_result) == 1) {
        $leader = mysqli_fetch_assoc($leader_result);

        $members_query = "SELECT * FROM members WHERE leader_id = '$leader_id'";
        $members_result = mysqli_query($con, $members_query);
        $members = mysqli_fetch_all($members_result, MYSQLI_ASSOC);

        echo json_encode([
            'status' => 200,
            'data' => [
                'leader' => $leader,
                'members' => $members
            ]
        ]);
    } else {
        echo json_encode(['status' => 404, 'message' => 'Leader not found']);
    }
}
if (isset($_POST['delete_leader'])) {
    $leader_id = mysqli_real_escape_string($con, $_POST['leader_id']);

    // Start transaction
    mysqli_begin_transaction($con);

    try {
        // Delete members associated with the leader
        $delete_members_query = "DELETE FROM members WHERE leader_id='$leader_id'";
        $delete_members_run = mysqli_query($con, $delete_members_query);

        // Delete the leader
        $delete_leader_query = "DELETE FROM leaders WHERE id='$leader_id'";
        $delete_leader_run = mysqli_query($con, $delete_leader_query);

        // Check if both queries were successful
        if ($delete_members_run && $delete_leader_run) {
            mysqli_commit($con); // Commit the transaction
            echo json_encode(['status' => 200, 'message' => 'Leader and Members deleted successfully']);
        } else {
            mysqli_rollback($con); // Rollback the transaction in case of error
            echo json_encode(['status' => 500, 'message' => 'Leader and Members not deleted']);
        }
    } catch (Exception $e) {
        mysqli_rollback($con); // Rollback the transaction in case of exception
        echo json_encode(['status' => 500, 'message' => 'An error occurred. Leader and Members not deleted']);
    }
}

ob_end_flush();
?>
