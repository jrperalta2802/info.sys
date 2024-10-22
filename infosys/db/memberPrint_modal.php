<!-- Member Print ID Modal -->
<div class="modal fade" id="memberPrintIdModal" tabindex="-1" aria-labelledby="memberPrintIdModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: none; width: 650px;">
    <div class="modal-content" style="border: none; padding: 20px;">
      <div class="modal-header">
        <h5 class="modal-title" id="memberPrintIdModalLabel">Member ID Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3 d-flex justify-content-center">
        <!-- Member ID Card Structure -->
        <div id="member-id-card" 
             style="background-image: url('db/uploads/Member_BG.jpg'); 
                    background-size: cover; 
                    color: black; 
                    padding: 10px; 
                    border-radius: 10px; 
                    width: 3.375in; 
                    height: 2.125in; 
                    text-align: left; 
                    position: relative;">
          
          <!-- Logo and Header -->
          <div style="position: absolute; top: 15px; left: 12px; display: flex; align-items: center;">
            <img src="db/uploads/logo.png" alt="Logo" style="width: 0.65in; height: auto; margin-right: 8px;">
            <h4 style="margin: 0; font-size: 0.16in;">Patuloy na Maglilingkod sa Inyo</h4>
          </div>

          <!-- Main Content -->
          <div style="display: flex; align-items: center; margin-top: 70px;">
            <!-- Member's Photo -->
            <div style="flex: 1;">
              <input type="hidden" id="print_member_photo"/>
              <img src="db/uploads/mem_placeholder.jpg" src="" alt="Member's Photo" style="width: 1in; height: 1in; border-radius: 3px; background-color: transparent;">
            </div>

            <!-- Full Name, Barangay, and Precinct -->
            <div style="flex: 2; padding-left: 10px;">
              <h3 id="print_member_full_name" style="margin: 0; font-size: 18px; white-space: normal; word-wrap: break-word; overflow: hidden;"></h3>
              <p id="print_member_barangay" style="font-size: 0.18in; margin: 3px 0;"></p>
              <p id="print_member_precinct" style="font-size: 0.15in; margin: 0;"></p> <!-- Add this line for barangay -->
              <input type="hidden" id="print_member_uid"/>
            </div>

            <!-- QR Code -->
            <div style="flex: 1; text-align: right; position: absolute; bottom: 10px; right: 10px;">
              <img id="print_member_qr_code" src="" alt="Member's QR Code" style="width: 0.58in; height: 0.58in;">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
       <p id="print_member_timestamp" style="margin-right: auto; display: inline-block;"></p>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveMemberAsPDF()">Save as PDF</button>
        <button type="button" class="btn btn-success" onclick="printMemberID()">Print</button>
      </div>
    </div>
  </div>
</div>



<script>
function saveMemberAsPDF() {
    var element = document.getElementById('member-id-card');
    var uid = document.getElementById('print_member_uid').value;

    html2canvas(element, {
        scale: 4,
        useCORS: true,
        backgroundColor: null
    }).then(function(canvas) {
        var imgData = canvas.toDataURL('image/jpeg', 1.0);

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'in',
            format: [3.375, 2.125]
        });

        pdf.addImage(imgData, 'JPEG', 0, 0, 3.375, 2.125);
        pdf.save(uid ? uid + '_member.pdf' : 'Member-ID-Card.pdf');

        // Update the timestamp in the database
        $.ajax({
            type: "POST",
            url: "db/updateTimestamp.php",
            data: { uid: uid, type: 'member' },
            success: function(response) {
                console.log(response.message);  // Should display "Timestamp updated successfully"
            },
            error: function(xhr, status, error) {
                console.error("Error updating timestamp:", status, error);
            }
        });
    });
}

function printMemberID() {
    var element = document.getElementById('member-id-card');
    var uid = document.getElementById('print_member_uid').value;

    html2canvas(element, {
        scale: 4,
        useCORS: true,
        backgroundColor: null
    }).then(function(canvas) {
        var imgData = canvas.toDataURL('image/jpeg', 1.0);

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'in',
            format: [3.375, 2.125]
        });

        pdf.addImage(imgData, 'JPEG', 0, 0, 3.375, 2.125);
        var pdfData = pdf.output('blob');
        var pdfUrl = URL.createObjectURL(pdfData);
        window.open(pdfUrl);

        // Update the timestamp in the database
        $.ajax({
            type: "POST",
            url: "db/updateTimestamp.php",
            data: { uid: uid, type: 'member' },
            success: function(response) {
                console.log(response.message);  // Should display "Timestamp updated successfully"
            },
            error: function(xhr, status, error) {
                console.error("Error updating timestamp:", status, error);
            }
        });
    });
}


</script>
