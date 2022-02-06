<?php
  include_once 'header.php';

  if (!isset($_SESSION["username"])) {
    header("location: index.php");
    exit();
  }
?>

  <script>
    window.onload = function() {

      //Config
      var updateInterval = 3000;
      var dataPoints = 5;
      var numUpdates = 0;

      //Charts
      var voltageChart = $("#voltageChart");

      //Chart Config
      var commonOptions = {
        scales: {
          xAxes: [{
            type: 'time',
            time: {
              displayFormats: {
                millisecond: 'mm:ss:SSS'
              }
            }
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        },
        legend: {display: false},
        tooltips:{
          enabled: false
        }
      };

      var voltageChartInstance = new Chart(voltageChart, {
        type: 'line',
        data: {
          datasets: [{
            label: "Volts",
            data: 0,
            fill: false,
            borderColor: "#f9f991",
            borderWidth: 1
          }]
        },
        options: Object.assign({}, commonOptions, {
          title:{
            display: true,
            text: "Voltage",
            fontsize: 24
          }
        })
      });

      function plotVoltage(voltage){
        if(voltage){
          var timestamp = new Date()
          voltageChartInstance.data.labels.push(timestamp.getHours().toString() + ":" +  timestamp.getMinutes().toString() + ":" + timestamp.getSeconds().toString());
          voltageChartInstance.data.datasets.forEach((dataset) =>{dataset.data.push(parseFloat(voltage['v']))});
          if(numUpdates > dataPoints){
            voltageChartInstance.data.labels.shift()
            voltageChartInstance.data.datasets[0].data.shift();
          }else{
            numUpdates++;
          }
          voltageChartInstance.update();
        }
      }

      function updateData() {
        $.getJSON("ardu_data.php", plotVoltage);
        setTimeout(updateData, updateInterval);
      }

      updateData();
    }
  </script>

  <section class="greetings">
    <h3>Welcome back <?php echo $_SESSION["username"] ?></h3>
  </section>

  <section class="operation">
    <div class="controls">
      <form action="write_pos" method="post">
        <input type="text" name="servo_pos" placeholder="90">
        <button type="send_pos" name="send_pos">Send Position</button>
      </form>
      <button type="light_sense" name="light_sense">Detect Best Position</button>
      <button type="automatic" name="automatic">Use Location for Positioning</button>
    </div>
    <div id="voltageDisplay" class="v">
      <canvas id="voltageChart"></canvas>
    </div>
  </section>



<?php
  include_once 'footer.php';
?>
