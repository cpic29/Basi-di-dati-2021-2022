<?php

include 'funzioni.php';


if (isset($_GET["pagina"]) && isset($_GET["totale"])){ 
    $page = $_GET["pagina"]; 
    $totale = $_GET["totale"];
} else { 
    $page=1;
    $totale = $_GET['totale'];
}

?>

<div class="container" id="pagination_control">
  <div class="row">
      <?php 
      $show_dots = false;
      $result = $_GET["result"];
      $input = $_GET["i"];
      if ($page > 1):
        ?>
        <p class='one column' id="<?php echo $page-1;?>" onclick="selectPage('<?php echo $result;?>','<?php echo $page-1;?>','<?php echo $input; ?>');  paginazione('<?php echo $result;?>','<?php echo $totale;?>','<?php echo $input; ?>','<?php echo $page-1;?>');">&laquo;</p>
        <?php 
        endif;
        $show = 0;
        for ($i = $page; $i <= $totale; $i++) {
            if($i >= 1){
            $show++;
            if ($page == $i):
              $show_dots = true;
              ?>
              <p class='one column active' id="<?php echo $i;?>" onclick="selectPage('<?php echo $result;?>','<?php echo $i;?>','<?php echo $input; ?>'); paginazione('<?php echo $result;?>','<?php echo $totale;?>','<?php echo $input; ?>','<?php echo $page;?>');"><?php echo $page;?></p>
              </p>
              <?php else:
                if (($i <= 1) || ($i >= $page - 1 && $i <= $page + 1) || ($totale - 1 <= $i)):
                  $show_dots = true;
                  ?>
                <p class='one column' id="<?php echo $i;?>" onclick="selectPage('<?php echo $result;?>','<?php echo $i;?>','<?php echo $input; ?>'); paginazione('<?php echo $result;?>','<?php echo $totale;?>','<?php echo $input; ?>','<?php echo $i;?>');"><?php echo $i;?></p>
                <?php elseif($show_dots):
                  $show_dots = false;?>
                    <p class='one column'id="<?php echo $i;?>" onclick="selectPage('<?php echo $result;?>','<?php echo $i;?>','<?php echo $input; ?>');  paginazione('<?php echo $result;?>','<?php echo $totale;?>','<?php echo $input; ?>','<?php echo $i;?>');">...</p>
                    <?php 
                    endif;
                  endif;
            }
        }
        if ($totale != $page):?>
        <p class='one column' id="<?php echo $page+1;?>" onclick="selectPage('<?php echo $result;?>','<?php echo $page+1;?>','<?php echo $input; ?>');  paginazione('<?php echo $result;?>','<?php echo $totale;?>','<?php echo $input; ?>','<?php echo $page+1;?>');">&raquo;</p>
        <?php endif; ?>
        </div>
        </div>

