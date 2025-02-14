<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'mohamed';  // legg inn brukernavnet til SQL-tilgangen
$DATABASE_PASS = '87654321';  // legg inn ditt passord til SQL-tilgangen
$DATABASE_NAME = 'login_db';  // legg inn navnet på databasen din

// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, 
$DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the 
	error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data from the login form (index.hmtl-filen) was submitted, isset() will check if the data exists. !isset = data don't exist. ! = not.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}

if ($stmt = $con->prepare('SELECT bruker_id, passord FROM ditt_tabellnavn WHERE 
brukernavn = ?')) {
	// Bind parameters (s = string, i = int,), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($bruker_id, $passord);
    $stmt->fetch();
    // Account exists, now we verify the password.
    // Note: remember to use password_hash in your registration file to store the hashed passwords.
// Linjen under må se slik ut!!
if (password_verify($_POST['password'], $passord)) {
      // Verification success! User has logged-in!
      // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
      session_regenerate_id();
      $_SESSION['loggedin'] = TRUE;
      $_SESSION['name'] = $_POST['username'];
      $_SESSION['id'] = $bruker_id;
      header('Location: home.php');
    } else {
      // Incorrect password
      echo 'Incorrect username and/or password!';
    }
  } else {
    // Incorrect username
    echo 'Incorrect username and/or password!';
  }
	$stmt->close();
}
?>
