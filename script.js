$(document).ready(function () {    
  let fetchInterval;
  
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
          $("#tableBody").empty();

          const data = res.data ?? [];

          console.log("data ", data);

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

  fetchTasks();
  startFetching(3600000);
});
