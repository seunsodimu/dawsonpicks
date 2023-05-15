<?php $wth = !empty($_GET['wth']) ? "" :"weather"?>
<div class="search-area-wrapper">
 <div class="<?= $wth ?>" id="weatherwidget">
	 
     <?php
	if(!empty($logged_user)){
	?>
	 <div style="color: #FFFFFF;">Welcome <?= $logged_user ?>, </div>
	 <div class="logout">
		 <?php if(session()->get('isa') >=1) { ?>
		 <span><a href="<?= base_url('all_post') ?>">Admin</a></span>&nbsp;&nbsp;|&nbsp;
		 <?php } ?>
		 <span><a href="<?= base_url('logout') ?>">Logout</a></span>
	 </div>
	 
	 <?php } ?>
	
	 <!--Dallas-->
      <a class="weatherwidget-io" href="<?= $weather['link'] ?>" data-label_1="<?= $weather['label'] ?>" data-label_2="WEATHER" data-mode="Current" data-theme="pure" data-basecolor="rgba(255, 255, 255, 0)" data-textcolor="#fff" ><?= $weather['label'] ?> WEATHER</a>
<script>
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
</script>
    </div>
        <?php
	if(!empty($logged_user)){
	?>
                        <div class="search-area container">
                                <h3 class="search-header">Search For Anything</h3>
                                <p class="search-tag-line">Search for trainings, articles, links, personnel and much more!</p>

                                <form id="search-form" class="search-form clearfix form-control" method="get" action="<?= base_url('search') ?>" autocomplete="off">
                                    <input class="search-term required form-control" style="height: 50px" type="text" id="s" name="s" placeholder="Type your search terms here" title="* Please enter a search term!" value="<?= !empty($search_string) ? $search_string : '' ?>" />
                                        <input class="search-btn" type="submit" value="Search" />
                                        <div id="search-error-container"></div>
                                </form>
                        </div>
	<?php } ?>
                </div>