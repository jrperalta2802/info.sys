<!-- Edit Reports Modal -->
<div class="modal fade" id="leaderEditModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Edit Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateLeader" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div id="errorMessage" class="alert alert-warning d-none"></div>
                        <input type="hidden" id="leader_id" name="leader_id">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="edit_barangay">Full Name</label>
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
                                <label for="edit_precint_no">Assistance</label>
                                <input type="text" id="edit_precint_no" name="precint_no" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_full_name">Date</label>
                                <input type="text" id="edit_full_name" name="full_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="edit_birthdate">Time</label>
                                <input type="date" id="edit_birthdate" name="birthdate" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for="edit_age">Comments</label>
                                <input type="text" id="edit_age" name="comments" class="form-control">
                            </div>
                        </div>
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
  


</script>
