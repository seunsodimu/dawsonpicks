<?=$this->extend("layout/admin")?>

<?=$this->section("custom_css")?>
<link href="<?= base_url() ?>/assets/admin/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>/assets/admin/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>/assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>/assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>/assets/admin/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>/assets/admin/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<style>
.contt {
  position: relative

}

.nstt {
  position: absolute;
  top:8px;
  left: 14px;
  font-size: 300%;
  color: rgba(217, 83, 79, 0.7);
}
	.fa{
	font-size: 16px;	
	}
</style>
<?=$this->endSection()?>
<?=$this->section("content")?>
<?php $session = \Config\Services::session();  ?>

	 <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid"> 

                        <div class="row">

                            <div class="col-12">
								<div class="card">
                                    <?php if(session()->getFlashdata('responseStatus')=="success") {?>
                                        <div class="row">
                                        <span class="alert alert-success">
                                       <?= !empty(session()->getFlashdata('responseStatusMsg'))?session()->getFlashdata('responseStatusMsg'):" User data updated!" ?>
                                        </span>
                                        </div>
                                        <?php } elseif(session()->getFlashdata('responseStatus')=="error") { ?>
                                        <div class="row">
                                        <span class="alert alert-danger">
                                        There was an error!
                                        </span>
                                        </div>
                                        <?php } ?>
									<?php 
									$uimg = str_replace('writable/uploads//var/www/vhosts/my.lawboss.org/httpdocs/citadel/','',$person['profile_pic']);
									$uimg = base_url()."/render/".$uimg; ?>
                                <div class="bg-picture card-body">
                                    <div class="d-flex align-items-top">
                                  <a href="javascript:void(0)" class="float-start me-3" data-bs-toggle="modal" data-bs-target="#photo-modal-<?= $person['employee_id'] ?>">
                                        <img src="<?= $uimg ?>"
                                                class="flex-shrink-0 rounded-circle avatar-xl img-thumbnail float-start me-3" alt="profile-image">
</a>

                                        <div class="flex-grow-1 overflow-hidden">
                                           
                                            <p class="text-muted"><i><?= $person['job_title'] ?></i></p>
                                            <p class="text-muted"><?= $person['work_email'] ?></p>
                                            
                                                
                                            
        
                                        </div>
<div class="col-md-5 pull-right">                                                        
                                                            <div class="mb-6">
                                                                 <button class="btn btn-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#awardModal"><i class="fa fa-trophy"></i> Give Award </button>
																<button id="ooo_btn" class="btn btn-warning waves-effect waves-light ooowfh_btn" data-bs-toggle="modal" data-bs-target="#ooowfhModal"><i class="fa fa-building contt" style="font-size 30px;"></i><i class="fa fa-ban nstt"></i> OOO </button>
																<button id="wfh_btn" class="btn btn-warning waves-effect waves-light ooowfh_btn" data-bs-toggle="modal" data-bs-target="#ooowfhModal"><i class="fa fa-home"></i> WFH </button>
																<button id="late_btn" class="btn btn-blue waves-effect waves-light ooowfh_btn" data-bs-toggle="modal" data-bs-target="#lateModal"><i class="fa fa-clock"></i> Late Arrival/Early Departure </button>
                                                            </div>

                                                  
                                                </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <form method="post" action="<?= base_url('update_user') ?>" class="card-body">
                                                    <input type="hidden" name="updateVal" value="aboutInfo">
									<label>Team Member Bio:</label>
                                                    <input type="hidden" name="userid" value="<?= $person['employee_id'] ?>" id="aboutuserid">
                                    <span class="input-icon icon-end">
                                        <textarea rows="3" class="form-control" id="PersonalInfo" name="PersonalInfo" placeholder=""><?= $person['about_info'] ?></textarea>
                                    </span>
                                    <div class="pt-1 float-end">
                                       <input type="submit" class="btn btn-info waves-effect waves-light" value="Save" />
                                    </div>
                                    

                                </form>
                                </div>

                                <div class="card">
                                <div class="card-body">
                                    <h3>Awards & Recognitions</h3>
<hr><?php foreach ($awards as $award): ?>
    

                                    <div class="d-flex align-items-top mb-2">
                                        
                                        <div class="flex-grow-1">
                                            <h5 class="mt-0"><?= $award->award_type ?><small class="ms-1 text-muted"><?= $award->award_name ?></small></h5>
                                            
                                        </div>
                                    </div>
                                    <?php endforeach ?>
                                    </div>
                                </div>
                                </div>
								</div>
							</div>
								
						</div>
					</div>
		 </div>

       
    <!-- photo modal -->

                                        <div id="photo-modal-<?= $person['employee_id'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
                                                <form method="POST" action="<?= base_url('update_user') ?>" enctype='multipart/form-data'>
                                                    <input type="hidden" name="updateVal" value="displayPic">
                                                    <input type="hidden" name="userid" value="<?= $person['employee_id'] ?>" id="userid">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><span class="photo-title"><?= $person['first_name']." ".$person['last_name'] ?></span></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                                                                            
                                                                                                                            <input type="file" id="DisplayImage" name="DisplayImage" data-plugins="dropify" data-default-file="<?= $uimg ?>" />
                                                                                                                        </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Save changes" />
                                                    </div>
                                                </div>
                                            </div>
                                          </form>
                                        </div>
</div>
											
											<div id="awardModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="<?= base_url('update_user') ?>">
                                                    <input type="hidden" class="updateVal" name="updateVal" value="awarduser" />
                                                    <input type="hidden" name="userid" value="<?= $person['employee_id'] ?>" id="awarduserid" />
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><span class="photo-title"><?= $person['first_name']." ".$person['last_name'] ?></span></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                    <label for="AwardType" class="form-label">Select award type</label>
                                                                    <select id="AwardType" name="AwardType" class="form-control">
                                                                        <option>TEAM MEMBER OF THE QUARTER</option>
                                                                        <option>TEAM MEMBER OF THE MONTH</option>
                                                                    </select>
                                                                </div>
                                                           
                                                            <div class="mb-3">
                                                                    <label for="Award" class="form-label">Enter an award name</label>
                                                                    <input type="text" class="form-control" id="Award" name="Award" placeholder="e.g 1st Quarter 2022">
                                                                
                                                            </div>
                                                           
                                                            <div class="mb-3">
                                                                    <label for="AwardText" class="form-label">Nomination Text</label>
                                                                    <textarea name="AwardText" id="AwardText" class="form-control"></textarea>
                                                                
                                                            </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Save changes" />
                                                    </div>
                                                </div>
                                            </div>
                                          </form>
                                        </div>
</div>



<div id="ooowfhModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="<?= base_url('update_user') ?>">
                                                    <input type="hidden" class="updateVal" name="updateVal" value="" />
                                                    <input type="hidden" name="userid" value="<?= $person['employee_id'] ?>" />
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><?= $person['first_name']." ".$person['last_name'] ?> <span id="owl-title"></span></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
															<div id="startTime" class="mb-3">
																<label>Start Date and time </label>
																<input type="datetime-local" name="StartDateTime" id="StartDateTime" class="form-control" value="<?= date('Y-m-d').'T08:00' ?>"/>
															</div>
															
															<div id="endTime" class="mb-3">
																<label>End Date and time</label>
																<input type="datetime-local" name="EndDateTime" id="StartDateTime" class="form-control" value="<?= date('Y-m-d', strtotime('tomorrow')).'T17:00' ?>"/>
															</div>
															
                                                            <div id="reportTodiv" class="mb-3">
                                                                    <label for="ReportTo" class="form-label">Report To </label>
                                                                    <select id="ReportTo" name="ReportTo" class="form-control">
                                                                        <?php foreach ($supervisors as $supervisor) {
	$slt = ($person['supervisor_id']==$supervisor->employee_id)?" selected":"";
	echo "<option".$slt.">".$supervisor->first_name." ".$supervisor->last_name."</option>";
}?>
                                                                    </select>
                                                                </div>
                                                           
                                                            
                                                           
                                                            <div id="oooNote" class="mb-3">
                                                                    <label for="Note" class="form-label">Note (Optional)</label>
                                                                    <textarea name="Note" id="Note" class="form-control"></textarea>
                                                                
                                                            </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Submit" />
                                                    </div>
                                                </div>
                                            </div>
                                          </form>
                                        </div>
</div>

<div id="lateModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="<?= base_url('update_user') ?>">
                                                    <input type="hidden" class="updateVal" name="updateVal" value="" />
                                                    <input type="hidden" name="userid" value="<?= $person['employee_id'] ?>" />
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><?= $person['first_name']." ".$person['last_name'] ?> <span class="owl-title"></span></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
															<div id="startTime" class="mb-3">
																<label>Type </label>
																<select class="form-control" name="late_early">
																	<option>Late Arrival</option>
																	<option>Leave Early</option>
																</select>
															</div>
															<div id="startTime" class="mb-3">
																<label>Date </label>
																<input type="date" name="StartDateTime" id="StartDateTime" class="form-control" value="<?= date('Y-m-d') ?>"/>
															</div>
															
															<div id="endTime" class="mb-3">
																<label>Arrival/Departure Time</label>
																<input type="time" name="EndDateTime" id="StartDateTime" class="form-control" value=""/>
															</div>
															
                                                            <div id="reportTodiv" class="mb-3">
                                                                    <label for="ReportTo" class="form-label">Report To </label>
                                                                    <select id="ReportTo" name="ReportTo" class="form-control">
                                                                        <?php foreach ($supervisors as $supervisor) {
	$slt = ($person['supervisor_id']==$supervisor->employee_id)?" selected":"";
	echo "<option".$slt.">".$supervisor->first_name." ".$supervisor->last_name."</option>";
}?>
                                                                    </select>
                                                                </div>
                                                           
                                                            
                                                           
                                                            <div id="oooNote" class="mb-3">
                                                                    <label for="Note" class="form-label">Note (Optional)</label>
                                                                    <textarea name="Note" id="Note" class="form-control"></textarea>
                                                                
                                                            </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Submit" />
                                                    </div>
                                                </div>
                                            </div>
                                          </form>
                                        </div>
</div>
											<!-- /.modal -->

                                         
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script src="<?= base_url() ?>/assets/admin/libs/dropzone/min/dropzone.min.js"></script>
<script src="<?= base_url() ?>/assets/admin/libs/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>/assets/admin/js/pages/form-fileuploads.init.js"></script>
<input type="file" multiple="multiple" class="dz-hidden-input" tabindex="-1" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="<?= base_url() ?>/assets/admin/libs/pdfmake/build/vfs_fonts.js"></script>
         <script src="<?= base_url() ?>/assets/admin/js/pages/datatables.init.js"></script>
<script>
   var baseurl = '<?= base_url() ?>';
	  $(document).ready(function() {
    $('#personnelTable').DataTable( {
        search: {
            return: true
        }
    } );



$( ".awarduser" ).click(function(e) {
var btnid = this.id;
var userid = btnid.replace('-awd', '');
var username = $('#'+btnid).data('username'); 
$('#awards-title').text(username);
$("#awarduserid").val(userid);
});



$( ".changebio" ).click(function(e) {
var btnid = this.id;
var userid = btnid.replace('-abt', '');
var aboutinfo = $('#'+btnid).data('aboutinfo'); 
var username = $('#'+btnid).data('username'); 
$('#about-title').text(username);
$("#PersonalInfo").val(aboutinfo);
$("#aboutuserid").val(userid);
});
		$( ".ooowfh_btn" ).click(function(e) {  
		   if(this.id=="ooo_btn"){
			   $( ".owl-title" ).text( " - Out of Office" );
			   $('.updateVal').val('ooo');
		   }  
		   if(this.id=="wfh_btn"){
			   $( ".owl-title" ).text( " - Work From Home" );
			   $('.updateVal').val('wfh');
		   }  
		   if(this.id=="late_btn"){
			   $( ".owl-title" ).text( " - Early Leave/Late Arrival" );
			   $('.updateVal').val('late');
		   }
		   });
} );
  </script>
<?=$this->endSection()?>