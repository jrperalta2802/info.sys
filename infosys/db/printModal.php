<!-- Print ID Modal -->
<div class="modal fade" id="printIDBtn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print ID</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img id="print_qr_code" src="" alt="QR Code" class="img-fluid rounded" style="max-width: 150px;">
                </div>
                <p><strong>Name:</strong> <span id="print_name"></span></p>
                <p><strong>Address:</strong> <span id="print_address"></span></p>
                <p><strong>Unique ID Number:</strong> <span id="print_uid"></span></p>
                <p><strong>Precinct Number:</strong> <span id="print_precinct_no"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="printButton">Print ID</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('printButton').addEventListener('click', function() {
        window.print();
    });
</script>
