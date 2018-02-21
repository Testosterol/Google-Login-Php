<?php 	$mysqli = new mysqli('localhost','mojuser','mojpassword','prvadb');	?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

      <?php
      		$Reg = $mysqli->query("SELECT count(*) from osoby_detail where login_type = 'Registracia'")->fetch_row();
      		$Google = $mysqli->query("SELECT count(*) from osoby_detail where login_type = 'Google'")->fetch_row();
      		$LDAP = $mysqli->query("SELECT count(*) from osoby_detail where login_type = 'LDAP'")->fetch_row();
      ?>

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['LDAP', <?php echo $LDAP[0]; ?>],
          ['Google', <?php echo $Google[0]; ?>],
          ['Registracia', <?php echo $Reg[0]; ?>]
        ]);

        var options = {
          title: 'Typ prihlasenia'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>
<?php

	$login = $_GET['login'];

 	if (isset($_GET['login'])){
		$result = $mysqli->query("SELECT login_time ,login_type from osoby_detail where login = '" . $login . "'");
			?>

		<table style="float: left; margin:0 auto;">
	 		<tr>
				<th>Cas prihlasenia</th>
				<th>Typ prihlasenia</th>
			</tr>
			</br>
	  		<?php
			while($obj = mysqli_fetch_object($result))
			{
				echo '<tr>';
				echo "<td>$obj->login_time</td><td>$obj->login_type</td>";
				echo '</tr>';
			}
		echo '</table>';
		}
?>