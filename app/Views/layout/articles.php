<html>

     <head>
  
  <?=$this->include("partials/head_meta")?>
     </head>

     <body>

       <?=$this->include("partials/navi")?>
         
       <?=$this->include("partials/searchterms")?>  
   <div class="page-container" id="pageContainer">
                        <div class="container">
                                <div class="row">
                    <?=$this->renderSection("content")?>
                    <?=$this->include("partials/post_sidebar")?> 
                                </div>
                        </div>
                </div>
       

      <?=$this->include("partials/footer")?>
     <?= $this->renderSection("scripts"); ?>
     </body>

</html>