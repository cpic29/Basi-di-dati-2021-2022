<?php 
require 'funzioni.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
  $msg = "";
  $msg2 = "Qualcosa è andato storto...";

	//something was posted
	$user_name = trim($_POST['username']);
  $email = trim($_POST['email']);
	$not_hash = trim($_POST['password']);
  $codf = trim($_POST['codiceFiscale']);
  $tel = trim($_POST['telefono']);


  $user_name = mysqli_real_escape_string($con,$user_name);
  $email = mysqli_real_escape_string($con,$email);
  $not_hash = mysqli_real_escape_string($con,$not_hash);
  $codf = mysqli_real_escape_string($con,$codf);
  $tel = mysqli_real_escape_string($con,$tel);


		if((!empty($user_name)) && (!empty($not_hash)) && (!empty($email)) && (!empty($codf)) && (!empty($tel)))
		{
      //controllo lunghezza campi
      if((strlen($user_name) > 2) && (strlen($user_name) < 16) && (strlen($not_hash) > 7) && (strlen($not_hash) < 17) && (strlen($email) > 1) && (strlen($email) < 51) && (strlen($codf) > 14) && (strlen($codf) < 17) && (strlen($tel) > 8) && (strlen($tel) < 11))
      {
        //controllo espressioni regolari
        if((preg_match('/^[a-zA-Z]+[a-zA-Z0-9]*$/', $user_name)) && (preg_match('/^.+@.+\..+$/', $email)) && ((preg_match('/^3\d{9}$/', $tel)) || (preg_match('/^0\d{9}$/', $tel))) && (preg_match('/^([A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST]{1}[0-9LMNPQRSTUV]{2}[A-Z]{1}[0-9LMNPQRSTUV]{3}[A-Z]{1})$|([0-9]{11})$/', $codf)))
        {

          $password = password_hash($not_hash, PASSWORD_DEFAULT);

          //sql statements
          $query = "INSERT into utente (username, email, pass, telefono, codF) values (?, ?, ?, ?, ?)";

          //prepare statement
          $result = mysqli_prepare($con, $query);

          if($result){
            // bind variables to prepare statement as parameters

            mysqli_stmt_bind_param($result,'sssss', $user_name, $email, $password, $tel, $codf);

            //execute prepared statement
            mysqli_stmt_execute($result);

                  
          header("Location: login.php");
          die;

          }
          //close prepared statement
          mysqli_stmt_close($result);
        
        } else {
          $msg = $msg2;
        }

      } else {
        $msg = $msg2;
      } 
  } else {
    $msg = $msg2;
  }

}

//close DB connection

mysqli_close($con);

?>


<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8"> <title>Registrazione</title> 
<link href="css/skeleton.css" rel="stylesheet" media="screen">
<link href="css/normalize.css" rel="stylesheet" media="screen">
<link href="css/costumize.css" rel="stylesheet" media="screen">

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
  
<?php include "header.php" ?>

</br>


  <div class="container">
  <h4> Registrazione </h4>

  <span class="error1"><?php if(isset($msg)){echo $msg;}?></span>
  
  <form method="post">

  <div class="row">
      <div class="six columns">

          <div class="row">

            <div class="six columns">
              <label for="id">Username</label>
            </div>

            <div class="six columns">
              <span id="span1" class="error1"></span>
            </div>

          </div>
          <input class="u-full-width" type="text" placeholder="Username" id="username" name="username" maxlength="15">

      </div>

    <div class="six columns">
      <div class="row">

        <div class="six columns">
          <label for="email">Email</label>
        </div>

        <div class="six columns">
          <span id="span2" class="error1"></span>
        </div>
      </div>
      <input class="u-full-width" type="text" placeholder="Indirizzo email" id="email" name="email" maxlength="50">
    </div>
</div>

    </br>
    <div class="row">
      <div class="six columns">

          <div class="row">

            <div class="six columns">
              <label for="telefono">Telefono</label>
            </div>

            <div class="six columns">
              <span id="span5" class="error1"></span>
            </div>

          </div>
          <input class="u-full-width" type="text" placeholder="Telefono" id="telefono" name="telefono" maxlength="10">

      </div>

    <div class="six columns">
      <div class="row">

        <div class="six columns">
          <label for="codiceFiscale">Codice fiscale</label>
        </div>

        <div class="six columns">
          <span id="span6" class="error1"></span>
        </div>
      </div>
      <input class="u-full-width" type="text" placeholder="Codice fiscale" id="codiceFiscale" name="codiceFiscale" maxlength="16">
    </div>
</div>

    </br>

    <div class="row">
      <div class="six columns">

          <div class="row">

            <div class="six columns">
            <label for="password">Password</label>
            </div>

            <div class="six columns">
              <span id="span3" class="error1"></span>
            </div>

          </div>
          <input class="u-full-width" type="password" placeholder="Nuova password" id="password" name="password" maxlength="16">

      </div>

    <div class="six columns">
      <div class="row">

        <div class="six columns">
        <label for="password2">Ripeti password</label>
        </div>

        <div class="six columns">
          <span id="span4" class="error1"></span>
        </div>
      </div>
      <input class="u-full-width" type="password" placeholder="Ripeti password" id="password2" maxlength="16">
    </div>
</div>

  </br>

    <div class="row">
        <div class="six columns">
        <label for="login">Hai già un account?</label><a href="login.php" id="login">Entra</a>
        </div>

        <div class="six columns">
        <input class="button-primary" type="submit" value="Registrami" id="submit">
        </div>
    </div>

  </form>

  </div>

</body>
<script src = "script/form_registrazione_validation.js"></script>
</html>

