<?php
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'mohamed';  // legg inn brukernavnet til SQL-tilgangen
$DATABASE_PASS = '87654321';  // legg inn ditt passord til SQL-tilgangen
$DATABASE_NAME = 'login_db';  // legg inn navnet på databasen din

// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
$DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data was submitted, isset() function checks if the data   exists. Dette sjekker om variablene er skrevet inn i register.html.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || 
empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

//HER LEGGES DET TIL EVENTUELLE VALIDERINGSFORMER - må ikke. (Steg 5 i manualen)

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT bruker_id, passord FROM ditt_tabellnavn WHERE  brukernavn = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password     using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo 'Username exists, please choose another!';
	} else {
	
// Username doesn't exists, insert new account
if ($stmt = $con->prepare('INSERT INTO ditt_tabellnavn (brukernavn, passord,     email) VALUES (?, ?, ?)')) {           //Her sjekker vi om prepare-metoden lyktes
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	$passord = password_hash($_POST['password'], PASSWORD_DEFAULT); //her hashes   passordet så det lagres kryptert og ikke i klartekst! Password_default er        hash-metoden.
	$stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);     //Lagres som tekst. Tre s-er siden det er tre variabler som er stringer.
	$stmt->execute();
	echo 'You have successfully registered! You can now login!';
} else {
	// Something is wrong with the SQL statement, so you must check to make sure   your accounts table exists with all three fields.
	echo 'Could not prepare statement!';
}
	
	}
	$stmt->close();
} else {
	// Something is wrong with the SQL statement, so you must check to make sure   your accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}
$con->close();
?>