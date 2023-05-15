<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>

<?=$this->section("custom_css")?>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
	#wrapper{
	overflow-y:	unset !important;
	}
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

                        

                           
								<div class="card">
                                    <div class="card-body">

<?php if(session()->getFlashdata('responseStatus')=="success") {?>
										<div class="row">
										<span class="alert alert-success">
										User data updated!
										</span>
										</div>
										<?php } elseif(session()->getFlashdata('responseStatus')=="error") { ?>
										<div class="row">
										<span class="alert alert-danger">
										There was an error!
										</span>
										</div>
										<?php } ?>

<table id="personnelTable" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Job Title</th>
                <th>Contact</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><span style="display: none"><?= $user['last_name'] ?></span><a href="<?= base_url().'/user/'.$user['employee_id'] ?>"><?= $user['first_name']." ".$user['last_name'] ?></a></td>
                <td><?= $user['job_title'] ?></td>
                <td><?= $user['work_email'] ?></td>
                <td><?= $user['status'] ?></td>
                <td>
						<button alt="OOO" title="OOO" id="ooo_btn-<?= $user['first_name']." ".$user['last_name'] ?>" class="btn btn-warning waves-effect waves-light ooowfh_btn" data-supervisorid="<?= $user['supervisor_id'] ?>" data-employeeid="<?= $user['employee_id'] ?>" data-bs-toggle="modal" data-bs-target="#ooowfhModal"><i class="fa fa-building contt" style="font-size 30px;"></i><i class="fa fa-ban nstt"></i> </button>
						<button alt="WFH" title="WFH" id="wfh_btn-<?= $user['first_name']." ".$user['last_name'] ?>" class="btn btn-warning waves-effect waves-light ooowfh_btn" data-supervisorid="<?= $user['supervisor_id'] ?>" data-employeeid="<?= $user['employee_id'] ?>" data-bs-toggle="modal" data-bs-target="#ooowfhModal"><i class="fa fa-home"></i></button>
																<button alt="Late Arrival/Early Departure" title="Late Arrival/Early Departure" id="late_btn-<?= $user['first_name']." ".$user['last_name'] ?>" class="btn btn-blue waves-effect waves-light ooowfh_btn" data-bs-toggle="modal" data-supervisorid="<?= $user['supervisor_id'] ?>" data-employeeid="<?= $user['employee_id'] ?>" data-bs-target="#lateModal"><i class="fa fa-clock"></i></button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        
    </table>
		

								
								</div>
							
								
						</div>
					</div>
		 </div>
		</div>

		 <!-- awards modal -->

                                        <div id="awards-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
                                            	<form method="POST" action="update_user">
                                                	<input type="hidden" name="updateVal" value="awarduser">
                                                	<input type="hidden" name="userid" value="" id="awarduserid">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><span id="awards-title"></span></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    	<div class="row">
                                                            <div class="mb-3">
                                                                    <label for="field-1" class="form-label">Select award type</label>
                                                                    <select id="AwardType" name="AwardType" class="form-control">
                                                                    	<option>TEAM MEMBER OF THE QUARTER</option>
                                                                    	<option>TEAM MEMBER OF THE MONTH</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                    <label for="field-1" class="form-label">Enter an award name</label>
                                                                    <input type="text" class="form-control" id="Award" name="Award" placeholder="e.g 1st Quarter 2022">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Give Award" />
                                                    </div>
                                                </div>
                                              </form>
                                            </div>
                                        </div><!-- /.modal -->

		  <!-- about modal -->

                                        <div id="about-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
                                                <form method="POST" action="update_user">
                                                	<input type="hidden" name="updateVal" value="aboutInfo">
                                                	<input type="hidden" name="userid" value="" id="aboutuserid">
                                                		<div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><span id="about-title"></span></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="">
                                                                    <label for="field-7" class="form-label">Personal Info</label>
                                                                    <textarea class="form-control" id="PersonalInfo" name="PersonalInfo" placeholder=""></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Save changes" />
                                                    </div>
                                                </div>
                                                </form>

                                            </div>
                                        </div>

<div id="ooowfhModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" id="oooForm" action="<?= base_url('update_user') ?>">
                                                    <input type="hidden" class="updateVal" name="updateVal" value="" />
                                                    <input type="hidden" name="userid" class="userid_field" value="" />
                                                    <input type="hidden" name="frompage" value="users" />
                                                    <input type="hidden" name="reportTo" class="oooReportTox" value="" />
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"> <span class="owl-title"></span></h4>
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
                                                                    <select name="ReportTo1" id="oooReportTo" class="form-control oooReportTo">
                                                                        <?php foreach ($supervisors as $supervisor) {
	
	echo "<option value='".$supervisor->employee_id."'>".$supervisor->first_name." ".$supervisor->last_name."</option>";
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
                                                <form method="POST" id="lateForm" action="<?= base_url('update_user') ?>">
                                                    <input type="hidden" class="updateVal" name="updateVal" value="" />
                                                    <input type="hidden" name="userid" class="userid_field" value="" />
                                                    <input type="hidden" name="frompage" value="users" />
                                                    <input type="hidden" name="reportTo" class="lateReportTox" value="" />
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><span class="owl-title"></span></h4>
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
                                                                    <select name="ReportTo1" id="lateReportTo" class="form-control lateReportTo">
                                                                        <?php foreach ($supervisors as $supervisor) {
	
	echo "<option value='".$supervisor->employee_id."'>".$supervisor->first_name." ".$supervisor->last_name."</option>";
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

<!-- /.modal -->


                                         
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>    
<script>
   var baseurl = '<?= base_url() ?>';
	  $(document).ready(function() {
    $('#personnelTable').DataTable( {
       
                "iDisplayLength": 100,
                "bDestroy": true,
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "bAutoWidth": false
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
		   $( ".owl-title" ).text('');
			  $('.updateVal').val('');
			  $('.userid_field').val('');
			  var btn = this.id;
			  var supervisor = this.getAttribute("data-supervisorid");
			  var userid = this.getAttribute("data-employeeid");
			  var pname = btn.replace('ooo_btn-', '');
			  pname = pname.replace('wfh_btn-', '');
			  pname = pname.replace('late_btn-', '');
			  var btid = btn.replace('-'+pname, ''); 
			  
			  $("#oooReportTo option[value="+supervisor+"]").prop('selected', true);
			  $("#lateReportTo option[value="+supervisor+"]").prop('selected', true);
			  
			  $('.userid_field').val(userid);
			if(btid=="ooo_btn"){ console.log(this.getAttribute("data-supervisorid")); //console.log($("#"+this.id).data("supervisorid"));
			   $( ".owl-title" ).text( pname+" - Out of Office" );
			   $('.updateVal').val('ooo');
			  
		   }  
		 else  if(btid=="wfh_btn"){
			   $( ".owl-title" ).text( pname+" - Work From Home" );
			   $('.updateVal').val('wfh');
		   }  
		 else  if(btid=="late_btn"){
			   $( ".owl-title" ).text( pname+" - Early Leave/Late Arrival" );
			   $('.updateVal').val('late');
		   }
		   });
		  
		  $('#oooRreportTo').on('change', function() {
  var reportTo = $(".oooReportTo option:selected").text(); 
	$('#oooReportTox').val(reportTo);
});
		  
		    $('#lateRreportTo').on('change', function() {
  var reportTo = $(".lateReportTo option:selected").text(); 
	$('#lateReportTox').val(reportTo);
});
		  
		  $("#oooForm").submit(function(e){
			  e.preventDefault();
			  var reportTo = $("#oooReportTo option:selected").text(); console.log(reportTo);
			  $('.oooReportTox').val(reportTo);
			  e.currentTarget.submit();
		  });
		  
		  $("#lateForm").submit(function(e){
			  e.preventDefault();
			  var reportTo = $("#lateReportTo option:selected").text(); console.log(reportTo);
			  $('.lateReportTox').val(reportTo);
			  e.currentTarget.submit();
		  });
} );
  </script>
<?=$this->endSection()?>