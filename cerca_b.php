<?php

include 'funzioni.php';

if(isset($_POST['val'])){

    $id_blog = $_POST['val'];
    $query = "SELECT * FROM blog WHERE id_blog = '$id_blog' LIMIT 1";
    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) > 0){

        $blog_data = mysqli_fetch_assoc($result);

        $_SESSION['blog'] = $blog_data;
    }

}

?>