<div class = "header">
    <h2 class="title">MyBlog</h2>
    <div class="container">
      <div class="navbar-spacer">
        <div class="navbar" id="mainNavBar">
            <a href="home.php">Home</a>
            <?php if(empty($_SESSION['info'])):?>
                <a href="profilo.php">Profilo</a>
                <a href="login.php">Login</a>
                <a href="registrazione.php">Registrati</a>
            <?php else:?>
              <a href="#" onclick="cerca_a('<?php echo $_SESSION['info']['id_utente']?>')">Profilo</a>
              <a href="logout.php">Logout</a>

            <?php endif;?>
            </div>
      </div>
    </div>
    </div> 
  </div>
