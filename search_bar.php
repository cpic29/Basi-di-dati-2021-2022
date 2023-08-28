<?php 

require 'funzioni.php';
error_reporting(E_ALL);
ini_set( 'display_errors','1'); 


if(empty($_POST["s"])){
    header("Location: home.php");
    die;
} else {
    $input = $_POST["s"];
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8"> <title>Ricerca</title> 
<link href="css/skeleton.css" rel="stylesheet" media="screen">
<link href="css/normalize.css" rel="stylesheet" media="screen">
<link href="css/costumize.css" rel="stylesheet" media="screen">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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

        error:function(){
          console.log("si è verificato un errore");
        }

      });
    };
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
?>

<div class="container">

    <h6> Hai cercato "<strong><?php echo $input ?></strong>"</h6>
    
</br></br>
    <h5><strong>Posts</strong></h5>

    <?php 
    $limit = 2;
    $sql = "SELECT count(*) as 'totale' from post where titolo like '{$input}%'";

    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);
    
    $total_rows=$row['totale'];
    $total_pages= ceil($total_rows / $limit);
    
    if($total_rows < 1):
        echo "</br>Nessun post presente!";
        else:?>
        <div class="container">
        <div class="row" id="result_post"></div>
        </div>
        
        <div class="container">
        <div class="row" id="pagination_post"></div>
        </div>
        
        <script>
        selectPage("post",1,"<?php echo $input; ?>");
        paginazione("post",<?php echo $total_pages; ?>,"<?php echo $input; ?>");</script>
        <?php endif;
        ?>

</br><br><br>
    <h5><strong>Blogs</strong></h5>

    <?php 
    $limit = 2;
    $sql = "SELECT count(*) as 'totale' from blog where nome like '{$input}%' ";

    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);
    
    $total_rows=$row['totale'];
    $total_pages= ceil($total_rows / $limit);
    
    if($total_rows < 1):
        echo "</br>Nessun blog presente!";
        else:?>
            <div class="container">
            <div class="row" id="result_blog"></div>
            </div>
            
            <div class="container">
            <div class="row" id="pagination_blog"></div>
            </div>
        
        <script>
                selectPage("blog",1,"<?php echo $input?>");
                paginazione("blog",<?php echo $total_pages; ?>,"<?php echo $input; ?>");</script>
        <?php endif;
        ?>
    
    <?php if(!empty($_SESSION['info'])):?>
        </br><br><br>

        <h5><strong>Utenti</strong></h5>

        <?php 
        $limit = 2;
        $sql = "SELECT count(*) as 'totale' from utente where username like '{$input}%' ";

        $query = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($query);
        
        $total_rows=$row['totale'];
        $total_pages= ceil($total_rows / $limit);
        
        if($total_rows < 1):
            echo "</br>Nessun utente presente!</br>";
            else:?>
                <div class="container">
                <div class="row" id="result_u"></div>
                </div>
        
                <div class="container">
                <div class="row" id="pagination_u"></div>
                </div>
            
                <script>
                    selectPage("u",1, "<?php echo $input?>");
                    paginazione("u",<?php echo $total_pages; ?>,"<?php echo $input; ?>");</script>
        <?php endif;
        endif;?>

</br><br><br>
<h5><strong>Argomenti</strong></h5>
<div class="container">
    <div class="row" id="result_a"></div>
    </div>
    
    <div class="container">
    <div class="row" id="pagination_a"></div>
    </div>

    <?php 
    $limit = 2;
    $sql = "SELECT count(*) as totale from blog where argomento IN (SELECT id_argomento from argomento where argomento like '{$input}%') OR sottoargomento IN (SELECT id_argomento from argomento where argomento like '{$input}%') ";

    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($query);
    
    $total_rows=$row['totale'];
    $total_pages= ceil($total_rows / $limit);
    
    if($total_rows < 1):
        echo "</br>Nessun argomento presente!";
        else:?>
        
        <script>
            selectPage("a",1, "<?php echo $input?>");
            paginazione("a",<?php echo $total_pages; ?>,"<?php echo $input; ?>");</script>
     <?php endif;

    include_once 'footer.php';
?>