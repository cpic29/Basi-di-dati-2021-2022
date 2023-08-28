<?php 


include 'funzioni.php';

if(isset($_POST['input'])){

    $input = $_POST['input'];

    $query = "SELECT nome FROM blog WHERE nome = '$input'";

    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) > 0){

        echo 1;

    } else {

        echo 2;
    }
}

?>