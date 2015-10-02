<? include("DbHome.php");

$db = new DbHome("Leo");

function create_user($db){
	$psw = md5("password");
	$db -> exec("INSERT INTO utenti VALUES ('Leo', '$psw')");
	$db -> exec("INSERT INTO utenti VALUES ('AleS', '$psw')");
	$db -> exec("INSERT INTO utenti VALUES ('AleP', '$psw')");
	$db -> exec("INSERT INTO utenti VALUES ('Sim', '$psw')");
}

create_user($db);