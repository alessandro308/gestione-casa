<?
define("N_UTENTI", 4);
class DbHome extends SQLite3{
	private $utente;
	private $logged;
	
	function __construct ($utente){
		$this->utente = $utente;
		$this -> open("home_db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
		$this -> exec("CREATE TABLE IF NOT EXISTS spese (data DATE, debitore TEXT, creditore TEXT, cash DOUBLE PRECISION, causale TEXT)");
		$this -> exec("CREATE TABLE IF NOT EXISTS utenti (nome TEXT, password TEXT, email TEXT)");
		//$this -> create_user();
	}
	
	function changePsw($old, $new, $newCheck){
		if(strcmp($new, $newCheck) == 0){
			return false;
		}
		else{
			if($this->userCheck($old)){
				$psw = md5($new);
				$this -> exec("UPDATE utenti SET password = '$psw' WHERE nome='$this->utente'");
				return true;
			}
			return false;
		}
	}

	function lista_debiti($creditore){
		$utente = $this->utente;
		$result = $this -> query("SELECT * FROM spese WHERE debitore='$utente' AND creditore='$creditore' ORDER BY data");
		return $result;
	}

	function debitoVerso($creditore){
		$debiti = $this->lista_debiti($creditore);
		$debito = 0;
		while ($row = $debiti -> fetchArray()){
			$debito += $row["cash"];
		}
		return $debito;
	}

	function lista_crediti($debitore){
		$utente = $this->utente;
		$result = $this->query("SELECT * FROM spese WHERE creditore='$utente' AND debitore='$debitore' ORDER BY data");
		return $result;
	}

	function creditoDa($debitore){
		$crediti = $this->lista_crediti($debitore);
		$credito = 0;
		while($row = $crediti->fetchArray()){
			$credito += $row["cash"];
		}
		return $credito;
	}

	/*Ritorna tutti gli utenti della casa tranne l'operatore*/
	function getUser(){
		$utente = $this->utente;
		$result = $this -> query("SELECT * FROM utenti WHERE nome != '$utente' ");
		return $result;
	}

	function aggiungiSpesa($data, $spesa, $causale){
		$debito_utente = $spesa/N_UTENTI;
		$utenti = $this->getUser();
		$creditore = $this->utente;
		//Per ogni utente inserisci la spesa
		while($utente = $utenti -> fetchArray()){
			$usr = $utente["nome"];
			$this -> exec("INSERT INTO spese VALUES ('$data', '$usr', '$creditore', $debito_utente, '$causale')");
		}
	}

	function saldaDebito($debitore){
		$utente = $this->utente;
		/*Tutti i debiti saldati, rimuovo tutte gli scontrini tra debitori e creditori*/
		$this -> exec("DELETE FROM spese WHERE debitore='$debitore' AND creditore='$utente'");
		$this -> exec("DELETE FROM spese WHERE debitore='$utente' AND creditore='$debitore'");
	}
	
	function aggiungiSpesaSingola($data, $spesa, $causale, $debitore){
		$utente = $this->utente;
		$this -> exec("INSERT INTO spese VALUES ('$data', '$debitore', '$utente', $spesa, '$causale')");
	}

	function userCheck($password){
		$utente = $this->utente;
		$result = $this -> query("SELECT * FROM utenti WHERE nome='$utente'");
		$row = $result -> fetchArray();
		if(strcmp($row["password"], md5($password)) == 0){
			$this->logged = true;
			return true;
		}
		return false;
	}

}
?>