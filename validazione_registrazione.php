<?php  


include 'funzioni.php';

if(isset($_POST['input1']) && isset($_POST['input2'])){

    $input = trim($_POST['input1']);
    $input = stripslashes($input);

    $cosa = $_POST['input2'];

    if(isset($_POST['input3'])){
        $utente = trim($_POST['input3']);
        $utente = stripslashes($utente);

        $utente = mysqli_real_escape_string($con,$utente);

        $query = "SELECT * from utente where username = ? limit 1";
        $result = mysqli_prepare($con, $query);

        if($result){
            mysqli_stmt_bind_param($result,'s', $utente);
            mysqli_stmt_execute($result);
            $stm = mysqli_stmt_get_result($result);

        if(mysqli_num_rows($stm)>0){

            $row = mysqli_fetch_assoc($stm);
            $pas = $row['pass']; //password attuale

            if(password_verify($input, $pas)){
                echo 1;
            } else {
                echo 2;
            }

        }
        }

    } else {

        $input = mysqli_real_escape_string($con,$input);


        if($cosa == "username") {

        $query = "SELECT * FROM utente WHERE username = ? limit 1";
        $result = mysqli_prepare($con, $query);


        } else if ($cosa == "email"){

        $query = "SELECT * FROM utente WHERE email = ? limit 1";
        $result = mysqli_prepare($con, $query);

        }

        

        if($result){
            mysqli_stmt_bind_param($result,'s', $input);
            mysqli_stmt_execute($result);
            $stm = mysqli_stmt_get_result($result);
    
        }


        if(mysqli_num_rows($stm) > 0){

            if(isset($_POST['id'])){
                $id = $_POST['id'];
                $row = mysqli_fetch_assoc($stm);

                if($row['id_utente'] != $id){
                    echo 1;
                } else {
                    echo 2;
                }

            } else {
                echo 1;
            }
        } else {
            echo 2;
        }
    }

}
?>
