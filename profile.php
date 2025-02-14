<?php
// We need to use sessions, so you should always start sessions using the below  code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'hannafy';  // legg inn brukernavnet til SQL-tilgangen
$DATABASE_PASS = 'passord';  // legg inn ditt passord til SQL-tilgangen
$DATABASE_NAME = 'databasenavn';  // legg inn navnet på databasen din

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
$DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// We don't have the password or email info stored, so we get the results from the database.

// Vi trenger ikke hente brukernavn, for det har vi allerede lagret i $_SESSION!
$stmt = $con->prepare('SELECT passord, email FROM ditt_tabellnavn WHERE bruker_id = ?');

// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);  //Brukerid er et tall, derfor "i" int
$stmt->execute();
$stmt->bind_result($passord, $email);
$stmt->fetch();
$stmt->close();
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Min profil</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
	
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Min profil</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		
		<div class="content">
			<h2>Min profil</h2>
			<div>
				<p>Din brukerkonto:</p>
				<table>
					<tr>
						<td>Brukernavn:</td>
						<td><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?></td>
					</tr>
					<tr>
						<td>Passord:</td>
						<td><?=htmlspecialchars($passord, ENT_QUOTES)?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=htmlspecialchars($email, ENT_QUOTES)?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>