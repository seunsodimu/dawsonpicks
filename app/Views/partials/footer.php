
<div id="footer-bottom-wrapper1" style="text-align: center; padding:100px">
                                <div id="footer-bottom" class="container">
                                        <div class="row">
                                            <hr>
                                                <div class="span4">
													<h3>Dallas</h3>
													<p>13355 Noel Rd, Suite 1500 <br>Dallas, TX 75240</p>
                                                </div>
                                                <div class="span4">
													<h3>Houston</h3>
													<p>5300 Memorial Drive, Suite 810 <br>Houston, TX 77007</p>
                                                </div>
                                                <div class="span4">
													<h3>San Antonio</h3>
													<p>8200 IH-10 West, Suite 315<br> San Antonio, TX 78230</p>
                                                </div>
                                        </div>
                                </div>
                        </div>
<footer id="footer-wrapper">
	
                        <div id="footer" class="container">
                                <div class="row">

                                        <div class="span4">
                                                <section class="widget">
                                                        <h3 class="title">Latest Tweets</h3>
                                                        <div id="twitter_update_list">
                                                              <!-- SnapWidget -->
<script src="https://snapwidget.com/js/snapwidget.js"></script>
<iframe src="https://snapwidget.com/embed/990520" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden;  width:100%; "></iframe>
                                                        </div>
                                                </section>
                                        </div>


                                        <div class="span4">
                                                <section class="widget">
                                                        <h3 class="title">LAWBOSS Facebook</h3>
                                                      <!-- SnapWidget -->
<iframe src="https://snapwidget.com/embed/990518" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden;  width:100%; "></iframe>
                                                </section>
                                        </div>

                                        

                                        <div class="span4">
                                                <section class="widget">
                                                        <h3 class="title">Instagram Feed</h3>
                                                         
<iframe src="https://snapwidget.com/embed/990517" class="snapwidget-widget" allowtransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden;  width:100%; "></iframe>
                                                </section>
                                        </div>

                                </div>
                        </div>
                        <!-- end of #footer -->

                        <!-- Footer Bottom -->
                        <div id="footer-bottom-wrapper">
                                <div id="footer-bottom" class="container">
                                        <div class="row">
                                                <div class="span6">
                                                        <p class="copyright">
                                                                 © 2022. LAWBOSS.
                                                        </p>
                                                </div>
                                                <div class="span6">
                                                        <!-- Social Navigation -->
                                                        <ul class="social-nav clearfix">
                                                                <li class="linkedin"><a target="_blank" href="#"></a></li>
                                                                <li class="google"><a target="_blank" href="#"></a></li>
                                                                <li class="twitter"><a target="_blank" href="#"></a></li>
                                                                <li class="facebook"><a target="_blank" href="#"></a></li>
                                                        </ul>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <!-- End of Footer Bottom -->

                </footer>
                <!-- End of Footer -->
           <?php
	if(!empty($logged_user)){
	?>
                <div class="" id="itreq" style="display: none;">
                    <div class="pull-right"><button id="close-it"><i class="fa fa-window-close"></i></button></div>
                     <iframe src="<?= base_url('it-request') ?>" style="width: 550px; height: 750px;" ></iframe>
                </div>
<?php } ?>
                <a href="#top" id="scroll-top"></a>
               
               
           <?php
	if(!empty($logged_user)){
	?>             
<a href="javascript:void(0)" id="it-request"></a>
           <?php } ?>     
                <script type='text/javascript' src="https://code.jquery.com/jquery-3.5.1.js"></script>
                
                <script type='text/javascript' src="<?= base_url('assets/js/jquery.easing.1.3.js') ?>"></script>
                <script type='text/javascript' src="<?= base_url('assets/js/prettyphoto/jquery.prettyPhoto.js') ?>"></script>
                <script type='text/javascript' src="<?= base_url('assets/js/jflickrfeed.js') ?>"></script>
                <script type='text/javascript' src="<?= base_url('assets/js/jquery.liveSearch.js') ?>"></script>
                <script type='text/javascript' src="<?= base_url('assets/js/jquery.form.js') ?>"></script>
                <script type='text/javascript' src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>
                <script type='text/javascript' src="<?= base_url('assets/js/custom.js') ?>"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js" integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <script src="https://vjs.zencdn.net/7.18.1/video.min.js"></script>
                <script type="text/javascript">
    $("#it-request").click(function(){
   $("#itreq").show(); 
       $("#it-request").hide(); 
});     
   $("#close-it").click(function(){
  $("#itreq").hide();
    $("#it-request").show(); 
});    
    $("#article_search_btn").click(function(){ 
        $('mark').contents().unwrap();
        $('#searchcount').empty();
var term = $('#article_search').val();
        if(term!=""){
        var src_str = $("#citadel-content").html();
term = term.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
var pattern = new RegExp("("+term+")", "gi");

src_str = src_str.replace(pattern, "<mark>$1</mark>");
src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

$("#citadel-content").html(src_str);
var searchcount = "<span style='color:green;'>"+$('mark').length +" result(s)</span>";
$('#searchcount').html(searchcount);
}
    }); 
					
					   $("#article_search_btnM").click(function(){ 
        $('mark').contents().unwrap();
        $('#searchcountM').empty();
var term = $('#article_searchM').val();
        if(term!=""){
        var src_str = $("#citadel-content").html();
term = term.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
var pattern = new RegExp("("+term+")", "gi");

src_str = src_str.replace(pattern, "<mark>$1</mark>");
src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

$("#citadel-content").html(src_str);
var searchcount = "<span style='color:green;'>"+$('mark').length +" result(s)</span>";
$('#searchcountM').html(searchcount);
}
    }); 
    
                </script>