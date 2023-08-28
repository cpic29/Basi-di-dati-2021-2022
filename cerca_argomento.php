<?php 


include 'funzioni.php';

if(isset($_POST['input'])){

    $input = trim($_POST['input']);
    $input = mysqli_real_escape_string($con, $input);

    $query = "SELECT * FROM argomento WHERE argomento like CONCAT('%',?,'%') and id_argomento = padre ORDER BY argomento LIMIT 0,6";
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
                $arg = $row['argomento'];
        ?>

        <li class="li_c" onClick="selectArg('<?php echo $arg; ?>')"><?php echo $arg; ?> </li>

        <?php };         

        }

    };
};
        ?>
    
    </ul>
        