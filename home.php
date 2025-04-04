<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

// Start økt
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
$tekst = "Velkommen.";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'mohamed'; 
	$DATABASE_PASS = '87654321'; 
	$DATABASE_NAME = 'login_db'; 

	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if ( mysqli_connect_errno() ) {

		exit('Kan ikke koble til MySQL: ' . mysqli_connect_error());
	}
	
	if (isset($_POST['sigma']) and $_POST['inputfelt'] !== "") {
		$brukerid = $_SESSION['id'];
		$inputfeltdata = $_POST['inputfelt'];
	
		// MySQL spørringer
		$sql = "INSERT INTO textfield_data (id,textdata) VALUES ($brukerid,'$inputfeltdata');";
		$sql1 = "SELECT * FROM textfield_data WHERE id = $brukerid;";
		$sql2 = "DELETE FROM textfield_data WHERE id = $brukerid;";
		
		if ($con->query($sql1)->num_rows > 0) {
			$con->query($sql2);
		}
		$con->query($sql);

		header("Location: home.php");
	}
	if (isset($_POST['hent'])) {
		$brukerid = $_SESSION['id'];
		$sql1 = "SELECT * FROM textfield_data WHERE id = $brukerid;";
		$resultat = $con->query($sql1);
		$rad = $resultat->fetch_assoc();
		if (!$rad["textdata"] == null or !$rad["textdata"] == "") {
			$tekst = $rad["textdata"];
		}
		else {
			$tekst = "Ingenting er lagret ennå.";
		}
	}

	$con->close();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title> Hjemmeside </title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Nettside</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="home.php"><i class="fas fa-sign-out-alt"></i>Home</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<form action="home.php" method="post">
				<!--img src="tennis.jpeg" width="150px"></img>
				<img src="pig-tank.png"width="150px"></img>-->
				<h2> Hjemmeside </h2>
				<p> Velkommen tilbake, <?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!</p>
				<input type="text" name="inputfelt" placeholder="gi meg dine hemmeligheter"><br><br>
				<button type="submit" name="sigma">lagre tekst</button> 
				<button type="submit" name="hent">hent tekst</button> 
				<p> <?php echo $tekst?></p>
			</form>  
		</div>
	</body>
</html>
