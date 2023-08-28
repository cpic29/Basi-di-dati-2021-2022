<?php 

require 'funzioni.php';

if($_SERVER['REQUEST_METHOD'] == "POST")
{
  $msg = "";
  $msg2 = "Qualcosa è andato storto...";
  
  $user_name = trim($_POST['username']);
  $not_hash = trim($_POST['password']);

  $user_name = mysqli_real_escape_string($con,$user_name);
  $password = mysqli_real_escape_string($con,$not_hash);


  //controlli lato server
  if(!empty($user_name) && !empty($password)){

    if((strlen($user_name) > 2) && (strlen($user_name) < 16) && (strlen($password) > 7) && (strlen($password) < 17))
    {
      if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9]*$/', $user_name))
      {
        //preparred statement
        $query = "SELECT * from utente where username = ? limit 1";

        $result = mysqli_prepare($con, $query);

        if($result){

          mysqli_stmt_bind_param($result,'s', $user_name);

          mysqli_stmt_execute($result);

          $stm = mysqli_stmt_get_result($result);

          if(mysqli_num_rows($stm) > 0){

            $user_data = mysqli_fetch_assoc($stm);
            $pas = $user_data['pass'];

            if(password_verify($password, $pas)){
              $_SESSION['info'] = $user_data;
              $_SESSION['utente'] = $user_data;

              header("Location: profilo.php");
              die;
            } 

          } 
        } 

      mysqli_stmt_close($result);

      } else {
        $msg = $msg2;
      }

  } else {
    $msg = $msg2;
  }
}

mysqli_close($con);

}

    
?>


<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8"> <title>Login</title> 
<link href=".../skeleton.css" rel="stylesheet" media="screen">
<link href="css/skeleton.css" rel="stylesheet" media="screen">
<link href="css/normalize.css" rel="stylesheet" media="screen">
<link href="css/costumize.css" rel="stylesheet" media="screen">

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<script>
  $(document).ready(function(){
    $('#submit').click(function(){
  
      var valid = true;
  
      if ($("#username").val().length < 3){
        $("#username").addClass("error");
        $("#span1").html("Inserisci almeno 4 caratteri");
        $("#username").val("")
        valid = false;
      } else {
        var us = $("#username").val();;
        var cosa = 'username';
        
        if(username != ""){
        $.ajax({
          url: 'validazione_registrazione.php',
          method: 'POST',
          async: false,
          data:{input1: us, input2: cosa},
  
          success:function(response){
            if(response == 2){
              $("#span1").html("Utente non esistente!");
              $("#username").addClass("error");
              valid = false;
              } else if (response == 1){
                $("#span1").html("");
                $("#username").removeClass("error");
              }
            }
          })
        };
      };

      if ($("#password").val().length < 8){
        $("#password").addClass("error");
        $("#span3").html("Inserisci almeno 8 caratteri");
        $("#password").val("");
        valid = false;
      } else {
        var pas = $("#password").val();;
        var cosa = 'password';
        var us = $("#username").val()
        $.ajax({
          url: 'validazione_registrazione.php',
          method: 'POST',
          async: false,
          data:{input1: pas, input2: cosa, input3: us},
  
          success:function(response){
            if(response == 1){
              $("#span3").html("");
              $("#password").removeClass("error");
            } else if(response == 2){
              $("#span3").html("Password errata!");
              $("#password").addClass("error");
              valid = false;
            }
          }
          });
        }; 

        return valid;

    });
  });

</script>
</head>

<body>
  
<?php include "header.php" ?>

</br>


<div class="container">

<h5>Login</h5>

<span class="error1"><?php if(isset($msg)){echo $msg;}?></span>

</br>
  
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
          <input class="u-full-width" type="text" placeholder="Username" id="username" name="username" maxlength="20">

      </div>

      <div class="six columns">

          <div class="row">

            <div class="six columns">
            <label for="password">Password</label>
            </div>

            <div class="six columns">
              <span id="span3" class="error1"></span>
            </div>

          </div>
          <input class="u-full-width" type="password" placeholder="Password" id="password" name="password" maxlength="16">

      </div>

    </diV>

</br>

    <div class="row">
      <div class="six columns">
        <label for="login">Non hai un account?</label><a href="registrazione.php" id="login">Registrati</a>
      </div>

      <div class="six columns">
        <input class="button-primary" type="submit" value="Entra" id="submit">
      </div>
    </div>

    </form>

</div>


</body>

<?php 
    include_once 'footer.php';
?>
</html>