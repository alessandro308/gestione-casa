<? include("DbHome.php");
session_start();
$utente=$_SESSION["utente"];
$db = new DbHome($utente);
if($_POST["utente_pagante"] == "Tutti")
	$db -> aggiungiSpesa( date("d-m-Y"), $_POST["importo"], $_POST["causale"]);
else
	$db -> aggiungiSpesaSingola( date("d-m-Y"), $_POST["importo"], $_POST["causale"], $_POST["utente_pagante"]);
header("location:./login.php");
$_SESSION["stato"] = "Spesa Registrata Correttamente";
?>