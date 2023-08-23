<?=$this->extend("layout/pages")?>

<?=$this->section("content")?>
 <div class="span7 page-content">


                                                <article class=" type-post format-standard hentry clearfix">
                                                    <h2><?= $page_title ?></h2>
                                                    <br>
                                                    
    
    <hr><ul class="articles">
   <?php
   foreach ($articles as $article){ ?>
                                                                <li class="article-entry <?= $article->content_type ?>">
                                                                        <h4><a href="<?= base_url().'/training/'.$article->slug ?>"><?= $article->title ?></a><sup><span class="pull-right"><button id="btn-<?= $article->link_id ?>" class="qlink-btn btn btn-sm"><i class="fa fa-trash"></i></button></span></sup></h4>
                                                                        
                                                        <div class="article-meta">
															<?php
	   $content = strip_tags($article->content);
	   $preview = "...".mb_strimwidth($content, 0, 200)."...";
									   echo $preview;
	   ?>
																	</div> 
                                                                </li>
   <?php } ?>
    </ul>
                                                </article>

                                              

                                               <!-- end of comments -->

                                        </div>
                <!-- End of Page Container -->
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript">
               var baseUrl = '<?= base_url() ?>';
               $(document).ready(function(){
 var img = jQuery("img");

img.each(function() {
   var element = jQuery(this);
    var a = jQuery("<a />", {href: element.attr("src"), "data-lightbox": "test"});
    
    element.wrap(a);
});
				   
				   $('.qlink-btn').click(function(){
   if (confirm("Are you sure you want to remove this Quick Link?") == true) {
	var actionUrl = baseUrl+'/removelink';
	var btnid = this.id;
	var id = btnid.replace('btn-', '');
	var formData = {
      linkid: id };

    $.ajax({
      type: "POST",
      url: actionUrl,
      data: formData,
      dataType: "json",
      encode: true,
    }).done(function (data) {
      alert(data);
		if(data =="Quick Link removed!"){
		location.reload();
		}
    });
   }
});
}); </script>
<?=$this->endSection()?>
  