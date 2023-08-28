<?php 


include 'funzioni.php';

if(isset($_POST['input'])){

    $input = trim($_POST['input']);
    $input = mysqli_real_escape_string($con, $input);

    $autore = $_SESSION['info']['username'];

    $query = "SELECT * FROM utente WHERE username like CONCAT('%',?,'%') and username != '$autore' ORDER BY username LIMIT 0,6";
    $result = mysqli_prepare($con, $query);

    if($result){

        mysqli_stmt_bind_param($result,'s', $input);
  
        mysqli_stmt_execute($result);
  
        $stm = mysqli_stmt_get_result($result);
  
        if(mysqli_num_rows($stm) > 0){
?>

    <ul> 
        <?php
            while($row = mysqli_fetch_assoc($stm)){
                $co = $row['username'];
        ?>

        <li class="li_c" onClick="selectCo('<?php echo $co; ?>')"><?php echo $co; ?> </li>

        <?php } 

        } else {
            echo "Utente non presente";
        }
    };
};
        ?>
    
    </ul>
        