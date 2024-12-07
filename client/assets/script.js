$(document).ready(function () {
  // Initial DataTable setup
  const table = $("#example").DataTable({
    paging: true,
    searching: true,
    info: true,
    ordering: true,
    ajax: {
      url: "../server/getData.php",
      dataSrc: "data",
    },
    columns: [
      { data: "id" },
      { data: "app" },
      { data: "namespace" },
      { data: "base" },
      { data: "en" },
      { data: "he" },
      { data: "ru" },
      { data: "comment" },
      { data: "code" },
    ],
    order: [[0, "desc"]],
  });

  // Handling the closing of the modal window
  $("#addModal").on("hidden.bs.modal", function () {
    $("#addDataForm")[0].reset();
    $("#submitButton").text("ADD KEY");
    $("#deleteButton").hide(); // Hide the delete button
  });

  // Handling click on a table row
  $("#example tbody").on("click", "tr", function () {
    const data = table.row(this).data();
    $("#addModalLabel").text("Edit Key");
    $("#submitButton").text("Update KEY");
    $("#deleteButton").show();

    if (data) {
      // Fill the form with the selected row's data
      $("#appSelect").val(data.app);
      $("#namespace").val(data.namespace);
      $("#key").val(data.base); // Use 'base' for the key
      $("#en").val(data.en);
      $("#he").val(data.he);
      $("#ru").val(data.ru);
      $("#comment").val(data.comment);
      $("#code").val(data.code);
      $("#addModal").data("id", data.id); // Store the id for update/delete
      $("#addModal").data("keyText", data.base); // Store the key text for notification

      // Open the modal window
      $("#addModal").modal("show");
    }
  });

  // Handling the data addition form submission
  $("#addDataForm").on("submit", function (e) {
    e.preventDefault();
    const keyId = $("#addModal").data("id"); // Get the id
    const keyText = $("#addModal").data("keyText"); // Get the key text

    // Collecting data from the form
    const formData = $(this).serialize();

    if (keyId) {
      // If the id exists, it's an update
      $.post("../server/updateData.php", formData + `&id=${keyId}`).done(
        function (response) {
          if (typeof response === "string") {
            response = JSON.parse(response);
          }
          if (response.status === "success") {
            table.ajax.reload(); // Reload the table to reflect changes
            table.search(keyId).draw(); // Optionally search for the updated row

            // Show a toast notification for update
            toastOn(`KEY id: ${keyId} - ${keyText} updated successfully`);
            $("#addModal").modal("hide");
          }
        }
      );
    } else {
      // Adding new data
      $.post("../server/addData.php", formData)
        .done(function (response) {
          try {
            if (typeof response === "string") {
              response = JSON.parse(response);
            }

            // Add the new data to the DataTable
            var newRow = response.data; // Use the new data returned from the server
            var newRowNode = table.row.add(newRow).draw().node(); // Add new row and get the row node

            // Add a class to the new row to change the background color
            $(newRowNode).addClass("highlight"); // Highlight the new row

            // Optionally reset the highlight after a timeout
            setTimeout(() => {
              $(newRowNode).removeClass("highlight"); // Remove highlight after 10 seconds
            }, 10000); // Adjust time as necessary

            // Show a toast notification for addition
            toastOn(`KEY added successfully`);
            $("#addDataForm")[0].reset();
            $("#addModal").modal("hide");
          } catch (error) {
            console.error("JSON parsing error:", error);
            alert("Error processing server response. Please try again.");
          }
        })
        .fail(function (xhr, status, error) {
          console.error("Error adding data:", error);
          alert("Error adding data. Please try again.");
        });
    }
  });

  // Handling click on the delete button
  $("#deleteButton").on("click", function () {
    $("#addDataForm")[0].reset();
    $("#addModal").modal("hide");
    $("#confirmDeleteModal").modal("show"); // Show the confirmation modal for deletion
  });

  // Confirm deletion
  $("#confirmDeleteBtn").on("click", function () {
    const id = $("#addModal").data("id");
    $.post("../server/deleteData.php", { id: id })
      .done(function (response) {
        if (typeof response === "string") {
          response = JSON.parse(response);
        }
        if (response.status === "success") {
          // Show a toast notification for deletion
          toastOn(`KEY deleted successfully`);
          table.ajax.reload(); // Reload the table to reflect changes
          $("#addModal").modal("hide"); // Close the modal window
        } else {
          alert(response.message);
        }
      })
      .fail(function (xhr, status, error) {
        console.error("Error deleting data:", error);
        alert("Error deleting data. Please try again.");
      });
    $("#confirmDeleteModal").modal("hide"); // Close the confirmation modal
  });

  // Handling click on create cache
  $("#createCache").on("click", function (e) {
    e.preventDefault();

    $.post("../server/createCache.php")
      .done(function (response) {
        try {
          const jsonResponse =
            typeof response === "string" ? JSON.parse(response) : response;

          if (jsonResponse.status === "success") {
            console.log(jsonResponse.message);
            toastOn(jsonResponse.message); // Show success message
          } else {
            console.error("Server Error:", jsonResponse.message);
            alert(
              jsonResponse.message || "Error creating cache. Please try again."
            );
          }
        } catch (error) {
          console.error("JSON parsing error:", error);
          alert("Error processing server response. Please try again.");
        }
      })
      .fail(function (xhr, status, error) {
        console.error("Error creating cache:", error);
        alert("Error creating cache. Please try again.");
      });
  });

  // Function to display toast messages
  function toastOn(keyText) {
    $("#toast .toast-body").text(keyText);
    $("#toast").toast("show"); // Show the toast
  }
});
