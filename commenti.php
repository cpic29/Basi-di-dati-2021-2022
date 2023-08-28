<?php

include 'funzioni.php';


if (isset($_POST["commento"])) { 

    $commento = $_POST["commento"];

    $query = "DELETE FROM commento WHERE id_commento = '$commento' limit 1";

    $result = mysqli_query($con, $query);

} 



?>

