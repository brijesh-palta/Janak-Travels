<<<<<<< HEAD
<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../");
exit();
?>
=======
<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../");
exit();
?>
>>>>>>> 9653623 (Initial commit with Dockerfile, compose files and Jenkinsfile)
