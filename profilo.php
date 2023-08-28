<?php 

require 'controlli_profilo.php';


$input = $_SESSION['utente']['id_utente'];


?>



<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8"> <title>Profilo</title> 
<link href="css/skeleton.css" rel="stylesheet" media="screen">
<link href="css/normalize.css" rel="stylesheet" media="screen">
<link href="css/costumize.css" rel="stylesheet" media="screen">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>

<script>
function selectPage(c,page_num,i){
    $.ajax({
    url: 'pagination_search.php',
    method: 'GET',
    data:{
    input:page_num,
    result:c,
    i:i},

    success:function(html){
        $("#result_"+c).html(html);
    },

    error:function(){
        console.log("si è verificato un errore");
    }

    });
};


function paginazione(c,x,i,y){
    $.ajax({
    url:'numeri_paginazione.php',
    method: 'GET',
    data:{
    result:c,
    totale:x,
    i:i,
    pagina:y,
    },

    success:function(html){
        $("#pagination_"+c).html(html);
    },
    });
};

function cerca_b(val){
  $.ajax({
      url: 'cerca_b.php',
      method: 'POST',
      data: {val:val},

      success:function(){
          window.location.href = 'blog.php';
      } 
  });
};

</script>
</head>
<body>

<?php 
include "header.php";
include "cerca.php";

if(!empty($_SESSION['info']) && $_SESSION['info']['id_utente'] == $_SESSION['utente']['id_utente']){
    if(!empty($_GET['action']) && $_GET['action'] == 'edit'):?>
        </br>
            <div class="container">
                <h4>Modifica profilo</h4>
                <span class="error1"><?php if(isset($msg)){echo $msg;}?></span>

        </br>
                <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class = "six columns">
                        <label for="biografia">Biografia</label>
                    </div>
                    <div class = "six columns">
                        <span id="span5" class="error1"></span>
                    </div>
                </div>
                <textarea class="u-full-width" placeholder="Racconta qualcosa su di te...." id="biografia" name="biografia" maxlength="170"><?php if(!empty($_SESSION['info']['bio'])){$bio = htmlspecialchars($_SESSION['info']['bio']);
            $bio = nl2br(stripcslashes($bio));
            echo $bio;
            }?></textarea>
            </br></br>

                    <div class="row">
                        <div class="six columns">
                            <div class="row">
                                <div class="six columns">
                                    <label for="username">Username</label>
                                </div>
                                <div class="six columns">
                                    <span id="span1" class="error1"></span>
                                </div>
                            </div>
                            <input class="u-full-width" type="text" placeholder="Username" id="username" name="username" value="<?php echo $_SESSION['info']['username'];?>" maxlength="20">
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
                            <input class="u-full-width" type="text" placeholder="Indirizzo email" id="email" name="email" value="<?php echo $_SESSION['info']['email'];?>" maxlength="50">
                        </div>
                    </div></br>

                    <div class="row">
                        <div class="six columns">
                            <div class="row">

                                <div class="six columns">
                                <label for="telefono">Telefono</label>
                                </div>
                                <div class="six columns">
                                <span id="span7" class="error1"></span>
                                </div>

                            </div>
                            <input class="u-full-width" type="text" placeholder="Telefono" id="telefono" name="telefono" maxlength="10" value="<?php echo $_SESSION['info']['telefono'];?>">
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
                            <input class="u-full-width" type="text" placeholder="Codice fiscale" id="codiceFiscale" name="codiceFiscale" maxlength="16" value="<?php echo $_SESSION['info']['codF'];?>">
                        </div>
                    </div>

                    </br>


                    <div class="row">
                        <div class="six columns">
                            <div class="row">
                                <div class="six columns">
                                    <label for="password">Nuova password</label>
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
                                    <label for="password2">Ripeti nuova password</label>
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
                            <a href="profilo.php">
                                <input class="button" type="button" value="Annulla modifiche">
                            </a>
                        </div>

                        <div class="six columns">
                            <input class="button-primary" type="submit" value="Salva modifiche" id="submit1">
                        </div>
                    </div>

                </form>
            </div> 


        </br>


        <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'delete'):?>
            <div class="container">
                <form method="post">
                    <p>Sei sicuro di voler eliminare il profilo?</p>


                    <div class="row">
                        <div class="six columns">
                            <label for="id">Username</label>
                            <div class="u-full-width" id="username" name="username"><?php echo $_SESSION['info']['username']?></div>
                        </div>
                    
                        <div class="six columns">
                            <label for="email">Email</label>
                            <div class="u-full-width" id="email" name="email"><?php echo $_SESSION['info']['email']?></div>
                        </div>
                    </div>

                    </br>

                    <div class="row">
                        <div class="six columns">
                            <a href="profilo.php">
                                <input class="button-primary" type="button" value="Indietro">
                            </a>
                        </div>

                        <div class="six columns">
                            <input class="button" type="submit" value="Elimina il profilo" id="submit">
                        </div>
                    </div>

                </form>
            </div> 

            
        <?php else:?>
            <div class="container">
                <h5 class="title"> Ciao <?php echo $_SESSION['info']['username'];?> !</h5>

                <a class="button" href="profilo.php?action=edit">Modifica profilo</a>
                <a class="button" href="profilo.php?action=delete">Elimina il profilo</a>
            </div>
        <?php endif;

} else {
    // cosa far vedere a un utente esterno 
?>
    <div class="container">
        <table class="u-full-width">
        <thead>
            <tr>
            <th> <h4><?php echo $_SESSION['utente']['username']?></h4></th>
            </tr>
        </thead>
        <tbody>
            <tr><td style="padding-top: 30px; padding-bottom: 30px;"><?php if(!empty($bio)){$bio = htmlspecialchars($_SESSION['utente']['bio']);
            $bio = nl2br(stripcslashes($bio));
            echo $bio;}?></td></tr>
        </tbody>
        </table>
    </div>
<?php
}

?>
</br></br>

        <?php if(!empty($_SESSION['info']) && $_SESSION['info']['id_utente'] == $_SESSION['utente']['id_utente']){?>
            <div class="container">
            <?php if(!empty($_GET['action']) && $_GET['action'] == 'create_b'):?>
                    <h5>Crea un nuovo blog</h5>
                    <span class="error1"><?php if(isset($msgB)){echo $msgB;}?></span>

                    <form method="post">
                    </br>

                    <div class="row">
                        <div class="six columns">
                        <label for="nome_b">Nome blog</label>
                    </div>

                    <div class="six columns">
                    <span id="span1" class="error1"></span>
                    </div>

                    </div>

                        <input class="u-full-width" type="text" placeholder="Nome blog" id="nome_b" name="nome_b" maxlength="20">

                    </br></br>


                    <div class="row">
                        <div class="six columns">
                        <label for="argomento">Argomento</label>
                    </div>

                    <div class="six columns">
                    <span id="span2" class="error1"></span>
                    </div>

                    </div>

                        <input class="u-full-width" type="text" placeholder="Inserisci l'argomento" id="argomento" name="argomento" maxlength="20">
                        </br>
                        <div class="suggerimento_a" id="suggerimento_a"></div>
                        

                        <div id="sottoargomento1">

                        </br>

                        <div class="row">
                            <div class="six columns">
                            <label for="sottoargomento">Sottoargomento (opzionale)</label>
                            </div>

                            <div class="six columns">
                            <span id="span3" class="error1"></span>
                            </div>
                        </div>

                        <input class="u-full-width" type="text" placeholder="Inserisci un sottoargomento" id="sottoargomento" name="sottoargomento" maxlength="20">
                        <div class="suggerimento_s" id="suggerimento_s"></div>
                        </div>

                        </br>


                        <label for="coautore">Coautore (opzionale)</label>



                        <input class="u-full-width" type="text" placeholder="Inserisci il nome del coautore" id="coautore" name="coautore">
                        <div class="suggerimento_c" id="suggerimento_c"></div>
                        </br>


    
                        
                        <label for="tema">Colore tema</label>


                        </br>

                        <div class="container" id="tema" >
                            <div class="row">

                            <div class="three columns active" >
                            <label for="1" style="background-color: #33C3F0">.</label>
                            <input type="radio" value="1" name="colori" checked <?php if(isset($tema) && $tema=="1"){echo "checked";}?>></input></div>


                            <div class="three columns active" >
                            <label for="2" style="background-color: #FF8D8E">.</label>
                            <input type="radio" value="2" name="colori" <?php if (isset($tema) && $tema=="2") {echo "checked";}?>></input></div>

                            <div class="three columns active" >
                            <label for="3" style="background-color: #54C292">.</label>
                            <input type="radio" value="3" name="colori" <?php if (isset($tema) && $tema=="3"){echo "checked";}?>></input></div>

                            <div class="three columns active">
                            <label for="4" style="background-color: #DEA0DE">.</label>
                            <input type="radio" value="4" name="colori" <?php if (isset($tema) && $tema=="4"){echo "checked";}?>></input></div>

                            </div>
                        </div>
            </br>
            </br>
            </br>
                        <div class="row">
                        <div class="two columns">
                            <a href="profilo.php">
                                <input class="button" type="button" value="Annulla">
                            </a>
                        </div>

                        <div class="eight columns" style="color: white">a
                        </div>

                        <div class="two columns">
                            <input class="button-primary" type="submit" value="Crea il blog" id="submit_blog">
                        </div>

                        </div>
                </form>
            </div>

            </br>

            <?php else:?>
                
                <a class="button button-primary"href="profilo.php?action=create_b">Crea un nuovo blog</a>


            <?php endif;
            }
        ?>
          

            </div>
        </br></br>


    <div class="container">
        <?php if(!empty($_SESSION['info']) && $_SESSION['info']['id_utente'] == $_SESSION['utente']['id_utente']):?>
            <h5> I tuoi blogs </h5>
            <?php else:?>
                <h5>Blogs di cui è autore <?php echo $_SESSION['utente']['username']?></h5>
        <?php
        endif;?>

    <div class="container u-full-width" id="result_b">
    </div>

    </br>
    <div class="container">
        <div class="row" id="pagination_b"></div>
    </div>

    <?php
        $limit = 2;
        $id = $_SESSION['utente']['id_utente'];
        $sql = "SELECT count(*) as 'totale' from blog where autore_blog = '$id'";
    
        $query = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($query);
        
        $total_rows=$row['totale'];
        $total_pages= ceil($total_rows / $limit);
        
        if($total_rows < 1):
            if(!empty($_SESSION['info']) && $_SESSION['info']['id_utente'] == $_SESSION['utente']['id_utente']){?>
                <p>Crea il tuo primo blog!</p>
                <?php }else{?>
                    <p>Ancora nessun blog!</p>
        <?php }
        else:?>
            <script>
                    selectPage("b",1,"<?php echo $input; ?>");
                    paginazione("b",<?php echo $total_pages; ?>,"<?php echo $input; ?>");</script>
            <?php endif;
            ?>
    
        </br></br>
        
        <?php if(!empty($_SESSION['info']) && $_SESSION['info']['id_utente'] == $_SESSION['utente']['id_utente']):?>
            <h5> Blogs di cui sei coauotore</h5>
            <?php else:?>
                <h5>Blogs di cui è coautore <?php echo $_SESSION['utente']['username']?></h5>
        <?php
        endif;?>

    <div class="container">
        <div class="row" id="result_bc"></div>
    </div>
    
    <div class="container">
        <div class="row" id="pagination_bc"></div>
    </div>

    <?php
        $limit = 3;
        $id = $_SESSION['utente']['id_utente'];
        $sql = "SELECT count(*) as 'totale' from blog where coautore = '$id'";
    
        $query = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($query);
        
        $total_rows=$row['totale'];
        $total_pages= ceil($total_rows / $limit);
        
        if($total_rows < 1):?>
        </br></br>
            <p>Ancora nessun blog!</p>
        </br></br>
        <?php 
        else:?>
            <script>
                    selectPage("bc",1,"<?php echo $input; ?>");
                    paginazione("bc",<?php echo $total_pages; ?>,"<?php echo $input; ?>");</script>
            <?php endif;
            ?>
</div>
</body>
<?php include_once 'footer.php';?>

<script src="script/cerca.js"></script>
<script src = "script/form_blog_validation.js"></script>
<script src = "script/form_post_editprofile.js"></script>
<script>

$(document).ready(function(){

$('#submit1').click(function(){
    var id = "<?php echo $_SESSION['info']['id_utente']?>";
    var valid = true;
    var us =  /^[a-zA-Z]+[a-zA-Z0-9]*$/;

  if ($("#username").val().length < 3){
    $("#username").addClass("error");
    $("#span1").html("Inserisci almeno 4 caratteri");
    $("#username").val("")
    valid = false;
  } else if (!us.test($("#username").val())){
        $("#username").addClass("error");
        $("#span1").html("Inserisci lettere e numeri!");
        $("#username").val("")
        var valid = false;
    } else {
    var us = $("#username").val();;
    var cosa = 'username';
    
    if(username != ""){
    $.ajax({
      url: 'validazione_registrazione.php',
      method: 'POST',
      data:{input1: us, input2: cosa, id: id},
      async: false, 

      success:function(response){
        if(response == 2){
          $("#span1").html("");
          $("#username").removeClass("error");
          } else if (response == 1){
            $("#span1").html("Utente già esistente!");
            $("#username").addClass("error");
            valid = false;
          }
        }
      })
    };
  };

  var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  if ($('#email').val() == ""){
      $("#email").addClass("error");
      $("#span2").html("Inserisci un indirizzo email");
      $("#email").val("");
      valid = false;
    } else if(!expr.test($('#email').val())){
        $("#email").addClass("error");
        $("#span2").html("Indirizzo email non valido");
        $("#email").val("");
        valid = false;
    } else {
         var email = $('#email').val();
         var cosa = 'email';
         $.ajax({
            url: 'validazione_registrazione.php',
            method: 'POST',
            data:{input1: email, input2: cosa, id: id},
            async: false, 

            success:function(response){
                if(response == 2){
                    $("#email").removeClass("error");
                    $('#span2').html("");
                } else if (response == 1){
                    $("#span2").html("Email già esistente!");
                    $("#email").addClass("error");
                    valid = false;
                }
            }
        })
    };

    var mob = /^3\d{9}$/;
    var fis = /^0\d{9}$/;

      if($("#telefono").val() == ""){
        $("#telefono").addClass("error");
        $("#span7").html("Inserisci un numero di telefono");
        $("#telefono").val("");
        valid = false;
      } else if ((!mob.test($('#telefono').val())) && (!fis.test($('#telefono').val()))){
        $("#telefono").addClass("error");
        $("#span7").html("Telefono non valido");
        valid = false;
      } 

      var cod = /^(?:[A-Z][AEIOU][AEIOUX]|[AEIOU]X{2}|[B-DF-HJ-NP-TV-Z]{2}[A-Z]){2}(?:[\dLMNP-V]{2}(?:[A-EHLMPR-T](?:[04LQ][1-9MNP-V]|[15MR][\dLMNP-V]|[26NS][0-8LMNP-U])|[DHPS][37PT][0L]|[ACELMRT][37PT][01LM]|[AC-EHLMPR-T][26NS][9V])|(?:[02468LNQSU][048LQU]|[13579MPRTV][26NS])B[26NS][9V])(?:[A-MZ][1-9MNP-V][\dLMNP-V]{2}|[A-M][0L](?:[1-9MNP-V][\dLMNP-V]|[0L][1-9MNP-V]))[A-Z]$/;

      if($("#codiceFiscale").val() == ""){
        $("#codiceFiscale").addClass("error");
        $("#span6").html("Inserisci il codice fiscale");
        $("#codiceFiscale").val("");
        valid = false;
      } else if ((!cod.test($('#codiceFiscale').val()))){
        $("#codiceFiscale").addClass("error");
        $("#span6").html("Documento non valido");
        valid = false;
      } 

    if ($("#password").val().length != 0 && $("#password").val().length < 8){
        $("#password").addClass("error");
        $("#span3").html("Inserisci almeno 8 caratteri");
        $("#password").val("");
        valid = false;
    } else {
        $("#password").removeClass("error");
        $('#span3').html("");
    } 

    if ($("#password2").val().length != $("#password").val().length || $("#password").val() != $("#password2").val()) {
        $("#password").addClass("error");
        $("#password2").addClass("error");
        $("#span4").html("Le password non coincidono");
        $("#password2").val("");
        valid = false;
    } else {
        $("#password").removeClass("error");
        $("#password2").removeClass("error");
        $("#span3").html("");
        $("#span4").html("");
  }

    return valid;
});
});

</script>


</html>

