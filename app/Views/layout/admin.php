<html>

     <head>
  
  <?=$this->include("partials/admin_head")?>
	<?= $this->renderSection("custom_css"); ?>
     </head>

     <body>
 
		 <div id="wrapper">
       <?=$this->include("partials/admin_navi")?>

       <?=$this->renderSection("content")?>
		 </div>
		 
      <?=$this->include("partials/admin_footer")?>
		 
     <?= $this->renderSection("scripts"); ?>
		 
     </body>

</html>