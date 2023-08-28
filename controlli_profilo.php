<?php 

require 'funzioni.php';
error_reporting(E_ALL);
ini_set( 'display_errors','1'); 

check_login();
$msg2 = "Qualcosa è andato storto...";

//eliminare il profilo
    if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_GET['action']) && $_GET['action']=='delete')
    {
        //elimina il profilo
        $id = $_SESSION['info']['id_utente'];
        $query = "DELETE from utente where id_utente = '$id' limit 1"; 
        $result = mysqli_query($con, $query); 
    

        header("Location: logout.php");
        die;

    }
    //modificare il profilo
    elseif($_SERVER['REQUEST_METHOD'] == "POST"  && !empty($_GET['action']) && $_GET['action']=='edit')
    {
        $msg = "";

        $user_name = trim($_POST['username']);
        $email = trim($_POST['email']);
        $codf = trim($_POST['codiceFiscale']);
        $tel = trim($_POST['telefono']);

        $id = $_SESSION['info']['id_utente'];

        if(!empty($user_name) && !empty($email) && (!empty($codf)) && (!empty($tel)))
        {
            if((strlen($user_name) > 2) && (strlen($user_name) < 16) && (strlen($email) > 1) && (strlen($email) < 51) && (strlen($codf) > 14) && (strlen($codf) < 17) && (strlen($tel) > 8) && (strlen($tel) < 11))
            {
                if((preg_match('/^[a-zA-Z]+[a-zA-Z0-9]*$/', $user_name)) && (preg_match('/^.+@.+\..+$/', $email)) && ((preg_match('/^3\d{9}$/', $tel)) || (preg_match('/^0\d{9}$/', $tel))) && (preg_match('/^([A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST]{1}[0-9LMNPQRSTUV]{2}[A-Z]{1}[0-9LMNPQRSTUV]{3}[A-Z]{1})$|([0-9]{11})$/', $codf)))
                {
                    $user_name = mysqli_real_escape_string($con,$user_name);
                    $email = mysqli_real_escape_string($con,$email);
                    $codf = mysqli_real_escape_string($con,$codf);
                    $tel = mysqli_real_escape_string($con,$tel);

                    if(!empty($_POST['password']) && (!empty($_POST['biografia'])))
                    {
                        $not_hash = trim($_POST['password']);
                        $pass = mysqli_real_escape_string($con,$not_hash);

                        $bio = trim($_POST['biografia']);
                        $bio = mysqli_real_escape_string($con,$bio);

                        if((strlen($bio) < 171) && (strlen($not_hash) > 7) && (strlen($not_hash) < 17)){

                            $pass= password_hash($not_hash, PASSWORD_DEFAULT);

                            $query = "UPDATE utente SET username = ?, email = ?, pass = ?, bio = ?, telefono = ?, codF = ? where id_utente = '$id' limit 1";
                            $result = mysqli_prepare($con, $query);

                            if($result){
                                mysqli_stmt_bind_param($result,'ssssss', $user_name, $email, $pass, $bio, $tel, $codf);
                                mysqli_stmt_execute($result);
                            }
                        } else {
                            $msg = $msg2;
                        }

                    } else if(!empty($_POST['password'])){

                        $not_hash = trim($_POST['password']);
                        $pass = mysqli_real_escape_string($con,$not_hash);

                        if((strlen($not_hash) > 7) && (strlen($not_hash) < 17)){
                            $pass = password_hash($not_hash, PASSWORD_DEFAULT);

                            $query = "UPDATE utente SET username = ?, email = ?, pass = ?, bio = NULL, telefono = ?, codF = ?  where id_utente = '$id' limit 1";

                            $result = mysqli_prepare($con, $query);

                            if($result){
                                mysqli_stmt_bind_param($result,'sssss', $user_name, $email, $pass, $tel, $codf);
                                mysqli_stmt_execute($result);
                            }
                        } else {
                            $msg = $msg2;
                        }
                    
                    } else if(!empty($_POST['biografia'])){

                        $bio = trim($_POST['biografia']);
                        $bio = mysqli_real_escape_string($con,$bio);

                        if((strlen($bio) < 171)){

                            $query = "UPDATE utente SET username = ?, email = ?, bio = ?, telefono = ?, codF = ? where id_utente = '$id' limit 1";
                            $result = mysqli_prepare($con, $query);

                            if($result){
                                mysqli_stmt_bind_param($result,'sssss', $user_name, $email, $bio, $tel, $codf);
                                mysqli_stmt_execute($result);
                            }
                        } else {
                            $msg = $msg2;
                        }

                    } else {
                        $query = "UPDATE utente SET username = ?, email = ?, bio = NULL, telefono = ?, codF = ? where id_utente = '$id' limit 1";
                        $result = mysqli_prepare($con, $query);

                        if($result){
                            mysqli_stmt_bind_param($result,'ssss', $user_name, $email, $tel, $codf);
                            mysqli_stmt_execute($result);
                        }
                    }

                    mysqli_stmt_close($result);
                    
                    //aggiornare i dati della sessione
                    $query = "SELECT * from utente where id_utente = '$id' limit 1";
                    $result = mysqli_query($con,$query);

                        if(mysqli_num_rows($result) > 0)
                        {
                            $_SESSION['info'] = mysqli_fetch_assoc($result);
                        }
                    //redirect alla pagina del profilo
                    header("Location: profilo.php");
                    die;
                } else {
                    $msg = $msg2;
                }
            } else {
                $msg = $msg2;
            }
    } else {
        $msg = $msg2;
    }
}


    //creare un nuovo blog
    if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['nome_b']) && !empty($_POST['argomento']) && !empty(($_POST['colori']))){
        $msgB = " ";
        $id = $_SESSION['info']['id_utente'];

        $nome_b = trim($_POST['nome_b']);
        $nome_b = mysqli_real_escape_string($con,$nome_b);

        $arg = trim($_POST['argomento']);
        $arg = mysqli_real_escape_string($con,$arg);


        if(preg_match('/^[A-zÀ-ÖØ-öø-ÿ]{5,30}$/', $nome_b) && preg_match('/^[A-zÀ-ÖØ-öø-ÿ]{3,27}$/', $arg))
        {
            $query = "SELECT * from argomento where argomento = ? and id_argomento = padre limit 1";
            $result = mysqli_prepare($con, $query);

            if($result){
                mysqli_stmt_bind_param($result,'s', $arg);
                mysqli_stmt_execute($result);
                $stm = mysqli_stmt_get_result($result); 
            }
            
            //se è presente l'argomento, uso id_argomento già esistente
            if(mysqli_num_rows($stm)>0){

                $row = mysqli_fetch_assoc($stm);
                $id_arg = $row['id_argomento'];

            } else {
                //altrimenti, aggiungo un nuovo argomento nella tabella
                $query1 = "INSERT INTO argomento (argomento) values(?)";
                $result1 = mysqli_prepare($con, $query1);

                if($result1){
                    mysqli_stmt_bind_param($result1,'s', $arg);
                    mysqli_stmt_execute($result1);
                    $stm = mysqli_stmt_get_result($result1);
                }

                //devo agigungerlo come argomento, quindi devo ricercare il suo id e metterlo come padre
                $query2 = "SELECT * FROM argomento WHERE argomento = ? and padre IS NULL limit 1";
                $result2 = mysqli_prepare($con, $query2);

                if($result2){
                    mysqli_stmt_bind_param($result2,'s', $arg);
                    mysqli_stmt_execute($result2);
                    $stm = mysqli_stmt_get_result($result2);
                }

                if(mysqli_num_rows($stm)>0){

                    $row = mysqli_fetch_assoc($stm);
                    $id_arg = $row['id_argomento'];

                    $query3 = "UPDATE argomento SET padre = $id_arg WHERE id_argomento = '$id_arg' limit 1 ";
                    $result3 = mysqli_query($con, $query3);
                }
            }


            //controllo se è presente un sottoargomento
            if(!empty($_POST['sottoargomento'])){

                $sottoarg = trim($_POST['sottoargomento']);
                $sottoarg = mysqli_real_escape_string($con,$sottoarg);

                if(preg_match('/^[A-zÀ-ÖØ-öø-ÿ]{3,27}$/', $sottoarg)){

                    $query = "SELECT * from argomento where argomento = ? and not id_argomento = padre and padre = $id_arg  limit 1";
                    $result = mysqli_prepare($con, $query);

                    if($result){
                        mysqli_stmt_bind_param($result,'s', $sottoarg);
                        mysqli_stmt_execute($result);
                        $stm = mysqli_stmt_get_result($result);
                    }

                    //se il sottoargomento è presente nel database come sottoargomento dell'argomento attuale, uso id_sottoargomento già esistente
                    if(mysqli_num_rows($stm)>0){

                        $row = mysqli_fetch_assoc($stm);
                        $id_sottoarg = $row['id_argomento'];

                    } else {
                        //altrimenti, aggiungo un nuovo sottoargomento nella tabella
                        $query1 = "INSERT INTO argomento(argomento,padre) values(?, '$id_arg')";
                        $result1 = mysqli_prepare($con, $query1);

                        if($result1){
                            mysqli_stmt_bind_param($result1,'s', $sottoarg);
                            mysqli_stmt_execute($result1);
                            $stm = mysqli_stmt_get_result($result1);
                        }
            
                        $query2 = "SELECT * from argomento where argomento = ? and not id_argomento = padre limit 1";
                        $result2 = mysqli_prepare($con, $query2);

                        if($result2){
                            mysqli_stmt_bind_param($result2,'s', $sottoarg);
                            mysqli_stmt_execute($result2);
                            $stm = mysqli_stmt_get_result($result2);
                        }

                        if(mysqli_num_rows($stm)>0){
                            $row = mysqli_fetch_assoc($stm);
                            $id_sottoarg = $row['id_argomento'];

                        }
                    }
                }
            }

            //controllo se il coautore è presente nel db 
            if(isset($_POST['coautore'])){

                $coautore = trim($_POST['coautore']);
                $coautore = mysqli_real_escape_string($con,$coautore);
                
                $query = "SELECT * from utente where username = ? limit 1";
                $result = mysqli_prepare($con, $query);

                if($result){
                    mysqli_stmt_bind_param($result,'s', $coautore);
                    mysqli_stmt_execute($result);
                    $stm = mysqli_stmt_get_result($result);
                }

                //se è presente, lo inserisco, sennò no
                if(mysqli_num_rows($stm) > 0){

                    $row = mysqli_fetch_assoc($stm);
                    $id_coautore = $row['id_utente'];
                } 
            }


            $tema = $_POST['colori'];

                    

        //a seconda dei dati che sono stati inseriti, eseguo la rispettiva query
            if (isset($id_coautore) && isset($id_sottoarg)){
                $query = "INSERT into blog(autore_blog, nome, argomento, sottoargomento, coautore, tema) values('$id', ?, '$id_arg', '$id_sottoarg', '$id_coautore', '$tema')";

            } elseif (!isset($id_coautore) && isset($id_sottoarg)){
                $query = "INSERT into blog(autore_blog, nome, argomento, sottoargomento, tema) values('$id', ?, '$id_arg', '$id_sottoarg', '$tema')";

            } elseif (isset($id_coautore) && !isset($id_sottoarg)){
                $query = "INSERT into blog(autore_blog, nome, argomento, coautore, tema) values('$id', ?, '$id_arg', '$id_coautore', '$tema')";

            } else {
                $query = "INSERT into blog(autore_blog, nome, argomento, tema) values('$id', ?, '$id_arg','$tema')";
            }

            $result = mysqli_prepare($con, $query);
            if($result){
                mysqli_stmt_bind_param($result,'s', $nome_b);
                mysqli_stmt_execute($result);
            }
            
            mysqli_stmt_close($result);

        } else {
            $msgB = $msg2;
        }

}

    
?>
    
