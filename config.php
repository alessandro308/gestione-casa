<? include("DbHome.php");

function create_user(){
	$db = new DbHome("utente");
	$psw = md5("password");
	$db -> exec("INSERT INTO utenti VALUES ('Leo', '$psw', 'ND')");
	$db -> exec("INSERT INTO utenti VALUES ('AleS', '$psw', 'ND')");
	$db -> exec("INSERT INTO utenti VALUES ('AleP', '$psw', 'ND')");
	$db -> exec("INSERT INTO utenti VALUES ('Sim', '$psw', 'ND')");
}

create_user();
echo "Utenti Creati";
?>