<?php
	session_start();
	session_destroy();
	unset($_COOKIE["gestione-casa"]);
	setcookie("gestione-casa", "", time()-3600);
	header("location:index.php");
?>