<?php 

require 'funzioni.php';
error_reporting(E_ALL);
ini_set( 'display_errors','1'); 



if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_GET['action']) && !empty($_POST['nome_b']) && !empty($_POST['argomento']) && !empty(($_POST['colori'])))
    {
        
        //modifica il blog
        $msgB = " ";
        $id_blog = $_SESSION['blog']['id_blog'];

        $nome_b = trim($_POST['nome_b']);
        $nome_b = mysqli_real_escape_string($con,$nome_b);

        $arg = trim($_POST['argomento']);
        $arg = mysqli_real_escape_string($con,$arg);

        if(preg_match('/^[A-zÀ-ÖØ-öø-ÿ]{5,30}$/', $nome_b) && preg_match('/^[A-zÀ-ÖØ-öø-ÿ]{3,27}$/', $arg))
        {

                $query = "SELECT * from argomento where argomento = ? and id_argomento = padre limit 1";
                $result = mysqli_prepare($con, $query);

                if($result){
                    mysqli_stmt_bind_param($result,'s', $arg);
                    mysqli_stmt_execute($result);
                    $stm = mysqli_stmt_get_result($result); 
                }
                
                //se è presente l'argomento, uso id_argomento già esistente
                if(mysqli_num_rows($stm)>0){

                    $row = mysqli_fetch_assoc($stm);
                    $id_arg = $row['id_argomento'];

                } else {
                    //altrimenti, aggiungo un nuovo argomento nella tabella
                    $query1 = "INSERT INTO argomento (argomento) values(?)";
                    $result1 = mysqli_prepare($con, $query1);

                    if($result1){
                        mysqli_stmt_bind_param($result1,'s', $arg);
                        mysqli_stmt_execute($result1);
                        $stm = mysqli_stmt_get_result($result1);
                    }

                    //devo agigungerlo come argomento, quindi devo ricercare il suo id e metterlo come padre
                    $query2 = "SELECT * FROM argomento WHERE argomento = ? and padre IS NULL limit 1";
                    $result2 = mysqli_prepare($con, $query2);

                    if($result2){
                        mysqli_stmt_bind_param($result2,'s', $arg);
                        mysqli_stmt_execute($result2);
                        $stm = mysqli_stmt_get_result($result2);
                    }

                    if(mysqli_num_rows($stm)>0){

                        $row = mysqli_fetch_assoc($stm);
                        $id_arg = $row['id_argomento'];

                        $query3 = "UPDATE argomento SET padre = $id_arg WHERE id_argomento = '$id_arg' limit 1 ";
                        $result3 = mysqli_query($con, $query3);
                    }
                }

    


            //controllo se è presente un sottoargomento
            if(!empty($_POST['sottoargomento'])){

                $sottoarg = trim($_POST['sottoargomento']);
                $sottoarg = mysqli_real_escape_string($con,$sottoarg);

                if(preg_match('/^[A-zÀ-ÖØ-öø-ÿ]{3,27}$/', $sottoarg)){

                    $query = "SELECT * from argomento where argomento = ? and not id_argomento = padre and padre = $id_arg  limit 1";
                    $result = mysqli_prepare($con, $query);

                    if($result){
                        mysqli_stmt_bind_param($result,'s', $sottoarg);
                        mysqli_stmt_execute($result);
                        $stm = mysqli_stmt_get_result($result);
                    }

                    //se il sottoargomento è presente nel database come sottoargomento dell'argomento attuale, uso id_sottoargomento già esistente
                    if(mysqli_num_rows($stm)>0){

                        $row = mysqli_fetch_assoc($stm);
                        $id_sottoarg = $row['id_argomento'];

                    } else {
                        //altrimenti, aggiungo un nuovo sottoargomento nella tabella
                        $query1 = "INSERT INTO argomento(argomento,padre) values(?, '$id_arg')";
                        $result1 = mysqli_prepare($con, $query1);

                        if($result1){
                            mysqli_stmt_bind_param($result1,'s', $sottoarg);
                            mysqli_stmt_execute($result1);
                            $stm = mysqli_stmt_get_result($result1);
                        }
            
                        $query2 = "SELECT * from argomento where argomento = ? and not id_argomento = padre limit 1";
                        $result2 = mysqli_prepare($con, $query2);

                        if($result2){
                            mysqli_stmt_bind_param($result2,'s', $sottoarg);
                            mysqli_stmt_execute($result2);
                            $stm = mysqli_stmt_get_result($result2);
                        }

                        if(mysqli_num_rows($stm)>0){
                            $row = mysqli_fetch_assoc($stm);
                            $id_sottoarg = $row['id_argomento'];

                        }
                    }
                }
            } 


                //controllo se il coautore è presente nel db 
                if(!empty($_POST['coautore'])){

                    $coautore = trim($_POST['coautore']);
                    $coautore = mysqli_real_escape_string($con,$coautore);
                    
                    $query = "SELECT * from utente where username = ? limit 1";
                    $result = mysqli_prepare($con, $query);

                    if($result){
                        mysqli_stmt_bind_param($result,'s', $coautore);
                        mysqli_stmt_execute($result);
                        $stm = mysqli_stmt_get_result($result);
                    }

                    //se è presente, lo inserisco, sennò no
                    if(mysqli_num_rows($stm) > 0){

                        $row = mysqli_fetch_assoc($stm);
                        $id_coautore = $row['id_utente'];
                    } 
                }


                $tema = $_POST['colori'];

                if (isset($id_coautore) && isset($id_sottoarg)){
                    $query = "UPDATE blog SET nome = ?, argomento = '$id_arg', sottoargomento = '$id_sottoarg', coautore = '$id_coautore', tema='$tema' where id_blog='$id_blog' ";
                
                    } elseif (!isset($id_coautore) && isset($id_sottoarg)){
                    $query = "UPDATE blog SET nome = ?, argomento = '$id_arg', sottoargomento = '$id_sottoarg', coautore = NULL, tema='$tema' where id_blog='$id_blog' ";
                
                    } elseif (isset($id_coautore) && !isset($id_sottoarg)){
                    $query = "UPDATE blog SET nome = ?, argomento = '$id_arg', sottoargomento = NULL, coautore = '$id_coautore', tema='$tema' where id_blog='$id_blog' ";
                
                    } else {
                    $query = "UPDATE blog SET nome = ?, argomento = '$id_arg', sottoargomento = NULL, coautore = NULL, tema='$tema' where id_blog='$id_blog' ";
                    }
                
                    $result = mysqli_prepare($con, $query);
                    if($result){
                        mysqli_stmt_bind_param($result,'s', $nome_b);
                        mysqli_stmt_execute($result);
                    }
                    
                mysqli_stmt_close($result);


                $query = "SELECT * from blog where id_blog = '$id_blog' limit 1";
                $result = mysqli_query($con,$query);

                        if(mysqli_num_rows($result) > 0)
                        {

                            $_SESSION['blog'] = mysqli_fetch_assoc($result);
                        }

                    header("Location: blog.php");
                    die;

        } else {
            $msgB = "Qualcosa è andato storto...";
        }
        
    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_GET['action']) && $_GET['action']=='delete')
    {
        //elimina il blog
        $id_blog = $_SESSION['blog']['id_blog'];
    
        $query2 = "DELETE FROM blog WHERE `blog`.`id_blog` = '$id_blog'";
        $result2 = mysqli_query($con, $query2); 

        header("Location: profilo.php");
        die;

    }


//aggiungo un nuovo post

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_GET['action']) && $_GET['action']=='create_p' && !empty($_POST['titolo_post']) && !empty($_POST['testo_post'])){

        $msg = " ";
        $image_added = false;
        $titolo = $_POST['titolo_post'];
        $contenuto = $_POST['testo_post'];

        $autore = $_SESSION['info']['id_utente'];

        $titolo = trim($_POST['titolo_post']);
        $titolo = mysqli_real_escape_string($con,$titolo);


        $contenuto = trim($_POST['testo_post']);
        $contenuto = mysqli_real_escape_string($con,$contenuto);


        $blog = $_SESSION['blog']['id_blog'];
        $data = date('Y-m-d H:i:s');

        if((strlen($titolo) > 3) && (strlen($titolo) < 51) && (strlen($contenuto) > 100) && (strlen($contenuto) < 1001)){

            if(!empty($_FILES['image']['name'])){

                if($_FILES['image']['size'] < 1073741824){

                    if($_FILES['image']['error'] == 0){

                        if($_FILES['image']['type'] == "image/jpg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/png"){
                            
                            $folder = "uploads/";
                        
                            //il file è stato uploadato
                            $image = $folder . $_FILES['image']['name'];
                            $image = mysqli_escape_string($con, $image);

                            $query = "SELECT immagine from post where immagine = ?";
                            $result = mysqli_prepare($con, $query);

                            if($result){
                                mysqli_stmt_bind_param($result,'s', $image);
                                mysqli_stmt_execute($result);
                                $stm = mysqli_stmt_get_result($result);

                                if(mysqli_num_rows($stm)>0) {
                                    $msg = "Esiste già un file con questo nome!";

                                } else {
                                    if(!move_uploaded_file($_FILES['image']['tmp_name'], $image)){
                                        $msg = "Errore nel caricamento dell'immagine!";
            
                                    } else {
                                        $image_added = true;

                                        if ($image_added = true){

                                            $image = mysqli_escape_string($con, $image);
                
                                            $query1 = "INSERT into post(blog, autore, contenuto, immagine, data_ora, titolo) values('$blog', '$autore', ?, ?, '$data', ?)";
                                            $result1 = mysqli_prepare($con, $query1);
                
                                            if($result1){
                                                mysqli_stmt_bind_param($result1,'sss', $contenuto, $image, $titolo);
                                                mysqli_stmt_execute($result1);
                                                mysqli_stmt_close($result1);
                                                
                                                $titolo ="";
                                                $contenuto = "";
                                                header('Location: blog.php');
                                                die;
                                            }        
                                        }
                                    }
                                }
                            }

                        } else {
                            $msg = "Formato dell'immagine non supportato!";
                        }


                    } else {
                        $msg ="Errore nel caricamento dell'immagine!";
                    }


                } else {
                    $msg = "Immagine troppo grande (max 10MB)";
                }

            } else {

                $query2 = "INSERT into post(blog, autore, contenuto, data_ora, titolo) values('$blog', '$autore', ?, '$data', ?)";

                $result2 = mysqli_prepare($con, $query2);

                if($result2){
                    mysqli_stmt_bind_param($result2,'ss', $contenuto, $titolo);
                    mysqli_stmt_execute($result2);
                    mysqli_stmt_close($result2);

                    //refresh the page 
                    $titolo = "";
                    $contenuto = "";
                    header('Location: blog.php');
                    die;

                }
            }
        }
    }

?>



<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8"> <title>Blog</title> 
<link href="css/skeleton.css" rel="stylesheet" media="screen">
<link href="css/normalize.css" rel="stylesheet" media="screen">
<link href="css/costumize.css" rel="stylesheet" media="screen">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>

$('#sottoargomento1').css('display', 'none');

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

    function cerca_p(val){
        $.ajax({
            url: 'cerca_p.php',
            method: 'POST',
            data: {val:val},

            success:function(){
                window.location.href = 'post.php';
            } 
        });

    }

    function cerca_a(val){
        $.ajax({
            url: 'cerca_a.php',
            method: 'POST',
            data: {val:val},

            success:function(){
                window.location.href = 'profilo.php';
            } 
        });
    };

</script>



<?php 
include "header.php";

include "cerca.php";


if(empty($_SESSION['blog'])){?>

<div class="container">
    <p> Il blog è inesistente!</p>
</div>

<?php    
} else {
    $id_b = $_SESSION['blog']['id_blog'];
    $num_autore = $_SESSION['blog']['autore_blog'];
    $num_argomento = $_SESSION['blog']['argomento'];
    $tema = $_SESSION['blog']['tema'];

    if ($tema == 1){
        $c = "#33C3F0";
        } else if ($tema == 2) {
            $c = "#FF8D8E";
        } else if ($tema == 3) {
            $c = "#54C292";
        } else if($tema == 4) {
            $c = "#DEA0DE";
        }
    
        //mi serve come parametro nelle query di ricerca per non scrivere nuovo codice
        $input = $c;

    $query = "SELECT username FROM utente where id_utente = '$num_autore' limit 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $autore_blog = $row['username'];

    $query = "SELECT argomento FROM argomento where id_argomento = '$num_argomento' limit 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $argomento_blog = $row['argomento'];


    if(!empty($_SESSION['blog']['sottoargomento'])){
        $num_sotto = $_SESSION['blog']['sottoargomento'];;
        $query = "SELECT argomento FROM argomento where id_argomento = '$num_sotto' limit 1";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $sotto_blog = $row['argomento'];
    }

    $num_coautore = $_SESSION['blog']['coautore'];
    if(!empty($_SESSION['blog']['coautore'])){
    $query = "SELECT username FROM utente where id_utente= '$num_coautore' limit 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $coautore_blog = $row['username'];
    }


?>

<?php if(!empty($_GET['action']) && $_GET['action'] == 'delete'):?>
    <div class="container">
        <form method="post">
            <p>Sei sicuro di voler eliminare questo blog?</p>
            <p>Una volta eliminato il blog, anche post e commenti saranno eliminati.</p>
        
            <div class="row">
                <div class="six columns">
                    <a href="blog.php">
                        <input class="button-primary" type="button" value="Indietro">
                    </a>
                </div>

                <div class="six columns">
                    <input class="button" type="submit" value="Elimina il blog" id="submit">
                </div>
            </div>

        </form>
    </div> 



<?php elseif(!empty($_GET['action']) && $_GET['action'] == 'edit'):?>
    <div class="container">
                <h5>Modifica il blog</h5>
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

                    <input class="u-full-width" type="text" placeholder="Nome blog" id="nome_b" name="nome_b" maxlength="50" value="<?php echo $_SESSION['blog']['nome']?>">

                </br></br>


                <div class="row">
                    <div class="six columns">
                    <label for="argomento">Argomento</label>
                </div>

                <div class="six columns">
                <span id="span2" class="error1"></span>
                </div>

                </div>

                    <input class="u-full-width" type="text" placeholder="Inserisci l'argomento" id="argomento" name="argomento" maxlength="50" value="<?php echo $argomento_blog?>">
                    </br>
                    <div class="suggerimento_a" id="suggerimento_a"></div>
                    

                    <?php if(!empty($sotto_blog)): ?>
                        <script>$('#sottoargomento1').css('display', 'block');</script>
                        <?php else: ?>
                            <script>$('#sottoargomento1').css('display', 'none');</script>
                            <?php endif; ?>
                    </br>
                    <div id="sottoargomento1">
                    <div class="row">
                        <div class="six columns">
                        <label for="sottoargomento">Sottoargomento (opzionale)</label>
                        </div>

                        <div class="six columns">
                        <span id="span3" class="error1"></span>
                        </div>
                    </div>


                    <input class="u-full-width" type="text" placeholder="Inserisci un sottoargomento" id="sottoargomento" name="sottoargomento" maxlength="50" value="<?php if(isset($sotto_blog)){echo $sotto_blog;} ?>">
                    <div class="suggerimento_s" id="suggerimento_s"></div>
                    </div>

                    </br>

                  

                    <label for="coautore">Coautore (opzionale)</label>
                   
                    <input class="u-full-width" type="text" placeholder="Inserisci il nome del coautore" id="coautore" name="coautore" value="<?php if(isset($coautore_blog)){echo $coautore_blog;} ?>">
                    <div class="suggerimento_c" id="suggerimento_c"></div>
                    </br>


    
                    
                    <label for="tema">Colore tema</label>

                    </br>

                    <div class="container" id="tema" >
                        <div class="row">

                        <div class="three columns active" >
                        <label for="1" style="background-color: #33C3F0">.</label>
                        <input type="radio" value="1" name="colori" <?php if (isset($tema) && $tema=="1") {echo "checked";}?>></input></div>


                        <div class="three columns active" >
                        <label for="2" style="background-color: #FF8D8E">.</label>
                        <input type="radio" value="2" name="colori" <?php if (isset($tema) && $tema=="2") {echo "checked";}?>></input></div>

                        <div class="three columns active" >
                        <label for="3" style="background-color: #54C292">.</label>
                        <input type="radio" value="3" name="colori" <?php if (isset($tema) && $tema=="3") {echo "checked";}?>></input></div>

                        <div class="three columns active">
                        <label for="4" style="background-color: #DEA0DE">.</label>
                        <input type="radio" value="4" name="colori" <?php if (isset($tema) && $tema=="4") {echo "checked";}?>></input></div>

                        </div>
                    </div>
        </br>
        </br>
        </br>
                    <div class="row">
                    <div class="two columns">
                        <a href="blog.php">
                            <input class="button" type="button" value="Annulla">
                        </a>
                    </div>

                    <div class="eight columns" style="color: white">a
                    </div>

                    <div class="two columns">
                        <input class="button-primary" type="submit" value="Modifica il blog" id="submit_blog">
                    </div>

                    </div>
            </form>
        </div>



</br>

<?php else :?>
<div class="container" style="border: solid 4px <?php echo $c?>">
    <div class="container" style="padding-top: 40px; padding-bottom: 40px">    
        <p><span class="round3" style="background-color: <?php echo $c?>"><?php echo "#", $argomento_blog;?></span> &ensp;<?php if(isset($sotto_blog)){?><span class="round3" style="background-color: <?php echo $c?>"><?php echo "#", $sotto_blog;}?></span></p>
</br></br><h3 style="color: <?php echo $c?>"><?php echo $_SESSION['blog']['nome'];?></h3>
            </br></br>
                <h5 style="color:#5f5f5f"><strong>Blog creato da: </strong><span class="c" onclick="cerca_a('<?php echo $num_autore?>')"><?php echo $autore_blog;?></span>
                <?php if(isset($coautore_blog)):?>
                <span class="c" onclick="cerca_a('<?php echo $num_coautore ?>')">, <?php echo $coautore_blog;?></span>
                <?php endif;?>
                </h5>


    
<?php if(!empty($_SESSION['info']) && $num_autore == $_SESSION['info']['id_utente']):?>
    </br></br></br>
        <a class="button" href="blog.php?action=delete">Elimina il blog</a>
        <a class="button" href="blog.php?action=edit">Modifica il blog</a>

</br></br></br>
<?php endif;?>






    <?php if(!empty($_SESSION['info']) && ($_SESSION['info']['id_utente'] == $num_autore || $_SESSION['info']['id_utente'] == $num_coautore)):?>
        <?php if(!empty($_GET['action']) && $_GET['action'] == 'create_p'):?>
            <div class="container">
                <h5> Crea un nuovo post </h5>
                    <form method="POST" enctype="multipart/form-data" id="data">
                        <div class="row">
                            <div class="six columns">
                                <label for="image">Immagine</label>
                            </div>
                            <div class = "six columns">
                                <span id="span5" class="error1"><?php if(isset($msg)){echo $msg;}?></span>
                            </div>
                        </div>
                        
                        Aggiungi un'immagine:<input type="file" name="image" id="image">

                        </br></br>

                        <div class="row">
                            <div class = "six columns">
                                <label for="titolo_post">Titolo</label>
                            </div>
                            <div class = "six columns">
                                <span id="span4" class="error1"></span>
                            </div>
                        </div>
                        <input class="u-full-width" type="text" placeholder="Inserisci un titolo" id="titolo_post" name="titolo_post" maxlength="50" value="<?php if(isset($titolo)){echo $titolo;}?>"></input>
                        </br></br>


                        <div class="row">
                            <div class = "six columns">
                                <label for="testo_post">Post</label>
                            </div>
                            <div class = "six columns">
                                <span id="span6" class="error1"></span>
                            </div>
                        </div>
                        <textarea class="u-full-width" placeholder="Inizia a scrivere..." id="testo_post" name="testo_post" maxlength="1000"><?php if(isset($contenuto)){echo $contenuto;}?></textarea>

                    <div class="row">
                        <div class="six columns">
                            <a href="blog.php">
                                <input class="button" type="button" value="Annulla">
                            </a>
                        </div>
                        
                        <div class="six columns">
                            <input class="button-primary" type="submit" value="Pubblica" id="submit_post">
                        </div>

                    </div>
            </form>
        </div>

        <?php else:?>
            <a href='blog.php?action=create_p'>
                <button class="button-primary" style="background-color: white; color: black; border-color: <?php echo $c?>;">Aggiungi un post</button>
            </a>
        <?php endif; endif;?>
        </br></br></br>

    <h5>Tutti i posts</h5>
    <div class="container u-full-width" id="result_postB">
    </div>
    
    <div class="container u-full-width" id="pagination_postB">
    </div>

    <?php 
    $limit = 2;
    $sql = "SELECT count(*) as 'totale' from post where blog = '$id_b'";

    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);
    
    $total_rows=$row['totale'];
    $total_pages= ceil($total_rows / $limit);
    
    if($total_rows < 1):
        echo "Nessun post presente!";
        else:?>
        
        <script>
        selectPage("postB",1,"<?php echo $input ?>");
        paginazione("postB",<?php echo $total_pages ?>,"<?php echo $input ?>",1);</script>
        <?php endif;
        ?>
</br>

<?php 

endif;
}
?>
    </div>
        </div>
<body>
<script src= "script/cerca.js"></script>
<script src = "script/form_blog_validation.js"></script>
<script src = "script/form_post_validation.js"></script>
</html>
