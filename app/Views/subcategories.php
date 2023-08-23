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
                                                    
    
    <hr><ul class="articles">
   <?php
   foreach ($articles as $article){ ?>
                                                                <li class="article-entry standard">
                                                                        <h4><a href="<?= base_url().'/training/'.$article['slug'] ?>"><?= $article['title'] ?></a></h4>
                                                                        
                                                                        <span class="like-count"><?= $article['like_count'] ?></span>
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
               
               $(document).ready(function(){
 var img = jQuery("img");

img.each(function() {
   var element = jQuery(this);
    var a = jQuery("<a />", {href: element.attr("src"), "data-lightbox": "test"});
    
    element.wrap(a);
});
}); </script>
<?=$this->endSection()?>
  