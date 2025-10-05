$(document).ready(function () {
  let fetchInterval;
  let taskData = [];

  const startFetching = (timeInMs) => {
    if (fetchInterval) {
      clearInterval(fetchInterval);
    }

    fetchInterval = setInterval(fetchTasks, timeInMs);
  };

  const fetchTasks = () => {
    $.ajax({
      url: "server.php",
      type: "GET",
      datatype: "json",
      success: function (res) {
        if (res.success) {
          taskData = res.data ?? [];

          loadTasks(taskData);
        } else {
          Swal.fire({
            title: "Error!",
            text: res.message,
            icon: "error",
          });
        }
      },
      error: function (xhr, status, error) {
        Swal.fire({
          title: "Error",
          text: error,
          icon: "error",
          confirmButtonText: "OK",
        });
      },
    });
  };

  const filterTasks = () => {
    const filterText = $("#filterText").val().toLowerCase().trim();

    // console.log("filterText ", filterText);

    const filtered = taskData.filter((el) => {
      return (
        el.task.toLowerCase().includes(filterText) ||
        el.title.toLowerCase().includes(filterText) ||
        el.description.toLowerCase().includes(filterText)
      );
    });

    console.log("filtered ", filtered);

    loadTasks(filtered);
  };

  const loadTasks = (data) => {
    $("#tableBody").empty();

    // console.log("data ", data);

    data.forEach((el, i) => {
      $("#tableBody").append(`
                    <tr>
                        <th>${i + 1}</th>
                        <th>${el.task}</th>
                        <th>${el.title}</th>
                        <th>${el.description}</th>
                        <th style="color: ${el.colorCode}">${el.colorCode}</th>
                    </tr>    
                `);
    });
  };

  $("#filterTable").click(filterTasks);

  fetchTasks();
  startFetching(3600000);

  function readImage($input) {
    const file = $input[0].files[0];

    // console.log('file ', file);
    
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $("#imagePreview").attr("src", e.target.result).show();
      };
      reader.readAsDataURL(file);
    } else {
      $("#imagePreview").hide();
    }
  }

  $("#imageInput").on("change", function () {
    readImage($(this));
  });
});
