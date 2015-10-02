<?
$utente = $_POST['utente'];
include("DbHome.php");
echo "stampa: "+ $utente + $_POST['password'];
/*session_start();
if(!isset($_COOKIE['gestione-casa'])){
	$db = new DbHome($_POST['utente']);
	if($db -> userCheck(md5($_POST['password']))){
		$_SESSION["login-failed"] = false;
		setcookie('gestione-casa', $_POST['utente'] +'/'+ $_POST['password']);
	}
	else{
		$_SESSION["login-failed"] = true;
		header("location: ./index.php");
	}
}

$utente = strtok($_COOKIE['gestione-casa'], "/");
$password = strtok("/");

echo $utente;
echo $password;*/
?>