<? include("DbHome.php");
session_start();
$utente=$_SESSION["utente"];
$db = new DbHome($utente);
if(strcmp($_POST["newpsw"], $_POST["newpsw1"])){
	$_SESSION["stato"] = "La password di conferma non coincide.";
}
if($db -> changePsw($_POST["oldpsw"], $_POST["newpsw"], $_POST["newpsw1"]))
	$_SESSION["stato"] = '<font color="green">Password Modifica con Successo</font>';
else
	$_SESSION["stato"]= "Tentativo di modifica password fallito";
header("location: ./login.php");
?>