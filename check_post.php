
<?php

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_GET['action']) && $_GET['action']=='edit'){
    $msg = "";
    $image_added = false;
    $titolo = $_POST['titolo_post'];
    $contenuto = $_POST['testo_post'];

    $autore = $_SESSION['info']['id_utente'];

    $blog = $_SESSION['blog']['id_blog'];
    $id_p = $_SESSION['post']['id_post'];
    $data = date('Y-m-d H:i:s');

    if((strlen($titolo) > 3) && (strlen($titolo) < 51) && (strlen($contenuto) > 100) && (strlen($contenuto) < 1001)){

        $titolo = trim($_POST['titolo_post']);
        $contenuto = trim($_POST['testo_post']);

        $titolo = mysqli_real_escape_string($con,$titolo);
        $contenuto = mysqli_real_escape_string($con,$contenuto);

        if (!empty($_FILES['image']['name'])){

            if($_FILES['image']['size'] < 20240){

                if($_FILES['image']['error'] == 0){

                    if($_FILES['image']['type'] == "image/jpg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/png"){
                        
                        $folder = "uploads/";
                    
                        //il file è stato uploadato
                        $image = $folder . $_FILES['image']['name'];
                        $image = mysqli_escape_string($con, $image);

                        $query = "SELECT immagine from post where immagine = ?";
                        $result = mysqli_prepare($con, $query);

                        if($result){
                            mysqli_stmt_bind_param($result,'s', $image);
                            mysqli_stmt_execute($result);
                            $stm = mysqli_stmt_get_result($result);

                            if(mysqli_num_rows($stm)>0) {
                                $msg = "Esiste già un file con questo nome!";

                            } else {
                                if(!move_uploaded_file($_FILES['image']['tmp_name'], $image)){
                                    $msg = "Errore nel caricamento dell'immagine!";
        
                                } else {
                                    
                                    //controllo se il post aveva già un'immagine e la elimino 
                                    $id_p = $_SESSION['post']['id_post'];
                                    $autore = $_SESSION['info']['id_utente'];
                                    $query = "SELECT * from post where id_post = '$id_p' && autore = '$autore' limit 1";
                                    
                                    $result = mysqli_query($con,$query);
                                        
                                    if(mysqli_num_rows($result) > 0){

                                        $row = mysqli_fetch_assoc($result);

                                        if(file_exists($row['immagine'])){
                                            unlink($row['immagine']);
                                        }
                                    }
                        
                                    $image_added = true;

                                    if ($image_added = true){

                                        $image = mysqli_escape_string($con, $image);
            
                                        $query1 = "UPDATE post SET contenuto = ?, immagine = ?, data_ora = '$data', titolo = ? WHERE  id_post = '$id_p'";
                                        $result1 = mysqli_prepare($con, $query1);
            
                                        if($result1){
                                            mysqli_stmt_bind_param($result1,'sss', $contenuto, $image, $titolo);
                                            mysqli_stmt_execute($result1);
                                            mysqli_stmt_close($result1);

                                            //aggiorno i dati della sessione
                                            $query = "SELECT * from post where id_post = '$id_p' limit 1";
                                            $result = mysqli_query($con,$query);
                                    
                                            if(mysqli_num_rows($result) > 0){
                                            
                                                $_SESSION['post'] = mysqli_fetch_assoc($result);
                                            }

                                            header('Location: post.php');
                                            die;
                                            
                                        }        
                                    }
                                }
                            }
                        }

                    } else {
                        $msg = "Formato dell'immagine non supportato!";
                    }


                } else {
                    $msg ="Errore nel caricamento dell'immagine!";
                }


            } else {
                $msg = $_FILES['image']['size'];
            }

        } else {

            $query2 = "UPDATE post SET contenuto = ?, data_ora = '$data', titolo = ? WHERE  id_post = '$id_p'";

            $result2 = mysqli_prepare($con, $query2);

            if($result2){
                mysqli_stmt_bind_param($result2,'ss', $contenuto, $titolo);
                mysqli_stmt_execute($result2);
                mysqli_stmt_close($result2);
            }

                //aggiorno i dati della sessione
                $query = "SELECT * from post where id_post = '$id_p' limit 1";
                $result = mysqli_query($con,$query);
        
                if(mysqli_num_rows($result) > 0){
                
                    $_SESSION['post'] = mysqli_fetch_assoc($result);
                }

                header('Location: post.php');
                die;
        }
    } else {
        $msg = "Qualcosa è andato storto...";
    }

} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_GET['action']) && $_GET['action']=='delete')

    {
        $id_p = $_SESSION['post']['id_post'];
        $autore = $_SESSION['info']['id_utente'];


        $query = "SELECT * from post where id_post = '$id_p' limit 1";
        $result = mysqli_query($con,$query);

        if(mysqli_num_rows($result) > 0){

            $row = mysqli_fetch_assoc($result);

                if(file_exists($row['immagine'])){
                    unlink($row['immagine']);
                }
            }

        $query = "DELETE FROM post WHERE id_post = '$id_p' && autore = '$autore' limit 1";
        $result = mysqli_query($con, $query); 

        header("Location: blog.php");
        die;

    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['commento']) && (strlen($_POST['commento']) < 140)){

        $testo = trim($_POST['commento']);
        $testo = mysqli_escape_string($con, $testo);

        $id_autore = $_SESSION['info']['id_utente'];
        $id_post = $_SESSION['post']['id_post'];
        $data = date('Y-m-d H:i:s');

        $query = "INSERT INTO commento(testo, autore_commento, post_commento, data_ora) VALUES (?, '$id_autore', '$id_post', '$data')";
        $result = mysqli_prepare($con, $query);

        if($result){
            mysqli_stmt_bind_param($result,'s', $testo);
            mysqli_stmt_execute($result);
            mysqli_stmt_close($result);
        }        

        header('Location: post.php');
        die;
    }


?>
