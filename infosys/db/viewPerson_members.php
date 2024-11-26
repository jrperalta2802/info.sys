<div class="modal fade" id="memberViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-sm border-0">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold text-dark">Member Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <p class="text-center text-muted mb-4">
                    <strong>Member UIDM:</strong> <span id="members_view_uid" class="text-primary fw-semibold"></span>
                </p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-secondary">Full Name</label>
                        <p id="members_view_full_name" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-secondary">Birthdate</label>
                        <p id="members_view_birthdate" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-secondary">Contact Number</label>
                        <p id="members_view_contact_number" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-secondary">Precinct No.</label>
                        <p id="members_view_precint_no" class="form-control-plaintext border rounded px-2 py-1 bg-light"></p>
                    </div>
                </div>

                <!-- Reports for Assistance -->
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
                            <tbody id="members_reportsTableBody">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Reports for Vote -->
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
                            <tbody id="members_votesTableBody">
                                <!-- Populated by JavaScript -->
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
