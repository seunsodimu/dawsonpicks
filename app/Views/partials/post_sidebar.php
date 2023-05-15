
<aside class="span4 page-sidebar">
    
                                                <section class="widget">
                                                        <div class="support-widget">
                                                                <h3 class="title">Search within this article</h3>
                                                                <div class="row-fluid">
                                                                    <div class="span9"><input type="search" name="article_search" class="form-control" id="article_search" results/></div>
                                                                    <div class="span3"><button id="article_search_btn" class="btn btn-secondary" style="height: 33px;" type="submit"><i class="fa fa-search"></i></button></div>
                                                                </div>
                                                                <div id="searchcount"></div>
                                                        </div>
                                                </section>
	
<section class="widget">
        <iframe src="https://app.nectarhr.com/culture-feed?token=1633959435" style="height: 450px; width: 600px; max-width: 100%; min-height: 400px; border: none; overflow: hidden; border-radius: 6px;" frameborder="0" allowTransparency="true"></iframe>
        </section>

                                                <section class="widget">
                                                        <h3 class="title">Quick Links</h3>
                                                        <ul class="articles">
                                                                <?php foreach($quicklinks as $link) { ?>
                                                                <li class="article-entry>">
                                                                        <p><a href="<?= base_url().'/training/'.$link['slug'] ?>"><?= $link['title'] ?></a></p>
                                                                        
                                                                </li>
                                                                <?php } ?>
                                                        </ul>
                                                        <span class="pull-right"><a href="<?= base_url('mylinks') ?> ">View more</a></span>
                                                </section>
<hr>
<section class="widget">
                                                        <h3 class="title">Related Articles</h3>
                                                        <ul class="articles">
                                                                <?php foreach($related as $relt) { ?>
                                                                <li class="article-entry <?= $relt['content_type'] ?>">
                                                                        <h4><a href="<?= base_url().'/training/'.$relt['slug'] ?>"><?= $relt['title'] ?></a></h4>
                                                                        <span class="article-meta">
                                                                            <?php
                                                                            $relt_content = strip_tags($relt['content']);
                                                                            echo mb_strimwidth($relt_content, 0, 120)."...";
                                                                            ?>
                                                                        </span>
                                                                </li>
                                                                <?php } ?>
                                                        </ul>
                                                        <span class="pull-right"><a href="<?= base_url().'/training/'.$subcat['slug'] ?> ">View more</a></span>
                                                </section>

                                                

                                        </aside>
