<?php

include 'funzioni.php';

if(isset($_POST['val'])){
    
    $id_post = $_POST['val'];
    $query = "SELECT * FROM post WHERE id_post = '$id_post' LIMIT 1";
    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) > 0){
        $post_data = mysqli_fetch_assoc($result);

        $_SESSION['post'] = $post_data;
    }

}

?>