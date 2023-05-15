<html>

     <head>
  
  <?=$this->include("partials/head_meta")?>
     </head>

     <body>

       <?=$this->include("partials/navi")?>
         
      

       <?=$this->renderSection("content")?>

      <?=$this->include("partials/footer")?>
     <?= $this->renderSection("scripts"); ?>
     </body>

</html>