<?php	
	$mysqli = new mysqli('localhost','mojuser','mojpassword','prvadb');	
	

	if (isset($_POST['register'])) {

		$loginNExist = $mysqli->query("SELECT login FROM osoby WHERE login = '".$_POST['login']."'")->fetch_row();

		if($loginNExist){
			?>
			 <div class="info">Login existuje!</div>
			<?php
		}

		if(!$loginNExist){

			$mysqli->query("INSERT INTO osoby (login,heslo,meno,priezvisko,email) VALUES ('". $_POST['login'] ."','". md5($_POST['password']) ."','". $_POST['meno'] ."','". $_POST['priezvisko'] ."','". $_POST['email'] ."')");

			header("Location: http://147.175.99.81/index.php");
		}	
	}

	if (isset($_POST['loginInput']))
	{
		$id = $mysqli->query("SELECT id FROM osoby WHERE login = '".$_POST['login']."'")->fetch_row();
		$log = $mysqli->query("SELECT login FROM osoby WHERE login = '".$_POST['loginInput']."'")->fetch_row();

		if($log[0]){

			$pass = $mysqli->query("SELECT heslo FROM osoby WHERE login = '".$_POST['loginInput']."'")->fetch_row();
			
			echo $pass[0] . "|";
			echo md5($_POST['passwordInput']);
			if ($pass[0] == md5($_POST['passwordInput']) ){				

				$mysqli->query("INSERT INTO osoby_detail (id,login_time,login_type,login) VALUES ('".$id[0]."','".date("Y-m-d h:i:sa")."','Registracia','". $_POST['loginInput'] ."')");

				header("Location: http://147.175.99.81/menu.php?login=". $_POST['loginInput'] . "&pass=" . md5($_POST['passwordInput']) );
			}
			
			else {
				?>
				<div class="info">Zle heslo!</div>
				<?php
			}
		}

		if(!$log[0]){
			?>
			<div class="logNE">Login neexistuje!</div>
			<?php
			}
	}

	if (isset($_GET['register']))
	{	
		?>
		<form method="post" action="" id="registerForm">
			<table id="registerTable">
				<tr><td>Login <input type="text" name="login" autofocus required><br></td></tr>	
				<tr><td>Password <input type="password" name="password" required><br></td></tr>
				<tr><td>Meno <input type="text" name="meno" required><br></td></tr>	
				<tr><td>Priezvisko <input type="text" name="priezvisko" required><br></td></tr>	
				<tr><td>Email <input type="text" name="email" required><br></td></tr>	
			</table>
			<button type="submit" name="register";>Ulozit</button>
		</form> 
		<?php
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
		body {
    		background-image: url("Wallpaper.jpg");
    		background-repeat: no-repeat;
    		background-size: 100%;
		}
		#loginForm{
			background-color: #E0DEDE;	
		}

		#registerForm{
			float: right;
		}

		#registerTable{
			background-color: #E0DEDE;	
		}

		a {
			color: red;
		}

		.info {
			border: 1px solid;
			margin: 180px 0px;
			padding:10px 15px 10px 10px;
			background-repeat: no-repeat;
			float: right;
			color: white;
			background-color: #00529B;
		}
		.logNE {
			border: 1px solid;
			padding:10px 15px 10px 10px;
			background-repeat: no-repeat;
			float: left;
			width: 100px;
			height: 30px;
			color: white;
			background-color: red;
		}
	</style>

</head>
<body>
	<?php
	if (!isset($_SESSION['name'])){
		session_start();	
		?>
		<center>
			<form method="post" action="" style="center margin:0 auto;">
				<table id="loginForm">
					<tr>
						<td> Login <input type="text" name="loginInput"><br></td>
						<td> Password <input type="password" name="passwordInput"><br></td>
					</tr>
				</table>
				<button type="submit" name="login">Prihlasit sa</button>
				<button type="submit" formmethod="get" name="register">Registruj sa</button>
			</form>
		</center>
	<?php
	

	
	require_once ('libraries/Google/autoload.php');
	########## Google Settings.Client ID, Client Secret from https://console.developers.google.com #############
	$client_id = '747575388619-opre31nuli7bpc3fdg82h4qkv89etbpo.apps.googleusercontent.com'; 
	$client_secret = 'tv_cYOqD9AVowXlRSLKMy1fz';
	$redirect_uri = 'http://147.175.99.81.nip.io/index.php';

	########## MySql details  #############
	$db_username = "mojuser"; //Database Username
	$db_password = "mojpassword"; //Database Password
	$host_name = "localhost"; //Mysql Hostname
	$db_name = 'prvadb'; //Database Name
	###################################################################

	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	$client->addScope("email");
	$client->addScope("profile");

	$service = new Google_Service_Oauth2($client);

	//If code is empty, redirect user to google authentication page for code.
	//Code is required to aquire Access Token from google
	//Once we have access token, assign token to session variable
	//and we can redirect user back to page and login.
	if (isset($_GET['code'])) {
	  $client->authenticate($_GET['code']);
	  $_SESSION['access_token'] = $client->getAccessToken();
	  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	  exit;
	}

	//if we have access_token continue, or else get login URL for user
	if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	  $client->setAccessToken($_SESSION['access_token']);
	} else {
	  $authUrl = $client->createAuthUrl();
	}

	//Display user info or display login url as per the info we have.
	echo '<div style="margin:20px">';
	if (isset($authUrl)){ 
	    //show login url
	    echo '<div align="center">';
	    echo '<a class="login" href="' . $authUrl . '"><img src="images/google-login-button.png" /></a>';
	    echo '</div>';
	    
	} else {
	    
	    $user = $service->userinfo->get(); //get user info 
	    

	    if ($mysqli->connect_error) {
	        die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	    }
	    
	    //check if user exist in database using COUNT
	    $result = $mysqli->query("SELECT COUNT(google_id) as usercount FROM google_users WHERE google_id=$user->id");
	    $user_count = $result->fetch_object()->usercount; //will return 0 if user doesn't exist
	    
	    //show user picture
	    echo '<img src="'.$user->picture.'" style="float: right;margin-top: 33px;" />';
	    
	    if($user_count) 
	    {
	        echo 'Welcome back '.$user->name.'! [<a href="'.$redirect_uri.'?logout=1">Log Out</a>]';
	    }
	    else 
	    { 

	    	$existuj = $mysqli->query("SELECT login FROM osoby where login= '" . $user->email . "'")->fetch_row();

	    	if (!$existuj[0]){
	        	$statement =  $mysqli->query("INSERT INTO osoby (login,meno,email,heslo) VALUES ('". $user->email ."','". $user->name ."','". $user->email ."','". $user->id ."') ");
	        }
	        $mysqli->query("INSERT INTO osoby_detail (login_time,login_type,login) VALUES ('".date("Y-m-d h:i:sa")."','Google','". $user->email ."')");
	        header("Location: http://147.175.99.81/menu.php?login=". $user->email . "&pass=" . $user->id);

	    }
	    
	    //print user details
	    echo '<pre>';
	    print_r($user);
	    echo '</pre>';
	}
	echo '</div>';

	}
	if (isset($_SESSION['name'])){
		header("Location: http://147.175.99.81/menu.php?login=". $_SESSION['name'] . "&pass=" . $_SESSION['password'] );
	}
	?>


</body>
</html>