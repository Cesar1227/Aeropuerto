<?php
	if (!isset($_SESSION['activo'])) {
		session_start();
	}
	
	if (empty($_SESSION['activo'])) {

	    header('location: ../index.php');
	    session_destroy();
	}
	
	
?>