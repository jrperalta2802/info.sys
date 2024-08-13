<!-- Edit Leader Modal -->
<div class="modal fade" id="leaderEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Leader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateLeader" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="errorMessage" class="alert alert-warning d-none"></div>
                        <input type="hidden" id="leader_id" name="leader_id">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="edit_barangay">Barangay</label>
                                <input type="text" id="edit_barangay" name="barangay" class="form-control" list="barangay-list">
                                <datalist id="barangay-list">
                                    <?php include 'includes/brgy_opt.php'; ?>
                                </datalist>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_contact_number">Contact Number</label>
                                <input type="tel" id="edit_contact_number" name="contact_number" class="form-control" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_precint_no">Precint No.</label>
                                <input type="text" id="edit_precint_no" name="precint_no" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_full_name">Full Name</label>
                                <input type="text" id="edit_full_name" name="full_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_birthdate">Birthdate</label>
                                <input type="date" id="edit_birthdate" name="birthdate" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="edit_age">Age</label>
                                <input type="number" id="edit_age" name="age" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_address">Address</label>
                                <input type="text" id="edit_address" name="address" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="edit_civil_status">Civil Status</label>
                                <select id="edit_civil_status" name="civil_status" class="form-select">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="edit_sex">Sex</label>
                                <select id="edit_sex" name="sex" class="form-select">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="leaders_photo">Leader's Photo</label>
                                <input type="file" id="leaders_photo" name="leaders_photo" class="form-control">
                                <img id="leader_photo_preview" alt="Leader Photo" class="img-fluid rounded mt-2" style="max-width: 150px;">
                            </div>
                        </div>

                        <h5 class="mt-3">Members</h5>
                        <div id="edit-members-container">
                            <!-- Member forms will be dynamically added here -->
                        </div>
                        <button type="button" class="btn btn-primary mt-2" id="edit-add-member">Add Member</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    var addMemberBtn = document.getElementById('edit-add-member');
    if (addMemberBtn) {
        addMemberBtn.addEventListener('click', function () {
            var memberForms = document.getElementsByClassName('member-form');
            if (memberForms.length < 10) {
                var newMemberForm = `
                    <div class="form-row row member-form">
                        <div class="col-md-4 mb-3">
                            <label for="member_name">Full Name</label>
                            <input type="text" class="form-control" name="member_name[]">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="member_birthdate">Birthdate</label>
                            <input type="date" class="form-control" name="member_birthdate[]">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="member_contact">Contact Number</label>
                            <input type="tel" class="form-control" name="member_contact[]" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="member_precinct">Precinct No.</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">#</span>
                                </div>
                                <input type="text" class="form-control" name="member_precinct[]">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-member">Remove</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="member_photo">Member Photo</label>
                            <input type="file" class="form-control" name="member_photo[]">
                        </div>
                    </div>`;
                document.getElementById('edit-members-container').insertAdjacentHTML('beforeend', newMemberForm);
            } else {
                alert('Maximum number of members reached!');
            }
        });
    } else {
        console.error('Element with ID "edit-add-member" not found.');
    }

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-member')) {
            event.target.closest('.member-form').remove();
        }
    });
});
</script>
