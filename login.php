<?
include("DbHome.php");
session_start();
if(!isset($_COOKIE['gestione-casa'])){
	$db = new DbHome($_POST['utente']);
	if($db -> userCheck($_POST['password'])){
		$_SESSION["login-failed"] = false;
		$cookie_string = $_POST['utente'].'/'.$_POST['password'];
		setcookie("gestione-casa", $cookie_string);
	}
	else{
		$_SESSION["login-failed"] = true;
		header("location: ./index.php");
	}
}
else{
	$cookie_string = $_COOKIE["gestione-casa"];
}

$utente = strtok($cookie_string, "/");
$_SESSION["utente"] = $utente;
$password = strtok("/");

$db = new DbHome($utente);
include("header.php");
?>

<div class="container">
	<center><img src="http://www.bartrattoriaichnusa.it/polopoly_fs/1.13207862.1375103135!/httpImage/img.jpg" /></center>
	<h2>Utente: <?php echo $utente; if(isset($_SESSION["stato"])){echo ' - <font color="red">'.$_SESSION["stato"]."</font>"; unset($_SESSION["stato"]);}?> - <a href="logout.php">Logout</a></h2>
  <h2>Tabella Riepilogo</h2>
  <p>Il valore mostrato è la differenza tra credito e debito</p>            
  <table class="table table-condensed">
	<thead>
	  <tr>
		<?php 
		$utenti = $db -> getUser();
		while( $row = $utenti->fetchArray() ){
			echo "<th>".$row["nome"]."</th>";
		}
		?>
	  </tr>
	</thead>
	<tbody>
	  <?php 
	  $utenti = $db -> getUser();
	  while( $row = $utenti->fetchArray() ){
		$valore = $db->creditoDa($row["nome"]) - $db->debitoVerso($row["nome"]);
		if($valore < 0){
			$string = '<font color="red">'.$valore.'</font>';
		}
		else
			$string = '<font color="green">'.$valore.'</font>';
	  	echo "<td>".$string."</td>";
	  }
	  ?>
	</tbody>
  </table>
  
  <h2>Registra nuova spesa</h2>
  <form role="form-inline" action="aggiungiSpesa.php" method="POST">
	<div class="form-group">
		<label for="sel1">Da chi devi ricevere questi soldi?</label>
		<select class="form-control"  id="sel1" name="utente_pagante">
			<option>Tutti</option>
			<?php 
				$utenti = $db -> getUser();
				while( $row = $utenti->fetchArray()){
						echo "<option>".$row["nome"]."</option>";
				}
				?>
			  </tr>
		 </select>
	</div>
  	<div class="form-group">
  	  <label for="comment">Causale:</label>
  	  <input type="text" class="form-control" id="causale" placeholder="Inserisci una descrizione di spesa" name="causale">
  	</div>
  	<div class="form-group">
  	  <label for="comment">Spesa:</label>
  	  <input type="number" class="form-control" id="causale" placeholder="Importo spesa" name="importo" step="any">
  	</div>
  	<button type="submit" class="btn btn-default">Inserisci Spesa</button>
    </form>

	<h2>Salda Debito</h2>
	<form role="form-inline" action="rimuoviDebito.php" method="POST">
		<div class="form-group">
		<label for="sel1">Utente</label>
		<select class="form-control"  id="sel1" name="utente_pagante">
			<?php 
				$utenti = $db -> getUser();
				while( $row = $utenti->fetchArray()){
					if ( $db->creditoDa($row["nome"]) - $db->debitoVerso($row["nome"] ) > 0)
						echo "<option>".$row["nome"]."</option>";
				}
				?>
			  </tr>
		  </select>
	  </div>
		 <button type="submit" class="btn btn-default">Salda Debito</button>
	</form>
	
	<h2>Cambia Password</h2>
	<form role="form-inline" action="changePsw.php" method="POST">
		<div class="form-group">
			<label for="psw">Vecchia Password</label>
			<input type="password" class="form-control" id="psw" placeholder="Inserisci la vecchia password" name="oldpsw">
		</div>
		<div class="form-group">
			<label for="psw">Nuova Password</label>
			<input type="password" class="form-control" placeholder="Inserisci la nuova password" name="newpsw">
		</div>
		<div class="form-group">
			<label for="psw">Conferma Nuova Password</label>
			<input type="password" class="form-control"  placeholder="Reinserisci la nuova password" name="newpsw1">
		</div>
		 <button type="submit" class="btn btn-default">Cambia Password</button>
	</form> 
</div>
</body>
</html>
