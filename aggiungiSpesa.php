<? include("DbHome.php");
session_start();
$utente=$_SESSION["utente"];
$db = new DbHome($utente);
$db -> aggiungiSpesa( date("d-m-Y"), $_POST["importo"], $_POST["causale"]);
header("location:./login.php");
$_SESSION["stato"] = "Spesa Registrata Correttamente";
?>