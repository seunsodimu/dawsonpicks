<?=$this->extend("layout/page_no_search")?>


<?=$this->section("content")?>
<style>
	.iframeStyle{
  width:100%;
  height:100%; 
  border: 0;
}

.divIFrame{
  overflow:auto;
  -webkit-overflow-scrolling:touch;
  border:1px solid black;
}
</style>
                                
                                
                          <iframe id="draggable" src="<?= base_url('chartdisplay') ?>" width="100%" height="1900px" frameBorder="1" scrolling="yes" ></iframe>
 <!--  <div class="divIFrame">
<iframe src='https://app.organimi.com/embed/organizations/629f885d52fb2b0c1ab80d8b/charts/62aa494125d2c20c20adee24/chart/view?pId=719c52d5871309c295291ff69181b36f8ccc419b1f4e90cf57f335aae3fbedc4' border='0' scrolling='no' class='iframeStyle'></iframe>
</div>-->
                                        <!-- start of page content -->
                                        
          
                                       
                <!-- End of Page Container -->
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script>  
  $(function() {  
    $( "#draggable" ).draggable();  
  });  
  </script>  
<?=$this->endSection()?>
  