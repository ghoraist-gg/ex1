<?php
  $content = '<div class="row">
                <div class="col-xs-12">

                  <!-- Weather Widget Start -->
                  <div class="box">
                    <div class="box-body text-center">
                      <div id="weather-widget" style="margin-bottom:20px;">
                        <strong>Weather:</strong> <span id="weather-city">Loading...</span>
                      </div>
                    </div>
                  </div>
                  <!-- Weather Widget End -->

                  <div class="box">
                    <div class="box-header">
                      <h3 class="box-title">Doctors List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <table id="doctors" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Gender</th>
                          <th>Specialist</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Gender</th>
                          <th>Specialist</th>
                          <th>Action</th>
                        </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
                </div>
              </div>';
  include('../master.php');
?>
<!-- page script -->
<script>
  const apiKey = "7bbbb47522846e8b3c26ba35c226c734";
  const city = "Paris";
  const units = "metric"; 

  fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=${units}`)
    .then(response => response.json())
    .then(data => {
      if (data.cod === 200) {
        const temp = data.main.temp;
        const desc = data.weather[0].description;
        const icon = data.weather[0].icon;
        document.getElementById('weather-city').innerHTML =
          `${city}: <img src="https://openweathermap.org/img/wn/${icon}.png" alt="${desc}" /> ${temp}°C (${desc})`;
      } else {
        document.getElementById('weather-city').innerText = "Weather data not available.";
      }
    })
    .catch(() => {
      document.getElementById('weather-city').innerText = "Weather data not available.";
    });

  $(document).ready(function(){
    $.ajax({
        type: "GET",
        url: "../api/doctor/read.php",
        dataType: 'json',
        success: function(data) {
            var response="";
            for(var user in data){
                response += "<tr>"+
                "<td>"+data[user].name+"</td>"+
                "<td>"+data[user].email+"</td>"+
                "<td>"+data[user].phone+"</td>"+
                "<td>"+((data[user].gender == 0)? "Male": "Female")+"</td>"+
                "<td>"+data[user].specialist+"</td>"+
                "<td><a href='update.php?id="+data[user].id+"'>Edit</a> | <a href='#' onClick=Remove('"+data[user].id+"')>Remove</a></td>"+
                "</tr>";
            }
            $(response).appendTo($("#doctors"));
        }
    });
  });
  function Remove(id){
    var result = confirm("Are you sure you want to Delete the Doctor Record?");
    if (result == true) {
        $.ajax(
        {
            type: "POST",
            url: '../api/doctor/delete.php',
            dataType: 'json',
            data: {
                id: id
            },
            error: function (result) {
                alert(result.responseText);
            },
            success: function (result) {
                if (result['status'] == true) {
                    alert("Successfully Removed Doctor!");
                    window.location.href = '/medibed/doctor';
                }
                else {
                    alert(result['message']);
                }
            }
        });
    }
  }
</script>
