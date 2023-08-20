<aside class="span4 page-sidebar">

                                         
<section class="widget">
        ﻿﻿<iframe src="https://app.nectarhr.com/culture-feed?token=1633959435" style="height: 450px; width: 600px; max-width: 100%; min-height: 400px; border: none; overflow: hidden; border-radius: 6px;" frameborder="0" allowTransparency="true"></iframe>
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
<br><br>

                                                

                                        </aside>