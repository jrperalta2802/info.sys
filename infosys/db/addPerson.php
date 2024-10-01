<!-- Add Modal -->
 <style>
   .star{
    color: red
  }
 </style>
<div class="modal fade" id="leaderAddModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Leader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveLeader" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="errorMessage" class="alert alert-warning d-none"></div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="barangay">Barangay</label>
                                <span class="star">*</span>
                                <input type="text" class="form-control" id="barangay" name="barangay" list="barangay-list" required>
                                <datalist id="barangay-list">
                                    <?php include 'includes/brgy_opt.php'; ?>
                                </datalist>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="contact_number">Contact Number</label>
                                <span class="star">*</span>
                                <input type="tel" class="form-control" id="contact_number" name="contact_number" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="precint_no">Precinct No.</label>
                                <span class="star">*</span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">#</span>
                                    </div>
                                    <input type="text" class="form-control" id="precint_no" name="precint_no" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="full_name">Full Name</label>
                                <span class="star">*</span>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="birthdate">Birthdate</label>
                                <span class="star">*</span>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required >
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="age">Age</label>
                                <span class="star">*</span>
                                <input type="number" class="form-control" id="age" name="age" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address">Complete Address</label>
                                <span class="star">*</span>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="civil_status">Civil Status</label>
                                <span class="star">*</span>
                                <select class="form-control" id="civil_status" name="civil_status" required>
                                    <option disabled selected value>----</option>
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="sex">Sex</label>
                                <span class="star">*</span>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option disabled selected value>----</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="leaders_photo">Leader Photo</label>
                                <span class="star">*</span>
                                <input type="file" class="form-control" id="leaders_photo" name="leaders_photo" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3>Members</h3>
                                <button type="button" id="add-member" class="btn btn-success">Add Member</button>
                            </div>
                            <div id="members-container">
                                <div class="form-row row member-form">
                                    <div class="col-md-4 mb-3">
                                        <label>Full Name</label>
                                        <span class="star">*</span>
                                        <input type="text" class="form-control" name="member_name[]" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Birthdate</label>
                                        <span class="star">*</span>
                                        <input type="date" class="form-control" name="member_birthdate[]" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label>Contact Number</label>
                                        <span class="star">*</span>
                                        <input type="tel" class="form-control" name="member_contact[]" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Precinct No.</label>
                                        <span class="star">*</span>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">#</span>
                                            </div>
                                            <input type="text" class="form-control" name="member_precinct[]" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger remove-member">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Member Photo</label>
                                        <span class="star">*</span>
                                        <input type="file" class="form-control" name="member_photo[]">
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
                             <span class="star">*</span>
                            <input type="text" class="form-control" name="member_name[]" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="member_birthdate">Birthdate</label>
                             <span class="star">*</span>
                            <input type="date" class="form-control" name="member_birthdate[]" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="member_contact">Contact Number</label>
                             <span class="star">*</span>
                            <input type="tel" class="form-control" name="member_contact[]" placeholder="09XX-XXX-XXXX" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="member_precinct">Precinct No.</label>
                             <span class="star">*</span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">#</span>
                                </div>
                                <input type="text" class="form-control" name="member_precinct[]" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-member">Remove</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="member_photo">Member Photo</label>
                             <span class="star">*</span>
                            <input type="file" class="form-control" name="member_photo[]">
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
