<?php
require 'funzioni.php';

$input = "";

?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8"> <title>Home</title> 

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
<body>

<?php 
include "header.php";
include "cerca.php";
?>

<div class="container u-full-width">
  <p> Visita il sito per trovare post interessanti da leggere.</p>
  <?php if (empty($_SESSION['info'])):?>
  <p><a href="login.php" id="login">Accedi</a> o <a href="registrazione.php" id="registrazione">registrati</a> per pubblicare anche tu un post!</p>
  </br></br>
  <?php endif;?>



    <h5>Pubblicati di recente</h5>
    <div class="container">
        <div class="row" id="result_r"></div>
    </div>


    <div class="container">
        <div class="row" id="pagination_r"></div>
    </div>


<?php 

  $limit = 2;

  $total_rows= 10;

  $total_pages= ceil($total_rows / $limit);
?>

  <script>
    selectPage("r",1,"<?php echo $input; ?> ");
    paginazione("r",<?php echo $total_pages;?>, "<?php echo $input; ?>");
  </script>



</div>




</body>

<?php 
    include_once 'footer.php';
?>
