<?php


session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = ''; 
$DATABASE_PASS = '';  
$DATABASE_NAME = '';  

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Kan ikke koble til MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Fyll ut skjemaet!');
}

if ($stmt = $con->prepare('SELECT id, password FROM users WHERE 
username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password);
    $stmt->fetch();

if (password_verify($_POST['password'], $password)) {
      session_regenerate_id();
      $_SESSION['loggedin'] = TRUE;
      $_SESSION['name'] = $_POST['username'];
      $_SESSION['id'] = $id;
      header('Location: home.php');
    } else {
      echo 'Feil brukernavn og/eller passord!';
    }
  } else {
    echo 'Feil brukernavn og/eller passord!';
  }
	$stmt->close();
}
?>
