<?=$this->extend("layout/pages")?>

<?=$this->section("content")?>
 <div class="span7 page-content">

<!--                                                <ul class="breadcrumb">
                                                        <li><a href="#">Knowledge Base Theme</a><span class="divider">/</span></li>
                                                        <li><a href="#" title="View all posts in Server &amp; Database">Server &amp; Database</a> <span class="divider">/</span></li>
                                                        <li class="active">Integrating WordPress with Your Website</li>
                                                </ul>-->

                                                <article class=" type-post format-standard hentry clearfix">
                                                    <h2><?= $page_title ?></h2>
                                                    <br>
                                                    
                                                    <p><i>Your search for <strong>"<?= $search_string ?>"</strong> returned <?= count($articles) ?> result(s)</strong></i>
    <hr>
    <div id="citadel-content">
    <ul class="articles" id="search_result_list">
   <?php
   foreach ($articles as $article){ 
   	$art_link = ($article->subcat_id==23) ? base_url().'/personnel/'.$article->slug: base_url().'/training/'.$article->slug;
	  $tagt = "";
	   if(in_array($article->subcat_id, [25, 26]))
		  {
			$art_link=$article->slug; // var_dump('show'); exit;
		   $tagt = "target='_blank'";
		  }
	   ?>
                                                   
                                                                <li class="article-entry <?= $article->content_type ?>">
                                                                        <h4><a href="<?= $art_link ?>" <?= $tagt ?>><?= $article->title ?></a></h4>
                                                                        <span class="article-meta">
                                                                            <?php
                                                                            $content = strip_tags($article->content);
                                                                            $position = strpos($content, $search_string);
                                                                            $position = $position-100;
                                                                            $pos = ($position >=1) ? $position: 0;
                                                                            $preview = "...".mb_strimwidth($content, $pos, 300)."...";
									   echo $preview;
									  
                                                                            ?>
                                                                        </span>
                                                                        <span class="like-count"><?= $article->like_count ?></span>

                                                                </li>
   <?php  } ?>
    </ul>
    </div>
                                                </article>

                                              

                                               <!-- end of comments -->

                                        </div>
                <!-- End of Page Container -->
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript">
               
               $(document).ready(function(){
 var img = jQuery("img");

img.each(function() {
   var element = jQuery(this);
    var a = jQuery("<a />", {href: element.attr("src"), "data-lightbox": "test"});
    
    element.wrap(a);
});

$('.article-meta').each(function(i, obj) {
  //console.log($(this).html());
var searchterm = '<?= $search_string ?>'; //console.log(searchterm);
      	var src_str1 = $(this).html();
		searchterm = searchterm.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
		var pattern1 = new RegExp("("+searchterm+")", "gi");

		src_str1 = src_str1.replace(pattern1, "<mark>$1</mark>");
		src_str1 = src_str1.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

		$(this).html(src_str1);

 });


}); </script>
<?=$this->endSection()?>
  