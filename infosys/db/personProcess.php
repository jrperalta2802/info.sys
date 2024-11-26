<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'dbcon.php';

ob_start();
header('Content-Type: application/json');

// Fetch a member's details by UIDM
if (isset($_GET['UIDM'])) {
    $UIDM = $_GET['UIDM'];

    // Fetch member details
    $member_query = "
        SELECT 
            UIDM, 
            member_name AS full_name,                    
            member_birthdate AS birthdate, 
            member_contact AS contact, 
            member_precinct AS precinct 
        FROM members 
        WHERE UIDM = '$UIDM'";
    $member_result = mysqli_query($con, $member_query);

    if (mysqli_num_rows($member_result) == 1) {
        $member = mysqli_fetch_assoc($member_result);

        // Fetch reports for assistance
        $reports_query = "SELECT * FROM reports_help WHERE UID = '$UIDM'";
        $reports_result = mysqli_query($con, $reports_query);
        $reports = mysqli_fetch_all($reports_result, MYSQLI_ASSOC);

        // Fetch reports for vote
        $votes_query = "SELECT * FROM reports WHERE UID = '$UIDM'";
        $votes_result = mysqli_query($con, $votes_query);
        $votes = mysqli_fetch_all($votes_result, MYSQLI_ASSOC);

        // Send JSON response with updated key names to match frontend
        echo json_encode([
            'status' => 200,
            'data' => [
                'member' => $member, // Matches updated frontend field references
                'reports_help' => $reports, // Matches updated frontend table
                'votes' => $votes // Matches updated frontend table
            ]
        ]);
    } else {
        echo json_encode(['status' => 404, 'message' => 'Member not found']);
    }
    exit();
}


// The rest of the existing functionalities remain unchanged
// Ensure all other features like save, update, delete leaders work as expected

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

    // Handle leader's photo upload (mandatory)
    if (isset($_FILES['leaders_photo']) && $_FILES['leaders_photo']['error'] == 0) {
        $leaders_photo_name = $_FILES['leaders_photo']['name'];
        $leaders_photo_tmp = $_FILES['leaders_photo']['tmp_name'];
        $leaders_photo_path = __DIR__ . '/uploads/leaders/' . $leaders_photo_name;

        // Create directory if it doesn't exist
        if (!file_exists(__DIR__ . '/uploads/leaders')) {
            mkdir(__DIR__ . '/uploads/leaders', 0777, true);
        }

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

            // Check if member already exists under any leader
            $check_member_query = "
                SELECT m.id, l.full_name 
                FROM members m 
                JOIN leaders l ON m.leader_id = l.id 
                WHERE m.member_name = ? AND m.member_contact = ? AND m.member_precinct = ?";
            $stmt = $con->prepare($check_member_query);
            $stmt->bind_param("sss", $name, $member_contact, $member_precinct);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Fetch the leader's name
                $stmt->bind_result($member_id, $leader_name);
                $stmt->fetch();
                echo json_encode(['status' => 422, 'message' => "Member '$name' already exists under leader '$leader_name'."]);
                exit();
            }

            $stmt->close();

            // Handle member's photo upload (optional)
if (isset($_FILES['member_photo']['name'][$index]) && $_FILES['member_photo']['error'][$index] == 0) {
    $member_photo_name = $_FILES['member_photo']['name'][$index];
    $member_photo_tmp = $_FILES['member_photo']['tmp_name'][$index];
    $member_photo_path = __DIR__ . '/uploads/members/' . $member_photo_name;

    // Create directory if it doesn't exist
    if (!file_exists(__DIR__ . '/uploads/members')) {
        mkdir(__DIR__ . '/uploads/members', 0777, true);
    }

    if (!move_uploaded_file($member_photo_tmp, $member_photo_path)) {
        echo json_encode(['status' => 500, 'message' => 'Failed to upload member photo']);
        exit();
    }
} else {
    $member_photo_name = ''; // Set to an empty string or default value if no photo is uploaded
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

if (isset($_POST['update_leader'])) {
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

    $leaders_photo = isset($_FILES['leaders_photo']['name']) ? $_FILES['leaders_photo']['name'] : '';
    $leader_photo_tmp = isset($_FILES['leaders_photo']['tmp_name']) ? $_FILES['leaders_photo']['tmp_name'] : '';

    $upload_dir = 'uploads/leaders/';
    $leader_photo_name = basename($leaders_photo);
    $leader_photo_folder = $upload_dir . $leader_photo_name;

    $old_leader_photo = '';

    $result = mysqli_query($con, "SELECT leaders_photo FROM leaders WHERE id='$leader_id'");
    if ($row = mysqli_fetch_assoc($result)) {
        $old_leader_photo = $row['leaders_photo'];
    }

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if ($leaders_photo) {
        if (move_uploaded_file($leader_photo_tmp, $leader_photo_folder)) {
            if ($old_leader_photo && file_exists($upload_dir . $old_leader_photo) && $old_leader_photo !== $leader_photo_name) {
                unlink($upload_dir . $old_leader_photo);
            }
            $leader_photo_query = ", leaders_photo='$leader_photo_name'";
        } else {
            error_log("Failed to move uploaded file for leader photo.");
            $leader_photo_query = '';
        }
    } else {
        $leader_photo_query = '';
    }

    $update_leader_stmt = $con->prepare(
        "UPDATE leaders 
        SET barangay=?, contact_number=?, precint_no=?, full_name=?, birthdate=?, age=?, address=?, civil_status=?, sex=? $leader_photo_query
        WHERE id=?"
    );
    $update_leader_stmt->bind_param("sssssssssi", $barangay, $contact_number, $precint_no, $full_name, $birthdate, $age, $address, $civil_status, $sex, $leader_id);

    if ($update_leader_stmt->execute()) {
        $delete_members_query = "DELETE FROM members WHERE leader_id='$leader_id'";
        if (!mysqli_query($con, $delete_members_query)) {
            error_log("Error deleting members: " . mysqli_error($con));
            echo json_encode(['status' => 500, 'message' => 'Error deleting members.']);
            exit;
        }

        if (!empty($_POST['member_name'])) {
    foreach ($_POST['member_name'] as $index => $member_name) {
        $member_birthdate = mysqli_real_escape_string($con, $_POST['member_birthdate'][$index]);
        $member_contact = mysqli_real_escape_string($con, $_POST['member_contact'][$index]);
        $member_precinct = mysqli_real_escape_string($con, $_POST['member_precinct'][$index]);

        // Check if the member already exists under a different leader
        $check_member_query = $con->prepare(
            "SELECT id FROM members WHERE member_name=? AND member_birthdate=? AND leader_id!=?"
        );
        $check_member_query->bind_param("ssi", $member_name, $member_birthdate, $leader_id);
        $check_member_query->execute();
        $check_member_query->store_result();

        if ($check_member_query->num_rows > 0) {
            // Member exists under a different leader, return an error
            echo json_encode([
                'status' => 422,
                'message' => "Member '{$member_name}' with birthdate '{$member_birthdate}' is already assigned to another leader."
            ]);
            exit;
        }

        // Proceed with inserting the member if validation passes
        $member_photo = isset($_FILES['member_photo']['name'][$index]) ? $_FILES['member_photo']['name'][$index] : '';
        $member_photo_tmp = isset($_FILES['member_photo']['tmp_name'][$index]) ? $_FILES['member_photo']['tmp_name'][$index] : '';

        $upload_dir = 'uploads/members/';
        $member_photo_name = basename($member_photo);
        $member_photo_folder = $upload_dir . $member_photo_name;

        if ($member_photo) {
            if (move_uploaded_file($member_photo_tmp, $member_photo_folder)) {
                $old_member_photo = isset($_POST['existing_member_photo'][$index]) ? $_POST['existing_member_photo'][$index] : '';
                if ($old_member_photo && file_exists($upload_dir . $old_member_photo) && $old_member_photo !== $member_photo_name) {
                    unlink($upload_dir . $old_member_photo);
                }
            } else {
                error_log("Failed to move uploaded file for member photo at index $index.");
                $member_photo_name = '';
            }
        } else {
            $member_photo_name = isset($_POST['existing_member_photo'][$index]) ? $_POST['existing_member_photo'][$index] : '';
        }

        $member_stmt = $con->prepare(
            "INSERT INTO members (leader_id, member_name, member_birthdate, member_contact, member_precinct, member_photo) 
            VALUES (?, ?, ?, ?, ?, ?)"
        );
        $member_stmt->bind_param("isssss", $leader_id, $member_name, $member_birthdate, $member_contact, $member_precinct, $member_photo_name);

        if (!$member_stmt->execute()) {
            error_log("Error inserting member: " . $member_stmt->error);
        }
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
        $leader_uid = $leader['UID'];

        // Fetch reports from reports_help table
        $reports_query = "SELECT * FROM reports_help WHERE UID = '$leader_uid'";
        $reports_result = mysqli_query($con, $reports_query);
        $reports = mysqli_fetch_all($reports_result, MYSQLI_ASSOC);

        // Fetch votes from reports table
        $votes_query = "SELECT * FROM reports WHERE UID = '$leader_uid'";
        $votes_result = mysqli_query($con, $votes_query);
        $votes = mysqli_fetch_all($votes_result, MYSQLI_ASSOC);

        // Fetch members
        $members_query = "SELECT * FROM members WHERE leader_id = '$leader_id'";
        $members_result = mysqli_query($con, $members_query);
        $members = mysqli_fetch_all($members_result, MYSQLI_ASSOC);

        echo json_encode([
            'status' => 200,
            'data' => [
                'leader' => $leader,
                'members' => $members,
                'reports_help' => $reports,
                'votes' => $votes
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
