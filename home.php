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
	$DATABASE_USER = 'eksamen'; 
	$DATABASE_PASS = '87654321';  
	$DATABASE_NAME = 'login_db';  

	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if ( mysqli_connect_errno() ) {

		exit('Kan ikke koble til MySQL: ' . mysqli_connect_error());
	}
	
	if (isset($_POST['lagre']) and $_POST['inputfelt'] !== "") {
		$brukerid = $_SESSION['id'];
		$inputfeltdata = $_POST['inputfelt'];

		if ($con->query("SELECT * FROM texttable WHERE id = $brukerid;")->num_rows > 0) {
			$con->query("DELETE FROM texttable WHERE id = $brukerid;");
		}
		$con->query("INSERT INTO texttable (id,textdata) VALUES ($brukerid,'$inputfeltdata');");
		$con->query("INSERT INTO texttable_log (id,textdata) VALUES ($brukerid,'$inputfeltdata');");

		header("Location: home.php");
	}
	if (isset($_POST['hent'])) {
		$brukerid = $_SESSION['id'];
		$resultat = $con->query("SELECT * FROM texttable WHERE id = $brukerid;");
		$rad = $resultat->fetch_assoc();
		
		if (!$rad["textdata"] == null or !$rad["textdata"] == "") {
			$tekst = $rad["textdata"];
		}
		else {
			$tekst = "Ingenting er lagret ennå.";
		}
	}
	if (isset($_POST['hent_logg'])) {
		$brukerid = $_SESSION['id'];
		$resultat = $con->query("SELECT * FROM texttable_log WHERE id = $brukerid");
		$tekst = "Logg: \n";

		while ($rad = $resultat->fetch_assoc()) {
			$tekst = $tekst . "- " . $rad['textdata'] . "\n";
		}
	}
	if (isset($_POST['slett_logg'])) {
		$brukerid = $_SESSION['id'];
		$resultat = $con->query("DELETE FROM texttable_log WHERE id = $brukerid;");
		$tekst = "Slettet logg fra serveren.";
	}

	$con->close();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title> Hjemmeside </title>
		<link rel="stylesheet" href="style.css?v=1.0">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Nettside</h1>
				<a href="profile.php" tabindex="1"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="home.php" tabindex="2"><i class="fas fa-sign-out-alt"></i>Home</a>
				<a href="logout.php" tabindex="3"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<form action="home.php" method="post">
				<!--img src="tennis.jpeg" width="150px"></img>
				<img src="pig-tank.png"width="150px"></img>-->
				<h2> Hjemmeside </h2>
				<p> Velkommen tilbake, <?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>!</p>
				<input type="text" name="inputfelt" tabindex="4" placeholder="gi meg dine hemmeligheter"><br><br>
				<button type="submit" name="lagre" tabindex="5">lagre tekst</button> 
				<button type="submit" name="hent" tabindex="6">hent tekst</button> 
				<button type="submit" name="hent_logg" tabindex="7">hent logg</button>
				<button type="submit" name="slett_logg" tabindex="8">slett logg</button> 
				<p> <?php echo nl2br(htmlspecialchars($tekst))?></p>
			</form>  
		</div>
	</body>
</html>
