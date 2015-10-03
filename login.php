<?
include("DbHome.php");
session_start();

/*Fase di Login*/
if(!isset($_COOKIE['gestione-casa'])){
	if(!isset($_POST['utente']))
		header("location:index.php");
	$utente = sqlite_escape_string($_POST['utente']);
	$password =sqlite_escape_string($_POST['password']);
	$db = new DbHome($utente);
	if($db -> userCheck($password)){
		$_SESSION["login-failed"] = false;
		$cookie_string = $utente.'/'.$password;
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
			echo "<th>".$row["nome"];
			echo ' <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#'.$row["nome"].'">Dettaglio Spese</button>';
			echo "</th>";
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
  <div class="row">
	  <div class="col-sm-4">
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
		</div>
		<div class="col-sm-4">

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
		</div>
		<div class="col-sm-4">	
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
				 <button type="submit" class="btn btn-default">Cambia Password</button> <br/>
			</form>
		</div>
	</div>
</div>

<?php
$result = $db -> getUser();
while($row = $result->fetchArray()){
	$creditore=$row["nome"];
?>
<div id="<?php echo $creditore;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Lista Dettaglio Spese</h4>
	  </div>
	  <div class="modal-body">
		<?php $debiti = $db -> lista_debiti($creditore); ?>
		
		<table class="table table-condensed">
			<thead>
			  <tr>
				<th> Data Inserimento </th>
				<th> Causale </th>
				<th> Importo </th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
			  while( $debito = $debiti->fetchArray() ){
				echo "<tr>";
			  	echo "<td>".$debito["data"]."</td>";
			  	echo "<td>".$debito["causale"]."</td>";
			  	echo "<td>".$debito["cash"]."</td>";
			  	echo "</tr>";
			  }
			  ?>
			</tbody>
		  </table>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>

  </div>
</div>
<?php }
?>

</body>
</html>
