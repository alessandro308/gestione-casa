<? include("DbHome.php");
session_start();
$utente=$_SESSION["utente"];
$db = new DbHome($utente);
if(strcmp($_POST["newpsw"], $_POST["newpsw1"])){
	$_SESSION["stato"] = "La Password di Conferma è differenza dalla nuova password";
}
if($db -> changePsw($_POST["oldpsw"], $_POST["newpsw"], $_POST["newpsw1"]))
	$_SESSION["stato"] = "Password Modifica con Successo";
else
	$_SESSION["stato"]= "Tentativo di modifica password fallito";
header("location: ./login.php");
?>