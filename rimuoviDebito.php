<? include("DbHome.php");
session_start();
$utente=$_SESSION["utente"];
$db = new DbHome($utente);
$db -> saldaDebito($_POST["utente_pagante"]);
$_SESSION["stato"] = "Debito Saldato Correttamente";
header("location:./login.php");
?>