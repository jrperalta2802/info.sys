<!-- Add Leader -->
<div class="modal fade" id="leaderAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Leader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveLeader" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="errorMessage" class="alert alert-warning d-none"></div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="barangay">Barangay</label>
                                <input type="text" class="form-control" id="barangay" name="barangay" list="barangay-list">
                                <datalist id="barangay-list">
                                    <?php include 'includes/brgy_opt.php'; ?>
                                </datalist>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="contact_number">Contact Number</label>
                                <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="precint_no">Precinct No.</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="text" class="form-control" id="precint_no" name="precint_no">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="birthdate">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="age">Age</label>
                                <input type="number" class="form-control" id="age" name="age">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address">Complete Address</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="civil_status">Civil Status</label>
                                <select class="form-control" id="civil_status" name="civil_status">
                                    <option disabled selected value>----</option>
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="sex">Sex</label>
                                <select class="form-control" id="sex" name="sex">
                                    <option disabled selected value>----</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="leaders_photo">Leader Photo</label>
                                <input type="file" class="form-control-file" id="leaders_photo" name="leaders_photo">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>Members</h3>
                                <button type="button" id="add-member" class="btn btn-success">Add Member</button>
                            </div>
                            <div id="members-container">
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
                                        <input type="file" class="form-control-file" name="member_photo[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('add-member').addEventListener('click', function () {
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
                            <input type="file" class="form-control-file" name="member_photo[]">
                        </div>
                    </div>`;
                document.getElementById('members-container').insertAdjacentHTML('beforeend', newMemberForm);
            } else {
                alert('Maximum number of members reached!');
            }
        });

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-member')) {
                event.target.closest('.member-form').remove();
            }
        });
    });
</script>
