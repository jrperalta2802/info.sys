<script>
$(document).on('submit', '#saveLeader', function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append("save_leader", true);

    $.ajax({
        type: "POST",
        url: "db/personProcess.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log("Raw response:", response); // Log raw response

            try {
                var res = typeof response === 'object' ? response : jQuery.parseJSON(response);
                console.log("Parsed JSON:", res); // Log parsed JSON

                if (res.status == 422) {
                    $('#errorMessage').removeClass('d-none');
                    $('#errorMessage').text(res.message);
                } else if (res.status == 200) {
                    $('#errorMessage').addClass('d-none');
                    $('#leaderAddModal').modal('hide');
                    $('#saveLeader')[0].reset();

                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);

                    $('#myTable').load(location.href + " #myTable");
                } else if (res.status == 500) {
                    $('#errorMessage').removeClass('d-none');
                    $('#errorMessage').text(res.message);
                } else {
                    console.error("Unexpected response: ", res);
                    $('#errorMessage').removeClass('d-none');
                    $('#errorMessage').text("An unexpected error occurred. Check console for details.");
                }
            } catch (e) {
                console.error("Error parsing JSON response: ", e, response);
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text("An error occurred while processing the response. Check console for details.");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX error: ", textStatus, errorThrown);
            $('#errorMessage').removeClass('d-none');
            $('#errorMessage').text("An AJAX error occurred. Check console for details.");
        }
    });
});


$(document).on('click', '.editLeaderBtn', function () {
    var leader_id = $(this).val();
    $.ajax({
        type: "GET",
        url: "db/personProcess.php?leader_id=" + leader_id,
        success: function (response) {
            var res = typeof response === 'object' ? response : jQuery.parseJSON(response);
            if (res.status == 404) {
                alert(res.message);
            } else if (res.status == 200) {
                $('#leader_id').val(res.data.leader.id);
                $('#edit_barangay').val(res.data.leader.barangay);
                $('#edit_full_name').val(res.data.leader.full_name);
                $('#edit_contact_number').val(res.data.leader.contact_number);
                $('#edit_precint_no').val(res.data.leader.precint_no);
                $('#edit_birthdate').val(res.data.leader.birthdate);
                $('#edit_age').val(res.data.leader.age);
                $('#edit_civil_status').val(res.data.leader.civil_status);
                $('#edit_sex').val(res.data.leader.sex);
                $('#edit_address').val(res.data.leader.address);

                // Load leader photo
                $('#leader_photo_preview').attr('src', '/info.sys/infosys/db/uploads/leaders/' + res.data.leader.leaders_photo);

                // Clear existing member forms
                $('#edit-members-container').empty();

                // Populate members
                res.data.members.forEach(function (member, index) {
                    var memberForm = `
                        <div class="form-row row member-form">
                            <div class="col-md-4 mb-3">
                                <label for="member_name">Full Name</label>
                                <input type="text" class="form-control" name="member_name[]" value="${member.member_name}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="member_birthdate">Birthdate</label>
                                <input type="date" class="form-control" name="member_birthdate[]" value="${member.member_birthdate}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="member_contact">Contact Number</label>
                                <input type="tel" class="form-control" name="member_contact[]" value="${member.member_contact}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="member_precinct">Precinct No.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="text" class="form-control" name="member_precinct[]" value="${member.member_precinct}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger remove-member">Remove</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="member_photo">Member Photo</label>
                                <input type="file" class="form-control" name="member_photo[]">
                                <img src="/info.sys/infosys/db/uploads/members/${member.member_photo}" alt="" class="img-fluid rounded mt-2" style="max-width: 50px;">
                                <input type="hidden" name="existing_member_photo[]" value="${member.member_photo}">
                            </div>
                        </div>`;
                    $('#edit-members-container').append(memberForm);
                });

                $('#leaderEditModal').modal('show');
            }
        }
    });
});

$(document).on('submit', '#updateLeader', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append("update_leader", true);

    // Debugging statement
    for (var pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }

    $.ajax({
        type: "POST",
        url: "db/personProcess.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json', // This ensures jQuery expects a JSON response
        success: function (response) {
            console.log(response);

            if (response.status == 422) {
                $('#errorMessageUpdate').removeClass('d-none');
                $('#errorMessageUpdate').text(response.message);
            } else if (response.status == 200) {
                $('#errorMessageUpdate').addClass('d-none');
                alertify.set('notifier', 'position', 'top-right');
                alertify.success(response.message);
                $('#leaderEditModal').modal('hide');
                $('#updateLeader')[0].reset();
                $('#myTable').load(location.href + " #myTable");
            } else if (response.status == 500) {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText); // Log the full error response
            $('#errorMessageUpdate').removeClass('d-none');
            $('#errorMessageUpdate').text('Error processing request. Please try again.');
        }
    });
});


$(document).on('click', '.viewLeaderBtn', function () {
    var leader_id = $(this).val();
    $.ajax({
        type: "GET",
        url: "db/personProcess.php?leader_id=" + leader_id,
        success: function (response) {
            var res = typeof response === 'object' ? response : jQuery.parseJSON(response);
            if (res.status == 404) {
                alert(res.message);
            } else if (res.status == 200) {
                $('#view_leader_photo').attr('src', '/info.sys/infosys/db/uploads/leaders/' + res.data.leader.leaders_photo);
                $('#view_barangay').text(res.data.leader.barangay);
                $('#view_full_name').text(res.data.leader.full_name);
                $('#view_precint_no').text(res.data.leader.precint_no);
                $('#view_contact_number').text(res.data.leader.contact_number);
                $('#view_address').text(res.data.leader.address);
                $('#view_birthdate').text(res.data.leader.birthdate);
                $('#view_age').text(res.data.leader.age);
                $('#view_civil_status').text(res.data.leader.civil_status);
                $('#view_sex').text(res.data.leader.sex);
                $('#view_uid').text(res.data.leader.UID); // Display leader UID

                // Populate members table
                var membersTableBody = $('#membersTableBody');
                membersTableBody.empty(); // Clear existing rows

                res.data.members.forEach(function (member) {
                    var row = '<tr>' +
                        '<td><img src="/info.sys/infosys/db/uploads/members/' + member.member_photo + '" alt="" class="img-fluid rounded" style="max-width: 50px;"></td>' +
                        '<td>' + member.UID + '</td>' + // Display member UID
                        '<td>' + member.member_name + '</td>' +
                        '<td>' + member.member_birthdate + '</td>' +
                        '<td>' + member.member_contact + '</td>' +
                        '<td>' + member.member_precinct + '</td>' +
                        '<td><button type="button" class="btn btn-success popover-btn" data-container="body" data-toggle="popover" = data-bs-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">Info</button></td>' +
                        '</tr>';
                    membersTableBody.append(row);
                })

                $(document).ready(function(){
                    $('[data-toggle="popover"]').popover({
                        placement : 'right',
                        trigger : 'focus',
                        html : true,
                        content : '<div class="media"><img src="db/uploads/leaders/1x1.png" class="mr-3" alt="QR Code"></div>'
                    });
                });
                // Clear the previous QR code
                $('#view_qr_code').attr('src', '');

                // Show the modal
                $('#leaderViewModal').modal('show');
            }
        }
    });
});

// Generate QR Code on button click
$(document).on('click', '.generateQRBtn', function () {
    var leader_id = $('#view_uid').text(); // Get the leader UID from the modal
    $.ajax({
        type: "GET",
        url: "db/generateQRCode.php?leader_id=" + leader_id,
        success: function (response) {
            $('#view_qr_code').attr('src', 'data:image/png;base64,' + response);
        }
    });
});

$(document).on('click', '.deleteLeaderBtn', function (e) {
    e.preventDefault();
    if (confirm('Are you sure you want to delete this data?')) {
        var leader_id = $(this).val();
        $.ajax({
            type: "POST",
            url: "db/personProcess.php",
            data: {
                'delete_leader': true,
                'leader_id': leader_id
            },
            success: function (response) {
                var res = typeof response === 'object' ? response : jQuery.parseJSON(response);
                if (res.status == 500) {
                    alert(res.message);
                } else {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);
                    $('#myTable').load(location.href + " #myTable");
                }
            }
        });
    }
});
</script>