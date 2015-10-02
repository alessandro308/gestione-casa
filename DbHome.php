<?
define("N_UTENTI", 4);
class DbHome extends SQLite3{
	private $utente;
	private $logged;
	function __construct ($utente){
		$this->utente = $utente;
		$this -> open("home_db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
		$this -> exec("CREATE TABLE IF NOT EXISTS spese (data DATE, debitore TEXT, creditore TEXT, cash DOUBLE PRECISION, causale TEXT)");
		$this -> exec("CREATE TABLE IF NOT EXISTS utenti (nome TEXT, password TEXT)");
		$pswLeo = md5("Leo");
		$this -> exec("INSERT INTO utenti VALUES ('Leo', '$pswLeo')");
	}
	
	function lista_debiti($creditore){
		$utente = $this->utente;
		$result = $this -> query("SELECT * FROM spese WHERE debitore=$utente AND creditore=$creditore");
		return $result;
	}
	
	function debitiDa($creditore){
		$debiti = $this->lista_debiti($creditore);
		$debito = 0;
		while ($row = $debiti -> fetchArray()){
			$debito += $row[cash];
		}
		return $debito;
	}
	
	function lista_crediti($debitore){
		$utente = $this->utente;
		$result = $this->query("SELECT * FROM spese WHERE creditore=$utente AND debitore=$debitore");
		return $result;
	}
	
	function creditoDa($debitore){
		$crediti = $this->lista_crediti($debitore);
		$credito = 0;
		while($row = $crediti->fetchArray()){
			$credito += $row[cash];
		}
		return $credito;
	}
	
	/*Ritorna tutti gli utenti della casa tranne l'operatore*/
	function getUser(){
		$utente = $this->utente;
		return $this -> query("SELECT nome FROM utenti WHERE nome != $utente");
	}
	
	function aggiungiSpesa($data, $spesa, $causale){
		$debito_utente = $spesa/N_UTENTI;
		$utenti = $this->getUser();
		$creditore = $this->utente;
		//Per ogni utente inserisci la spesa
		while($utente = $utenti -> fetchArray()){
			$this -> exec("INSERT INTO spese VALUES ($data, $utente, $creditore, $spesa, $causale)");
		}
	}
	
	function saldaDebito($debitore){
		$utente = $this->$utente;
		$this -> exec("DELETE * FROM spese WHERE debitore=$debitore AND creditore=$utente");
	}
	
	function userCheck($password){
		$utente = $this->utente;
		$result = $this -> query("SELECT * FROM utenti WHERE nome='$utente'");
		$row = $result -> fetchArray();
		if($row["password"] == md5($password)){
			$this->logged = true;
			return true;
		}
		return false;
	}
	
}
?>