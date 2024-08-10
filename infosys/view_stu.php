<!-- View Leader Modal -->
<div class="modal fade" id="leaderViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Leader</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img id="view_leader_photo" src="" alt="Leader Photo" class="img-fluid rounded" style="max-width: 150px;">
                </div>
                <div class="mb-3">
                    <label for="">Barangay</label>
                    <p id="view_barangay" class="form-control"></p>
                </div>
                <div class="mb-3">
                    <label for="">Full Name</label>
                    <p id="view_full_name" class="form-control"></p>
                </div>

                <div class="mb-3">
                    <label for="">Precinct No.</label>
                    <p id="view_precint_no" class="form-control"></p>
                </div>
                

                <div class="mt-4">
                    <h5>Members</h5>
                    <table id="membersTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Full Name</th>
                                <th>Birthdate</th>
                                <th>Contact Number</th>
                                <th>Precinct</th>
                            </tr>
                        </thead>
                        <tbody id="membersTableBody">
                            <!-- Members will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
