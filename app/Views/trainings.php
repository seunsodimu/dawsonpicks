<?=$this->extend("layout/articles")?>

<?=$this->section("content")?>

<div class="widget" id="float-toc" style="height: 50px;">
        <?php
        
       if(!empty(($article['toc']))){
        echo "<select class='form-control' onchange='if (this.value) window.location.href=this.value'><option value=''>Table of Contents</option>";
           foreach (json_decode($article['toc']) as $value){
       //$options1 = json_encode($value->options);
            $options = $value->options;
               if(!empty($options)){
       echo "<optgroup label='".$value->name."' value='#".$value->link."'>";
       foreach ($options as $val1){

           if(!empty($val1->link)){
           echo "<option value='#".$val1->link."'>".$val1->name."</option>";
           }
       }
         echo "</optgroup>";       
            }
            else{
             echo "<option value='#".$value->link."'>".$value->name."</option>";   
            }
       }
       echo "</select>";
       }
        ?>
	</div>
 <div class="span7 page-content">
    <div class="mobile-only" style="display: none;">
<div class="support-widget">
                                                                
                                                               
                                                                    <div class="span6"><input type="search" name="article_search" class="form-control" id="article_searchM" placeholder="Search within this article" results/></div>
                                                                    <div class="col-3"><button id="article_search_btnM" class="btn btn-secondary" style="height: 33px;" type="submit"><i class="fa fa-search"></i></button></div>
                                                                
                                                                <div id="searchcountM"></div>
                                                        </div>
    </div>

                                                <ul class="breadcrumb">
													<?php
													if($subcat['subcat_id']==23){
														$parent_article =  base_url().'/directory/';
													}else if($subcat['subcat_id']==6){
														$parent_article =  base_url().'/job-openings/';
													}else{
														$parent_article = base_url().'/trainings/'.$subcat['slug'];
													}
													?>
                                                        <li><a href="<?= $parent_article ?>"><?= $subcat['title'] ?></a><span class="divider">/</span></li>
                                                        <li class="active"><?= $page_title ?></li>
                                                </ul>

                                                <article class=" type-post format-standard hentry clearfix">
                                                    <div class="post-meta clearfix">
                                                                <span class="category"><a href="javascript:void(0)" id="addQuick">Add to Quick Links</a></span>
                                                                <span class="pull-right">
																	<?php if ($article['downloadable_file']!="") {
																	$dlink = base_url()."/render/".$article['downloadable_file']
																	?>
																	<a href="<?= $dlink ?>" id="" target="_blank"><i class="fa fa-print"></i> Print/Download</a>
																	<?php } else { ?>
																	<a href="javascript:void(0)" id="hrefPrint"><i class="fa fa-print"></i> Print/Download</a>
																	<?php } ?> 
																	<?php if(session()->get('isa') >=1) {
																	$edit_link = ($article['content_type']=='user')?'/user/'.$article['external_id']:'/post/'.$article['content_id'];
																	?>
																	<a href="<?= base_url().$edit_link ?>" id="hrefEdit"><i class="fa fa-edit"></i> Edit</a>
																	
																	<?php } ?>
														</span>
                                                        </div><!-- end of post meta -->
                                                    <h3><?= $page_title ?> </h3>
                                                    <br>
                                                    
    <div class="col-md-8">
        
        
    </div>
    <hr>
    <div id="citadel-content">
        <?php if ($subcat['subcat_id']==23) {
		$uimg = str_replace('writable/uploads//var/www/vhosts/my.lawboss.org/httpdocs/citadel/','',$person['profile_pic']);
									$uimg = base_url()."/render/".$uimg;
		?>
        <img src="<?= $uimg ?>" alt="<?= $article['title'] ?>" />
        <br>
        <?= $person['job_title']."<br>".$person['work_email']."<br>".$person['about_info'] ?>
        <?php } else { 
		if ($article['content_type']=="video"){
		?>
  			<button class="btn btn-success btn-large modalCall" id="callModal"><i class="fa fa-video"></i> Play Video</button>
		<div class="modal-content1" id="myModal">
    <span class="close">&times;</span>
    <div id="modalBody">
        <?= $article['content'] ?>
    </div>
  </div><!-- /.modal -->
		<div class="modal-content1" id="myModal1">
    <span class="close">&times;</span>
    <div id="modalBody1">
      <form id="ooo_wfh_form">
		  
		</form>
    </div>
  </div><!-- /.modal -->
				<?php } else { ?>
		<?= $article['content'] ?>
		
        <?php } } ?>
    </div>
                                                </article>

                                              

                                               <!-- end of comments -->

                                        </div>
                <!-- End of Page Container -->



<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript">
               var baseUrl = '<?= base_url() ?>';
               $(document).ready(function(){
 var img = jQuery(".hentry img");

img.each(function() {
   var element = jQuery(this);
    var a = jQuery("<a />", {href: element.attr("src"), "data-lightbox": "test"});
    
    element.wrap(a);
});


$("#hrefPrint").click(function() {
      
       Popup(jQuery('.hentry').html());
    });


function Popup(data) {
    var csslink = '<link rel="stylesheet" href="'+baseUrl+'assets/css/main.css" type="text/css" />';
    var mywindow = window.open('', 'my div');
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write(csslink);  
    mywindow.document.write('<style type="text/css">.post-meta { display:none; } </style></head><body>');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');
    mywindow.document.close();
    mywindow.print();                        
}

$("#addQuick").click(function() {
    
    var actionUrl = baseUrl+'/add_links';
   var formData = {
      articleSlug: "<?= $article['slug'] ?>" };

    $.ajax({
      type: "POST",
      url: actionUrl,
      data: formData,
      dataType: "json",
      encode: true,
    }).done(function (data) {
      alert(data);
    });
    });
}); 

	
	// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
//btn.onclick = function() {
//  modal.style.display = "block";
//}

 $(".modalCall").click(function() {
     var overlay = jQuery('<div id="overlay"> </div>');
	overlay.appendTo(document.body)
	 modal.style.display = "block";
    });

// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
    modal.style.display = "none";
	  var overlay = document.getElementById("overlay");
	  overlay.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
	  var overlay = document.getElementById("overlay");
	  overlay.style.display = "none";
  }
}

</script>
<?=$this->endSection()?>
  