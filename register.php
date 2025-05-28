<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'eksamen'; 
$DATABASE_PASS = '87654321';  
$DATABASE_NAME = 'login_db';  

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
$DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Kan ikke koble til MySQL: ' . mysqli_connect_error());
}

if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['birthday'])) {
	exit('Fyll ut skjemaet!');
}

if (empty($_POST['username']) || empty($_POST['password']) || 
empty($_POST['email'] || empty($_POST['birthday']))) {
	exit('Fyll ut skjemaet!');
}

if ($stmt = $con->prepare('SELECT id, password FROM users WHERE  username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		echo 'Brukernavnet er tatt!';
	} else {
	
if ($stmt = $con->prepare('INSERT INTO users (username, password, email, birthday) VALUES (?, ?, ?, ?)')) {           //Her sjekker vi om prepare-metoden lyktes
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT); //her hashes   passordet så det lagres kryptert og ikke i klartekst! Password_default er        hash-metoden.
	$stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $_POST['birthday']);     //Lagres som tekst. Tre s-er siden det er tre variabler som er stringer.
	$stmt->execute();
	echo 'Du har registrert en bruker! Jippi!';
} else {
	echo 'Could not prepare statement!';
}
	
	}
	$stmt->close();
} else {
	echo 'Could not prepare statement!';
}
$con->close();
?>