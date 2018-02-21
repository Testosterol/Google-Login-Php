<?php
	$mysqli = new mysqli('localhost','mojuser','mojpassword','prvadb');	
	$_SESSION['name'] = $_GET['login'];
	$_SESSION['password'] = $_GET['pass'];
	$meno = $_GET['login'];

	$mysqli->query("UPDATE osoby SET je_aktivny = 1 WHERE login = '" . $meno . "'");
	echo "<H1>Vitaj " . $meno;
	echo "</H1>";

	if(isset($_POST['historia'])){
		header("Location: http://147.175.99.81/historia.php?login=". $meno);
	}
	if(isset($_POST['odhlasenie'])){
		session_unset($_SESSION['name']);
		$mysqli->query("UPDATE osoby SET je_aktivny = 0 WHERE login = '" . $meno . "'");
		session_destroy();
		header("Location: http://147.175.99.81/");

	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>3.zadanie</title>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
	<style type="text/css">
		table, th, td {
	   		border: 1px solid black;
		}
	</style>
	
</head>
<body>
	<form method='post'>
		<button type="submit" name="odhlasenie">Odhlasit sa</button>
		<button type="submit" name="historia">Minule prihlasenia</button>
	</form>
	<br>

	<?php
		$login = $_GET['login'];


	  	if (isset($_GET['login'])){
			$result = $mysqli->query("SELECT login from osoby where je_aktivny = 1");
				?>

			<table style="float: left; margin:0 auto;">
			<tr>
				<th>Prihlaseni uzivatelia</th>
			</tr>
			</br>
			<?php
				while($obj = mysqli_fetch_object($result))
				{
					echo '<tr>';
					echo "<td>$obj->login</td>";
					echo '</tr>';
				}
				echo '</table>';
			}
	?>
</body>
</html>