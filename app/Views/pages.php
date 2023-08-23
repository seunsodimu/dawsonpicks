<?=$this->extend("layout/pages")?>
<style>
.my-accordion .menu{background-color:#d5d5d5;color:#444;cursor:pointer;padding:12px;width:100%;text-align:left;border:none;outline:none;margin-top:4px;border-radius:8px;font-size:inherit}.my-accordion .panel{background-color:#FFFFFF;color:#000000;overflow:hidden}.my-accordion .open{display:block}.my-accordion .close{display:none}.my-accordion .active{background-color:#1b90bb;color:#fff}.my-accordion .arrow{float:right;display:block}.my-accordion .darrow{display:none}.my-accordion .active .darrow{display:block}.my-accordion .active .rarrow{display:none}.my-accordion .panel a{display:block;background:#808080;color:#FFFFFF;padding:5px;margin:3px;width:100%;text-decoration:none}
</style>
<?=$this->section("content")?>
 <div class="span8 page-content">



                                                <article class=" type-post format-standard hentry clearfix">
                                                    <h1><?= $page_title ?> </h1>
                                                    <br>
                                                    
    
    <hr>
    
        <?= $article['content'] ?>
    
    
                                                </article>

                                              

                                               <!-- end of comments -->

                                        </div>
                <!-- End of Page Container -->
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>            
<script type="text/javascript">
               
$(document).ready(function() {
    $('#personnelTable').DataTable( {
        search: {
            return: true
        }
    } );
} );

	!function(){for(var l=document.querySelectorAll(".my-accordion .menu"),e=0;e<l.length;e++)l[e].addEventListener("click",n);function n(){for(var e=document.querySelectorAll(".my-accordion .panel"),n=0;n<e.length;n++)e[n].className="panel close";if(-1==this.className.indexOf("active")){for(n=0;n<l.length;n++)l[n].className="menu";this.className="menu active",this.nextElementSibling.className="panel open"}else for(n=0;n<l.length;n++)l[n].className="menu"}}();
	
//var acc = document.getElementsByClassName("accordion");
//var i;

//for (i = 0; i < acc.length; i++) {
//  acc[i].addEventListener("click", function() {
//    this.classList.toggle("active_accordion");
//    var panel = this.nextElementSibling;
//    if (panel.style.display === "block") {
//      panel.style.display = "none";
//    } else {
//      panel.style.display = "block";
//    }
//  });
//}
</script>
<?=$this->endSection()?>
  