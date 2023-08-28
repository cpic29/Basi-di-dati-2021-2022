<?php
include 'funzioni.php';

function blog_pagination($con, $q){?>
    <div class="row u-full-width">
    <?php while($row = mysqli_fetch_assoc($q)){

        $id_blog = $row['id_blog'];
        $id_autore=$row['autore_blog'];
    
        $nome = $row['nome'];
        $tema = $row['tema'];
            
        $id_arg = $row['argomento'];
        $id_sotto = $row['sottoargomento'];

        if ($tema == 1){
            $c = "#33C3F0";
            } else if ($tema == 2) {
                $c = "#FF8D8E";
            } else if ($tema == 3) {
                $c = "#54C292";
            } else if($tema == 4) {
                $c = "#DEA0DE";
            }

            $query = "SELECT username FROM utente where id_utente = '$id_autore' limit 1";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $autore_blog = $row['username'];


        if(!empty($id_sotto)){
            $query = "SELECT argomento FROM argomento where id_argomento = '$id_sotto' limit 1";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $sotto_blog = $row['argomento'];
        }
        
        $query = "SELECT argomento FROM argomento where id_argomento = '$id_arg' limit 1";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $arg_blog = $row['argomento'];
    
    
        ?>
            <div class="six columns c" style="border: 2px solid <?php echo $c?>" onclick="cerca_b('<?php echo $id_blog ?>')">
            <div class="container">
                <h5 style="padding-top:25px;"><strong><?php echo $nome?></strong></h5>
                <p><strong>Autore:</strong> <?php echo $autore_blog ?></p>
                <p><strong>#<?php echo $arg_blog ?></strong> &ensp; <?php if(!empty($sotto_blog)):?><strong>#<?php echo $sotto_blog; endif;?></strong></p>
            </div>
            </div>
    <?php 
    }
}

function post_pagination($con, $q, $i){?>
    <div class="row u-full-width">

            <?php while($row = mysqli_fetch_assoc($q)){
                $id_autore = $row['autore'];
                $titolo = $row['titolo'];
                $id_post = $row['id_post'];
                $data_ora = $row['data_ora'];

                $query = "SELECT username FROM utente where id_utente = '$id_autore' limit 1";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_assoc($result);
                $autore_post = $row['username'];

                ?>

                <div class="six columns c" style="border: solid 2px <?php echo $i ?>;" onclick="cerca_p('<?php echo $id_post?>')">
                    <div class="container">
                            <h6 style="padding-top:30px;"><strong><?php echo $titolo?></strong></h6>
                            <div class="row">
                                    <div class="one column">
                                        <img src="user.png" style="width: 13px;height: 13px;" alt="">
                                    </div>
                                    <div class="eleven columns">
                                        <p>&ensp; <?php echo $autore_post ?></p>
                                    </div>
                            </div>
                            <p class="u-pull-right" style="color: #888"><?php echo date("jS M, Y", strtotime($data_ora));?></p>
                    </div>
                </div>

        <?php } 
        

}
    



if (isset($_GET["input"]) && isset($_GET["result"]) && isset($_GET["i"])){ 
    $page = $_GET["input"]; 
    $result = $_GET["result"];
    $i = $_GET["i"];
} else { 
    $page=1; 
}


$start_from = ($page-1) * 2;


if($result == "blog") {

    $i = trim($_GET['i']);
    $i = mysqli_real_escape_string($con, $i);


    $sql = "SELECT * FROM blog where nome like CONCAT('%',?,'%') LIMIT $start_from, 2";
    $q_result = mysqli_prepare($con, $sql);

        if($q_result){

            mysqli_stmt_bind_param($q_result,'s', $i);
      
            mysqli_stmt_execute($q_result);
      
            $stm = mysqli_stmt_get_result($q_result);
        
    
            if(mysqli_num_rows($stm) > 0){
        ?>
    </br>
    <?php blog_pagination($con, $stm);?>
    
        </div>
        <?php
            } 
        }
}

    if($result == "post"){
        $i = trim($_GET['i']);
        $i = mysqli_real_escape_string($con, $i);
    

        $sql = "SELECT * FROM post where titolo like CONCAT('%',?,'%') ORDER BY data_ora ASC LIMIT $start_from, 2";
        $q_result = mysqli_prepare($con, $sql);

        if($q_result){

            mysqli_stmt_bind_param($q_result,'s', $i);
      
            mysqli_stmt_execute($q_result);
      
            $stm = mysqli_stmt_get_result($q_result);
        
    
            if(mysqli_num_rows($stm) > 0){
            ?>
        </br>
            <?php 
            $i = "#222";
            post_pagination($con, $stm, $i);
        
            ?>
            </div>
            <?php
            }
        }
    }

    if($result == "u") {

        $i = trim($_GET['i']);
        $i = mysqli_real_escape_string($con, $i);

        $sql = "SELECT * FROM utente where username like CONCAT('%',?,'%') LIMIT $start_from, 2";
        $q_result = mysqli_prepare($con, $sql);

        if($q_result){

            mysqli_stmt_bind_param($q_result,'s', $i);
      
            mysqli_stmt_execute($q_result);
      
            $stm = mysqli_stmt_get_result($q_result);
        
    
            if(mysqli_num_rows($stm) > 0){
            ?>
        </br>
            <?php while($row = mysqli_fetch_assoc($stm)){
                $id_utente = $row['id_utente'];
                $username = $row['username'];            
                ?>
                <div class="row c">
                    <div class="four columns" onclick="cerca_a('<?php echo $id_utente?>')">
                    <h5><?php echo $username ?></h5>
            </br>
                    </div>
        
            <?php 
                }
            }
        }
        
            ?>
        
                </div>
            <?php

    }


    if($result == "a"){
        $i = trim($_GET['i']);
        $i = mysqli_real_escape_string($con, $i);

        $sql ="SELECT * from blog where argomento IN (SELECT id_argomento from argomento where argomento like CONCAT('%',?,'%')) OR sottoargomento IN (SELECT id_argomento from argomento where argomento like CONCAT('%',?,'%')) LIMIT $start_from, 2";    
        $q_result = mysqli_prepare($con, $sql);

        if($q_result){

            mysqli_stmt_bind_param($q_result,'ss', $i, $i);
      
            mysqli_stmt_execute($q_result);
      
            $stm = mysqli_stmt_get_result($q_result);
        
    
            if(mysqli_num_rows($stm) > 0){
            ?>
        </br>

        <?php blog_pagination($con, $stm);?>
        
            </div>
            <?php
            } 

        }
    }

    if($result == "b"){
        $sql = "SELECT * FROM blog where autore_blog = '$i' LIMIT $start_from, 2";
        $q_result = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($q_result) > 0){
            ?>
        </br>
        <?php blog_pagination($con, $q_result);?>
        
            </div>
            <?php
        } 
    }

    if($result == "r"){
        $sql = "SELECT * FROM post ORDER BY data_ora DESC LIMIT $start_from, 2";
        $q_result = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($q_result) > 0){
    ?>
    </br>
    <?php 
            $i = "#222";
            post_pagination($con, $q_result, $i);
            ?>
              </div>
    <?php

        }
    }

    if($result == "c"){
        $id_p = $_SESSION['post']['id_post'];
        $start_from = ($page-1) * 5;
        $sql = "SELECT * FROM commento WHERE post_commento = '$id_p' ORDER BY data_ora DESC LIMIT $start_from, 5";
        $q_result = mysqli_query($con, $sql);

        if(mysqli_num_rows($q_result) > 0){
        ?>

        <?php while ($commenti = mysqli_fetch_assoc($q_result)){
            $id_autore = $commenti['autore_commento'];
            $id_commento = $commenti['id_commento'];

            $query = "SELECT username FROM utente where id_utente = '$id_autore' limit 1";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $autore_commento = $row['username'];?>
            
            <div class="container">
                <div class="row">
                    <div class="five columns c" onclick="cerca_a('<?php echo $id_autore ?>')"><?php echo $autore_commento;?></div>
                    <div class="five columns" style="text-align:right;"><?php echo date("jS M, Y", strtotime($commenti["data_ora"]));?></div>
                    <div class="two columns">

                <?php if(!empty($_SESSION['info']) && $id_autore == $_SESSION['info']['id_utente']):?>

                        <img src="delete_remove_bin_icon-icons.com_72400-2.png" style="width: 30px; height: auto;" alt="Elimina Commento" onclick="delete_c(<?php echo $id_commento;?>)">
                        
                <?php endif;?>
                    </div>
                </div>
                </br>
                <p><?php $t = $commenti["testo"];  $t = htmlspecialchars($t); $t = nl2br(stripcslashes($t)); echo $t;?></p>

                </br></br>
            </div>
    <?php       }
            }

    }

    if($result == "postB"){
        $id_b = $_SESSION['blog']['id_blog'];
        $sql = "SELECT * FROM post WHERE blog = '$id_b' ORDER BY data_ora DESC LIMIT $start_from, 2";

        $q_result = mysqli_query($con, $sql);
        if(mysqli_num_rows($q_result) > 0){
            ?>
        </br>   
        <?php post_pagination($con, $q_result, $i) ?>
           </div>
                
<?php
        }


    }

    if($result == "bc"){
        $sql = "SELECT * FROM blog where coautore = '$i' LIMIT $start_from, 2";
        $q_result = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($q_result) > 0){
            ?>
        </br>
        <?php blog_pagination($con, $q_result);?>
        
            </div>
            <?php
        } 
    }


?>


