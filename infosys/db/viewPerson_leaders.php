<!-- View Leader Modal -->
<div class="modal fade" id="leaderViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-sm border-0">
            <!-- Modal Header -->
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold text-dark">Leader Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4">
                <!-- Leader Photo and QR Code -->
                <div class="text-center mb-4">
                    <img id="view_leader_photo" src="" alt="Leader Photo" class="img-thumbnail shadow-sm" style="width: 120px; height: 120px;">
                </div>

                <!-- Leader Details -->
                <p class="text-center text-muted mb-4"><strong>Leader UID:</strong> <span id="view_uid" class="text-primary fw-semibold"></span></p>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-secondary">Barangay</label>
                        <p id="view_barangay" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary">Full Name</label>
                        <p id="view_full_name" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-secondary">Contact Number</label>
                        <p id="view_contact_number" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-secondary">Precinct No.</label>
                        <p id="view_precint_no" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label text-secondary">Address</label>
                        <p id="view_address" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-secondary">Civil Status</label>
                        <p id="view_civil_status" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-secondary">Birthdate</label>
                        <p id="view_birthdate" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label text-secondary">Age</label>
                        <p id="view_age" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-secondary">Sex</label>
                        <p id="view_sex" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                </div>
            <!-- Report Assistance List Section -->
            <div class="mt-4">
                <h5 class="text-dark fw-bold mb-4 border-bottom pb-2">Reports for Assistance</h5>
                <div class="table-responsive shadow-sm rounded border">
                    <table id="reportsTable" class="table table-striped table-hover table-bordered text-center align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary fw-bold">
                                <th class="py-3" style="width: 15%;">Date</th>
                                <th class="py-3" style="width: 15%;">Time</th>
                                <th class="py-3" style="width: 25%;">Type of Assistance</th>
                                <th class="py-3" style="width: 35%;">Comments</th>
                                <th class="py-3" style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reportsTableBody">
                            <!-- JavaScript Populated Content -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Report for Vote-->
            <div class="mt-4">
                <h5 class="text-dark fw-bold mb-4 border-bottom pb-2">Reports for Vote</h5>
                <div class="table-responsive shadow-sm rounded border">
                    <table id="votesTable" class="table table-striped table-hover table-bordered text-center align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary fw-bold">
                                <th class="py-3" style="width: 15%;">Date</th>
                                <th class="py-3" style="width: 15%;">Time In</th>
                                <th class="py-3" style="width: 15%;">Time Out</th>
                                <th class="py-3" style="width: 25%;">Barangay</th>
                                <th class="py-3" style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="votesTableBody">
                            <!-- JavaScript Populated Content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <!-- Modal Footer -->
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
