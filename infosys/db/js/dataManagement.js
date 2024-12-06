$(document).on("submit", "#saveLeader", function (e) {
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
      console.log("Raw response:", response);

      try {
        var res =
          typeof response === "object" ? response : jQuery.parseJSON(response);
        console.log("Parsed JSON:", res);

        if (res.status === 422) {
          $("#errorMessage").removeClass("d-none").text(res.message);
        } else if (res.status === 200) {
          $("#errorMessage").addClass("d-none");
          $("#leaderAddModal").modal("hide");
          $("#saveLeader")[0].reset();

          alertify.set("notifier", "position", "top-right");
          alertify.success(res.message);

          $("#myTable").load(location.href + " #myTable");
        } else if (res.status === 500) {
          $("#errorMessage").removeClass("d-none").text(res.message);
        } else {
          console.error("Unexpected response:", res);
          $("#errorMessage")
            .removeClass("d-none")
            .text("An unexpected error occurred. Check console for details.");
        }
      } catch (e) {
        console.error("Error parsing JSON response:", e, response);
        $("#errorMessage")
          .removeClass("d-none")
          .text(
            "An error occurred while processing the response. Check console for details."
          );
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX error:", textStatus, errorThrown);
      $("#errorMessage")
        .removeClass("d-none")
        .text("An AJAX error occurred. Check console for details.");
    },
  });
});

$(document).on("click", ".editLeaderBtn", function () {
  var leader_id = $(this).val();
  $.ajax({
    type: "GET",
    url: "db/personProcess.php?leader_id=" + leader_id,
    success: function (response) {
      var res =
        typeof response === "object" ? response : jQuery.parseJSON(response);
      if (res.status === 404) {
        alert(res.message);
      } else if (res.status === 200) {
        $("#leader_id").val(res.data.leader.id);
        $("#edit_barangay").val(res.data.leader.barangay);
        $("#edit_full_name").val(res.data.leader.full_name);
        $("#edit_contact_number").val(res.data.leader.contact_number);
        $("#edit_precint_no").val(res.data.leader.precint_no);
        $("#edit_birthdate").val(res.data.leader.birthdate);
        $("#edit_age").val(res.data.leader.age);
        $("#edit_civil_status").val(res.data.leader.civil_status);
        $("#edit_sex").val(res.data.leader.sex);
        $("#edit_address").val(res.data.leader.address);
        $("#leader_photo_preview").attr(
          "src",
          "/info.sys/infosys/db/uploads/leaders/" +
            res.data.leader.leaders_photo
        );

        $("#edit-members-container").empty();
        res.data.members.forEach(function (member) {
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
          $("#edit-members-container").append(memberForm);
        });

        $("#leaderEditModal").modal("show");
      }
    },
  });
});
$(document).on("submit", "#updateLeader", function (e) {
  e.preventDefault();
  var formData = new FormData(this);
  formData.append("update_leader", true);

  $.ajax({
    type: "POST",
    url: "db/personProcess.php",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      console.log(response);
  
      if (response.status === 422) {
          $("#errorMessageUpdate").removeClass("d-none").text(response.message);
      } else if (response.status === 200) {
          $("#errorMessageUpdate").addClass("d-none");
          alertify.set("notifier", "position", "top-right");
          alertify.success(response.message);
  
          // Close modal and reset form
          $("#leaderEditModal").modal("hide");
          $("#updateLeader")[0].reset();
  
          // Redraw the DataTable
          $('#myTable').DataTable().ajax.reload(null, false); // Reload table without resetting pagination
      } else if (response.status === 500) {
          $("#errorMessageUpdate").removeClass("d-none").text(response.message);
      }
  },
  
  });
});

$(document).on("click", ".viewLeaderBtn", function () {
  var leader_id = $(this).val();
  $.ajax({
    type: "GET",
    url: "db/personProcess.php?leader_id=" + leader_id,
    success: function (response) {
      var res =
        typeof response === "object" ? response : jQuery.parseJSON(response);
      if (res.status == 404) {
        alert(res.message);
      } else if (res.status == 200) {
        // Populate leader details
        $("#view_leader_photo").attr(
          "src",
          "/info.sys/infosys/db/uploads/leaders/" +
            res.data.leader.leaders_photo
        );
        $("#view_barangay").text(res.data.leader.barangay);
        $("#view_full_name").text(res.data.leader.full_name);
        $("#view_precint_no").text(res.data.leader.precint_no);
        $("#view_contact_number").text(res.data.leader.contact_number);
        $("#view_address").text(res.data.leader.address);
        $("#view_birthdate").text(res.data.leader.birthdate);
        $("#view_age").text(res.data.leader.age);
        $("#view_civil_status").text(res.data.leader.civil_status);
        $("#view_sex").text(res.data.leader.sex);
        $("#view_uid").text(res.data.leader.UID); // Display leader UID

        // Populate Reports for Assistance table
        var reportsTableBody = $("#reportsTableBody");
        reportsTableBody.empty(); // Clear existing rows

        if (res.data.reports_help.length === 0) {
          // Add placeholder row if no data is available
          var placeholderRow = `
        <tr>
            <td colspan="5" class="text-center text-dark fw-bold">No data available in table</td>
        </tr>`;
          reportsTableBody.append(placeholderRow);
        } else {
          res.data.reports_help.forEach(function (report) {
            var row =
              "<tr>" +
              "<td>" +
              report.date +
              "</td>" +
              "<td>" +
              report.time +
              "</td>" +
              "<td>" +
              report.assistance +
              "</td>" +
              "<td>" +
              report.comments +
              "</td>" +
              "<td>" +
              '<button type="button" class="btn btn-danger btn-sm delete-report-btn" data-container="body" data-report-id="' +
              report.id +
              '"><i class="fa fa-trash" ></i></button></td>' +
              "</tr>";
            reportsTableBody.append(row);
          });
        }

        // Populate Reports for Vote table
        var votesTableBody = $("#votesTableBody");
        votesTableBody.empty(); // Clear existing rows

        if (res.data.votes.length === 0) {
          // Add placeholder row if no data is available
          var placeholderRow = `
          <tr>
              <td colspan="5" class="text-center text-dark fw-bold">No data available in table</td>
          </tr>`;
          votesTableBody.append(placeholderRow);
        } else {
          res.data.votes.forEach(function (vote) {
            var row = `
              <tr>
                <td>${vote.date}</td>
                <td>${vote.time_in}</td>
                <td>${vote.time_out}</td>
                <td>${vote.barangay}</td>
                <td>
                  <button type="button" class="btn btn-danger btn-sm viewLeaderBtn" value="${vote.id}"><i class="fa fa-trash" ></i></button>
                </td>
              </tr>`;
            votesTableBody.append(row);
          });
        }

        // Show the modal
        $("#leaderViewModal").modal("show");
      }
    },
  });
});

$(document).on("click", ".generateQRBtn", function () {
  var leader_id = $("#view_uid").text(); // Get the leader UID from the modal
  $.ajax({
    type: "GET",
    url: "db/generateQRCode.php", // Use the unified file
    data: {
      leader_id: leader_id, // Pass leader_id as the parameter
    },
    success: function (response) {
      $("#view_leader_qr_code").attr(
        "src",
        "data:image/png;base64," + response
      );
    },
    error: function (xhr, status, error) {
      console.log("Error: " + error);
    },
  });
});

$(document).on("click", ".generateMemberQRBtn", function () {
  var member_id = $(this).data("member-id"); // Get the member UIDM from the button

  console.log("Member UIDM: " + member_id);

  $.ajax({
    type: "GET",
    url: "db/generateQRCode.php", // Use the unified file
    data: {
      member_id: member_id, // Pass member_id as the parameter
    },
    success: function (response) {
      console.log(response); // Log the response to check for any issues
      $("#view_qr_code").attr("src", "data:image/png;base64," + response);
    },
    error: function (xhr, status, error) {
      console.log("Error: " + error);
    },
  });
});

$(document).on("click", ".deleteLeaderBtn", function (e) {
  e.preventDefault();
  if (confirm("Are you sure you want to delete this data?")) {
    var leader_id = $(this).val();
    $.ajax({
      type: "POST",
      url: "db/personProcess.php",
      data: {
        delete_leader: true,
        leader_id: leader_id,
      },
      success: function (response) {
        var res =
          typeof response === "object" ? response : jQuery.parseJSON(response);
        if (res.status === 500) {
          $("#errorMessage").removeClass("d-none").text(res.message);
        } else if (res.status === 200) {
          $("#errorMessage").addClass("d-none");
          alertify.set("notifier", "position", "top-right");
          alertify.success(res.message);
          $("#myTable").load(location.href + " #myTable");
        }
      },
    });
  }
});

function populateLeaderIDModal(id) {
  var fullNameElem = document.getElementById("print_leader_full_name");
  var precinctElem = document.getElementById("print_leader_precinct");
  var photoElem = document.getElementById("print_leader_photo");
  var qrCodeElem = document.getElementById("print_leader_qr_code");
  var barangayElem = document.getElementById("print_leader_barangay");
  var timestampElem = document.getElementById("print_leader_timestamp"); // Element to display the timestamp
  var uidElem = document.getElementById("print_leader_uid"); // Hidden input for UID

  if (
    !fullNameElem ||
    !precinctElem ||
    !photoElem ||
    !qrCodeElem ||
    !barangayElem ||
    !timestampElem ||
    !uidElem
  ) {
    console.error(
      "Modal elements not found. Check if modal is loaded correctly."
    );
    return;
  }

  // Reset the modal content
  fullNameElem.innerText = "";
  precinctElem.innerText = "";
  barangayElem.innerText = "";
  photoElem.src = "";
  qrCodeElem.src = "";
  timestampElem.innerText = ""; // Reset timestamp field
  uidElem.value = ""; // Reset the hidden UID input

  // Fetch leader data via AJAX
  $.ajax({
    type: "GET",
    url: `db/printDataHandler.php?leader_id=${id}`,
    dataType: "json",
    success: function (res) {
      if (res.status === 500) {
        alert(res.message);
      } else {
        // Populate leader data
        fullNameElem.innerText = res.full_name;
        precinctElem.innerText = res.precinct;
        barangayElem.innerText = res.barangay;
        photoElem.src = res.photo;

        // Set the UID value to the hidden input field
        uidElem.value = res.uid;

        // Display the timestamp if available
        if (res.printLeader_timestamp) {
          timestampElem.innerText =
            "Last printed: " + res.printLeader_timestamp;
        } else {
          timestampElem.innerText = "Not yet printed.";
        }

        // Fetch and set leader QR code
        $.ajax({
          type: "GET",
          url: `db/generateQRCode.php?leader_id=${id}`,
          success: function (qrResponse) {
            qrCodeElem.src = "data:image/png;base64," + qrResponse;
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error fetching QR code:", textStatus, errorThrown);
          },
        });

        // Show the modal
        $("#leaderPrintIdModal").modal("show");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("An error occurred while fetching leader data.");
      console.error("Error fetching leader data:", textStatus, errorThrown);
    },
  });
}

// Function to handle updating the timestamp when printing or saving as PDF for leaders
function updateLeaderTimestamp(id) {
  $.ajax({
    type: "POST",
    url: "db/updateTimestamp.php", // Assuming this is your PHP file to update the timestamp
    data: { uid: id, type: "leader" }, // Pass the leader ID and type as "leader"
    success: function (response) {
      console.log(response.message);
      if (response.status === 200) {
        // Optionally, you can update the timestamp displayed in the modal here
        var timestampElem = document.getElementById("print_leader_timestamp");
        timestampElem.innerText = "Printed on: " + new Date().toLocaleString();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error updating timestamp:", textStatus, errorThrown);
    },
  });
}

function populateMemberIDModal(id) {
  var fullNameElem = document.getElementById("print_member_full_name");
  var precinctElem = document.getElementById("print_member_precinct");
  var photoElem = document.getElementById("print_member_photo");
  var qrCodeElem = document.getElementById("print_member_qr_code");
  var barangayElem = document.getElementById("print_member_barangay");
  var timestampElem = document.getElementById("print_member_timestamp"); // Element to display the timestamp
  var uidElem = document.getElementById("print_member_uid"); // Hidden input for UID

  if (
    !fullNameElem ||
    !precinctElem ||
    !photoElem ||
    !qrCodeElem ||
    !barangayElem ||
    !timestampElem ||
    !uidElem
  ) {
    console.error(
      "Modal elements not found. Check if modal is loaded correctly."
    );
    return;
  }

  // Reset the modal content
  fullNameElem.innerText = "";
  precinctElem.innerText = "";
  barangayElem.innerText = "";
  photoElem.src = "";
  qrCodeElem.src = "";
  timestampElem.innerText = ""; // Reset timestamp field
  uidElem.value = ""; // Reset the hidden UID input

  // Fetch member data via AJAX
  $.ajax({
    type: "GET",
    url: `db/printDataHandler.php?member_id=${id}`,
    dataType: "json",
    success: function (res) {
      if (res.status === 500) {
        alert(res.message);
      } else {
        // Populate member data
        fullNameElem.innerText = res.full_name;
        precinctElem.innerText = res.precinct;
        barangayElem.innerText = res.barangay;
        photoElem.src = res.photo;

        // Set the UID value to the hidden input field
        uidElem.value = res.uid;

        // Display the timestamp if available
        if (res.printMember_timestamp) {
          timestampElem.innerText =
            "Last printed: " + res.printMember_timestamp;
        } else {
          timestampElem.innerText = "Not yet printed.";
        }

        // Fetch and set member QR code
        $.ajax({
          type: "GET",
          url: `db/generateQRCode.php?member_id=${id}`,
          success: function (qrResponse) {
            qrCodeElem.src = "data:image/png;base64," + qrResponse;
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error("Error fetching QR code:", textStatus, errorThrown);
          },
        });

        // Show the modal
        $("#memberPrintIdModal").modal("show");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("An error occurred while fetching member data.");
      console.error("Error fetching member data:", textStatus, errorThrown);
    },
  });
}

// Function to handle updating the timestamp when printing or saving as PDF for members
function updateMemberTimestamp(id) {
  $.ajax({
    type: "POST",
    url: "db/updateTimestamp.php", // Assuming this is your PHP file to update the timestamp
    data: { uid: id, type: "member" }, // Pass the member ID and type as "member"
    success: function (response) {
      console.log(response.message);
      if (response.status === 200) {
        // Optionally, you can update the timestamp displayed in the modal here
        var timestampElem = document.getElementById("print_member_timestamp");
        timestampElem.innerText = "Printed on: " + new Date().toLocaleString();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error updating timestamp:", textStatus, errorThrown);
    },
  });
}

function updateTimestamp(uid, type) {
  $.ajax({
    type: "POST",
    url: "db/updateTimestamp.php", // Path to your timestamp update PHP script
    data: {
      uid: uid,
      type: type,
    },
    success: function (response) {
      console.log("Timestamp updated successfully:", response);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error updating timestamp:", textStatus, errorThrown);
    },
  });
}
$(document).on("click", ".view-member-btn", function () {
  var memberUIDM = $(this).closest("tr").find("td:first").text();

  console.log("Fetching details for UIDM:", memberUIDM);

  $.ajax({
    type: "GET",
    url: "db/personProcess.php?UIDM=" + memberUIDM,
    success: function (response) {
      console.log("Response:", response);
      var res =
        typeof response === "object" ? response : jQuery.parseJSON(response);

      if (res.status === 404) {
        alert(res.message);
      } else if (res.status === 200) {
        console.log("Populating modal fields...");

        // Test multiple methods for updating the DOM
        let contactField = document.getElementById(
          "members_view_contact_number"
        );

        console.log(
          "Before update - native textContent:",
          contactField.textContent
        );
        contactField.textContent = res.data.member.contact || "N/A"; // Native update
        console.log(
          "After update - native textContent:",
          contactField.textContent
        );

        // Update the modal fields
        $("#members_view_uid").text(res.data.member.UIDM || "N/A");
        $("#members_view_full_name").text(res.data.member.full_name || "N/A");
        $("#members_view_contact_number").text(
          res.data.member.contact || "N/A"
        );
        $("#members_view_birthdate").text(res.data.member.birthdate || "N/A");
        $("#members_view_precint_no").text(res.data.member.precinct || "N/A");

        // Delay modal display to ensure updates
        setTimeout(() => {
          $("#memberViewModal").modal("show");
        }, 100);

        // Populate Reports for Assistance table
        var reportsTableBody = $("#members_reportsTableBody");
        reportsTableBody.empty(); // Clear existing rows

        if (res.data.reports_help.length === 0) {
          reportsTableBody.append(`
            <tr>
              <td colspan="5" class="text-center text-muted">No Reports Available</td>
            </tr>
          `);
        } else {
          res.data.reports_help.forEach(function (report) {
            var row = `
              <tr>
                <td>${report.date}</td>
                <td>${report.time}</td>
                <td>${report.assistance}</td>
                <td>${report.comments}</td>
                <td>
                  <button type="button" class="btn btn-danger btn-sm delete-report-btn" data-report-id="${report.id}"><i class="fa fa-trash" ></i></button>
                </td>
              </tr>`;
            reportsTableBody.append(row);
          });
        }

        // Populate Reports for Vote table
        var votesTableBody = $("#members_votesTableBody");
        votesTableBody.empty(); // Clear existing rows

        if (res.data.votes.length === 0) {
          votesTableBody.append(`
            <tr>
              <td colspan="5" class="text-center text-muted">No Reports Available</td>
            </tr>
          `);
        } else {
          res.data.votes.forEach(function (vote) {
            var row = `
              <tr>
                <td>${vote.date}</td>
                <td>${vote.time_in}</td>
                <td>${vote.time_out}</td>
                <td>${vote.barangay}</td>
                <td>
                  <button type="button" class="btn btn-danger btn-sm" data-report-id="${vote.id}"><i class="fa fa-trash" ></i></button>
                </td>
              </tr>`;
            votesTableBody.append(row);
          });
        }

        // Ensure the modal is displayed
        $("#memberViewModal").modal("show");
      }
    },
    error: function (xhr, status, error) {
      console.error("XHR Error:", xhr);
      alert("Failed to fetch member details. Please try again.");
    },
  });
});
