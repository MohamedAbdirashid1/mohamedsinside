<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'eksamen'; 
$DATABASE_PASS = '87654321';  
$DATABASE_NAME = 'login_db';  

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
$DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Kan ikke koble til MySQL: ' . mysqli_connect_error());
}

$stmt = $con->prepare('SELECT password, email FROM users WHERE id = ?');


$stmt->bind_param('i', $_SESSION['id']);
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
				<a href="profile.php" tabindex="1"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="home.php" tabindex="2"><i class="fas fa-sign-out-alt"></i>Home</a>
				<a href="logout.php" tabindex="3"><i class="fas fa-sign-out-alt"></i>Logout</a>
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
						<!--<td>Passord (jeg vet dette er usikkert..):</td>
						<td><?=htmlspecialchars($passord, ENT_QUOTES)?></td>-->
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