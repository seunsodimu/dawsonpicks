<?=$this->extend("layout/pages")?>

<?=$this->section("content")?>
 <div class="span8 page-content">



                                                <article class=" type-post format-standard hentry clearfix">
                                                    <h1><?= $page_title ?> </h1>
                                                    <br>
                                                    
    
    <hr>
						<?= $head['content'] ?>							
												<br><br>	
													<?php if(!empty($ooo)){ ?>
    <div class="row">
       <table border=1 width="100%">
		   <tr>
			   <td colspan='4' align="center">
				   <strong>OOO / PTO</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Returning on </strong></td>
			   <td><strong>Urgent Matters to</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>
		   <?php foreach($ooo as $ooo_) { ?>
		   <tr>
			   <td><?= $ooo_->first_name." ".$ooo_->last_name ?></td>
			   <td><?= date('m/d/Y', strtotime($ooo_->end)) ?></td>
			   <td><?= $ooo_->report_to ?></td>
			   <td><?= $ooo_->note ?></td>
		   </tr>
		   <?php } ?>
		</table>
													</div>	
													<?php } if(!empty($wfh)){ ?>
    <br><br><br>
													<div class="row">
       <table border=1 width="100%">
		   <tr>
			   <td colspan='4' align="center">
				   <strong>WFH</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>
		   <?php foreach($wfh as $wfh_) { ?>
		   <tr>
			   <td><?= $wfh_->first_name." ".$wfh_->last_name ?></td>
			   <td><?= $wfh_->note ?></td>
		   </tr>
		   <?php } ?>
		</table>
													</div>
													<?php } if(!empty($late)){ ?>
													<br><br><br>
													<div class="row">
       <table border=1 width="100%">
		   <tr>
			   <td colspan='4' align="center">
				   <strong>Late Arrival</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Arriving At</strong></td>
			   <td><strong>Urgent Matters To</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>
		   <?php foreach($late as $late_) { ?>
		   <tr>
			   <td><?= $late_->first_name." ".$late_->last_name ?></td>
			   <td><?= date('h:i a', strtotime($late_->arrival)) ?></td>
			   <td><?= $late_->report_to ?></td>
			   <td><?= $late_->note ?></td>
		   </tr>
		   <?php } ?>
		</table>
													</div>
													<?php } if(!empty($early)){ ?>
													<br><br><br>
													<div class="row">
       <table border=1 width="100%">
		   <tr>
			   <td colspan='4' align="center">
				   <strong>Early Departure</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Leaving At</strong></td>
			   <td><strong>Urgent Matters To</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>
		   <?php foreach($early as $early_) { ?>
		   <tr>
			   <td><?= $early_->first_name." ".$early_->last_name ?></td>
			   <td><?= date('h:i a', strtotime($late_->arrival)) ?></td>
			   <td><?= $early_->report_to ?></td>
			   <td><?= $early_->note ?></td>
		   </tr>
		   <?php } ?>
		</table>
													</div>
													<br><br><br>
													<?php } ?>
													<?= $foot['content'] ?>	
                                                </article>

                                              

                                               <!-- end of comments -->

                                        </div>
                <!-- End of Page Container -->
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>            
<script type="text/javascript">
               
$(document).ready(function() {

} );
</script>
<?=$this->endSection()?>
  