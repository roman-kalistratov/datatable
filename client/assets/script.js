$(document).ready(function () {
  //   $("#myToast").toast({ delay: 3000 }); // Delay in milliseconds

  // Initial DataTable
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
      $("#addModal").data("keyText", data.base); // Store the id for update/delete

      // Open the modal window
      $("#addModal").modal("show");
    }
  });

  // Handling the data addition form submission
  $("#addDataForm").on("submit", function (event) {
    event.preventDefault();
    const keyId = $("#addModal").data("id"); // Get the id;
    const keyText = $("#addModal").data("keyText"); // Get the id;

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
            table.ajax.reload();

            // toast
            toastOn(`KEY id: ${keyId} - ${keyText} updated successfully`);

            $("#addModal").modal("hide");
          }
        }
      );
    } else {
      $.post("../server/addData.php", formData)
        .done(function (response) {
          try {
            if (typeof response === "string") {
              response = JSON.parse(response);
            }

            table.ajax.reload();

            // toast
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

  $("#deleteButton").on("click", function () {
    $("#confirmDeleteModal").modal("show"); // Show the confirmation modal for deletion
  });

  // Handling click on the delete button
  $("#confirmDeleteBtn").on("click", function () {
    const id = $("#addModal").data("id");
    $.post("../server/deleteData.php", { id: id })
      .done(function (response) {
        if (typeof response === "string") {
          response = JSON.parse(response);
        }
        if (response.status === "success") {
          console.log(response);
          // toast
          toastOn(`KEY deleted successfully`);
          table.ajax.reload(); // Reload the table
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
  $("#createCache").on("click", function () {
    $.post("../server/createCache.php")
      .done(function (response) {
        try {
          // Если сервер вернул JSON, пытаемся его распарсить
          const jsonResponse =
            typeof response === "string" ? JSON.parse(response) : response;

          if (jsonResponse.status === "success") {
            console.log("success added cache");
          } else {
            // Если сервер вернул ошибку
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
        console.error("Error adding data:", error);
        alert("Error creating cache. Please try again.");
      });
  });

  // toast on
  function toastOn(keyText) {
    $("#toast .toast-body").text(keyText);
    $("#toast").toast("show"); // Show the toast
  }
});
