<? 
session_start();
if(!isset($_COOKIE['gestione-casa'])){?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Gestionale Spese Casa</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Accesso Gestione Casa</h2>
  <? if( isset($_SESSION['login-failed']) && $_SESSION['login-failed'] == true){
	  echo "<h3> Errore Accesso. Riprova.</h3>";
  }?>
  <form role="form" action="login.php" method="POST">
	<div class="form-group">
	  <label for="usr">Utente:</label>
	  <input type="text" class="form-control" id="utente" placeholder="Utente" name="utente">
	</div>
	<div class="form-group">
	  <label for="pwd">Password:</label>
	  <input type="password" class="form-control" id="password" placeholder="Password" name="password">
	</div>
	<button type="submit" class="btn btn-default">Entra</button>
  </form>
</div>

</body>
<? }
else
header("location: ./login.php");
?>