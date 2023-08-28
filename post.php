<?php 

require 'funzioni.php';
error_reporting(E_ALL);
ini_set( 'display_errors','1'); 

require_once 'check_post.php';

$input = "";

function br2nl( $input ) {
    return preg_replace( '/\<br.*\>/Ui', '', $input );
}


?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8"> <title>Post</title> 
<link href="css/skeleton.css" rel="stylesheet" media="screen">
<link href="css/normalize.css" rel="stylesheet" media="screen">
<link href="css/costumize.css" rel="stylesheet" media="screen">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<script>

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

  });
};

function delete_c(id_commento){
    var id = id_commento;
    $.ajax({
        url:'commenti.php',
        method:'POST',
        data:{
            commento: id,
        },

        success:function(){
            window.location.reload(); 
        },

        error:function(){
            console.log("impossibile eliminare il commento");
        }
    });
}

function delete_img(immagine, id){
    var img = immagine;
    var id = id;
    $.ajax({
        url:'cancella.php',
        method: 'POST',
        data:{
            img: img,
            id: id,
        },

        success:function(data){
            window.location.reload(); 
        }
    })
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

function like(azione,us, p){
    $.ajax({
        url: 'like.php',
        method: 'POST',
        data:{
            azione: azione,
            us:us, 
            post:p,
        },

        success:function(){
            window.location.reload();
        }
    })
}

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


<?php 
include "header.php";
include "cerca.php";


if(!empty($_SESSION['post'])){
    $id_p = $_SESSION['post']['id_post'];
    $num_autore = $_SESSION['post']['autore'];
    $num_blog = $_SESSION['post']['blog'];
    $data_ora = $_SESSION['post']['data_ora'];
    $contenuto = $_SESSION['post']['contenuto'];
    $titolo = $_SESSION['post']['titolo'];

    $immagine = $_SESSION['post']['immagine'];

    $query = "SELECT username FROM utente where id_utente = '$num_autore' limit 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $autore_post = $row['username'];

    $query = "SELECT nome FROM blog where id_blog = '$num_blog' limit 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $blog = $row['nome'];

    $query = "SELECT count(*) as 'likes' from likes where post_id = '$id_p' ";
    $result = mysqli_query($con, $query);

    $row = mysqli_fetch_assoc($result);
    
    $likes = $row['likes'];

?>

</br>

<?php if(!empty($_GET['action']) && $_GET['action'] == 'delete'):?>
    <div class="container">
        <form method="post">
            <p>Sei sicuro di voler eliminare questo post?</p>
            <p>Una volta eliminato il post, anche i commenti saranno eliminati.</p>
        
            <div class="row">
                <div class="six columns">
                    <a href="post.php">
                        <input class="button-primary" type="button" value="Indietro">
                    </a>
                </div>

                <div class="six columns">
                    <input class="button" type="submit" value="Elimina il post" id="submit">
                </div>
            </div>

        </form>
    </div> 

    <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'edit'):?>
        <div class="container">
            <h5> Modifica il post </h5>
            
            <form method="POST" enctype="multipart/form-data" id="data">
                        <div class="row">
                            <div class="six columns">
                                <label for="image">Immagine</label>
                            </div>
                            <div class = "six columns">
                                <span id="span5" class="error1"><?php if(isset($msg)){echo $msg;}?></span>
                            </div>
                        </div>

                Modifica immagine:<input type="file" name="image"> 
                <?php if(isset($immagine)):?> 
                    <p><img src="<?php echo $immagine; ?>" style="width: 150px;height: 150px;" alt=""></p>
                    <input type ="button" value="Elimina immagine" onClick = "delete_img('<?php echo $immagine;?>', '<?php echo $id_p;?>')">                
                <?php endif; ?>

                </br>

                <div class="row">
                            <div class = "six columns">
                                <label for="titolo_post">Titolo</label>
                            </div>
                            <div class = "six columns">
                                <span id="span4" class="error1"></span>
                            </div>
                        </div>
                        <input class="u-full-width" type="text" placeholder="Inserisci un titolo" id="titolo_post" name="titolo_post" maxlength="50" value="<?php if(isset($titolo)){echo htmlspecialchars($titolo);}?>"></input>
                        </br></br>

                        <div class="row">
                            <div class = "six columns">
                                <label for="testo_post">Post</label>
                            </div>
                            <div class = "six columns">
                                <span id="span6" class="error1"></span>
                            </div>
                        </div>
                        <textarea class="u-full-width" placeholder="Inizia a scrivere..." id="testo_post" name="testo_post" maxlength="1000"><?php $contenuto=htmlspecialchars($contenuto); $contenuto = nl2br(stripcslashes($contenuto)); echo br2nl($contenuto);?></textarea>

                <div class="row">
                        <a href="post.php">
                            <input class="button" type="button" value="Annulla modifiche">
                        </a>
        
                        <input class="button-primary" type="submit" value="Salva modifiche" id="submit_post">
                </div>
  
            </form>
        </div>

        <?php else: ?>
<div class="container">
    
    <p class="c" style="color:#888; text-align:left" onclick="cerca_b('<?php echo $num_blog?>')"><?php echo $blog;?></p>

    <p style="color:#888; text-align:right"><?php echo date("jS M, Y", strtotime($data_ora));?></p>

    <h3  style="text-align:center;"><?php echo htmlspecialchars($titolo); ?></h3>
        </br>

    <p class="u-float-left c" onclick="cerca_a('<?php echo $num_autore?>')";>Autore:  <strong><?php echo $autore_post;?></strong><p>
    <?php 

    if(!empty($_SESSION['info']) && $num_autore != $_SESSION['info']['id_utente']){
        
        $log_utente =  $_SESSION['info']['id_utente'];

        $like = "SELECT * from likes where id_user = '$log_utente' AND post_id = '$id_p'";

        $result= mysqli_query($con, $like);

    if(mysqli_num_rows($result) < 1):?>
    </br>
    <img src="heart.png" style="width: 20px;height: 20px;" alt="" onclick="like('L','<?php echo $log_utente?>','<?php echo $id_p?>')">
    <?php else:?>
    </br>
    <img src="like.png" style="width: 20px;height: 20px;" alt="" onclick="like('U','<?php echo $log_utente?>','<?php echo $id_p?>')">

    <?php endif;
    }

    
    if($likes < 2){?>
        <p style="color:#888;"><?php echo $likes ?> like</p>
        <?php }else{ ?>
        <p style="color:#888;"><?php echo $likes ?> likes</p>

    
    <?php }
    
    if(!empty($_SESSION['info']) && $num_autore == $_SESSION['info']['id_utente']):?>

                    <a class="button" href="post.php?action=edit">Modifica il post</a>

                    <a class="button" href="post.php?action=delete">Elimina il post</a>

    </br></br></br>
    
    <?php endif;
    
    if(isset($immagine)):?> 
    <div class="container u-full-width">
        <div class="row">
            <div class="four columns j">.</div>
            <div class="four columns "><img src="<?php echo $immagine; ?>" style="width: 250px;height: 250px;" alt=""></div>
            <div class="four columns j">.</div>
    </div>
    </br></br></br>
    <?php endif; ?>

    <p style="text-align:left;">
    <?php $contenuto = htmlspecialchars($contenuto);
    $contenuto = nl2br(stripcslashes($contenuto));
    echo $contenuto; ?></p>

</div>



<div class="container">
    <form method="post">
        <p style="color:#888; text-align:right" id="conta" readonly></p>
        <label for="commento">Commenti</label>
        <?php if(!empty($_SESSION['info'])):?>
            <textarea class="u-full-width" placeholder="Lascia un commento..." id="commento" name="commento" maxlength="140"></textarea>
            <input class="button-primary" type="submit" value="Pubblica" id="submit">
    </form>
        <?php else:?>
            <p><a href="login.php" id="login">Accedi</a> o <a href="registrazione.php" id="registrazione">registrati</a> per lasciare un commento </p>
        <?php endif;?>
</div>

        </br></br>
<div class="container">
    <div class="row" id="result_c"></div>
    </div>
    
    <div class="container">
    <div class="row" id="pagination_c"></div>
    </div>

</div>

<?php 

$limit = 5;
$sql = "SELECT count(*) as 'totale' FROM commento WHERE post_commento = '$id_p'";

$query = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($query);

$total_rows=$row['totale'];

$total_pages= ceil($total_rows / $limit);

if($total_rows < 1):
    ?>
    <div class="container">Nessun commento presente!</div>
    <?php
else:?>

<script>
selectPage("c",1, "<?php echo $input; ?>");
paginazione("c",<?php echo $total_pages; ?>,"<?php echo $input; ?>");</script>

<?php endif;

endif;

} else {
    ?>
    <div class="container">Il post è inesitente</div>
<?php
}

include 'footer.php';
?>

</div>

</div>
</body>
<?php 
    include_once 'footer.php';
?>

<script src = "script/form_post_validation.js"></script>
</html>

