<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>

<?=$this->section("custom_css")?>
<link href="<?= base_url() ?>/assets/admin/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>/assets/admin/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>/assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>/assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>/assets/admin/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>/assets/admin/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
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
                                    <div class="card-body">
<div style="margin-bottom: 10px;">
	<button class="btn btn-success" id="new-menu-item" data-bs-toggle="modal" data-bs-target="#menuModal"><i class="fa fa-plus"> New Menu Item</i></button>
	
</div>
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


		<table id="postsTable" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Date Created</th>
                <th>Menu Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Created By</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($content as $post) { ?>
            <tr>
                <td><?= $post->created_at ?></td>
                <td><a href="<?= $post->slug ?>" target="_blank" ><?= $post->title ?></a></td>
                <td><?= ucfirst($post->content_type) ?></td>
                <td><?= $post->status ?></td>
                <td><?= $post->created_by ?></td>
                <td><button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>
            </tr>
            <?php } ?>
        </tbody>
        
    </table>

									</div>
								</div>
							</div>
								
						</div>
					</div>
		 </div>
		</div>

<div id="menuModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
                                                <form method="POST" action="<?= base_url('new-menu') ?>" enctype='multipart/form-data'>
                                                    <input type="hidden" name="updateVal" value="displayPic">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">New Menu Item</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                                                                            
                                                                                                                            <input type="file" id="DisplayImage" name="DisplayImage" data-plugins="dropify" data-default-file="" />
																<div class="form-group" style="margin-top: 10px">
																	<select class="form-control" name="MenuType" required>
																		<option value="">Select Menu Type</option>
																		<option>Site</option>
																		<option>Tool</option>
																	</select>
																</div>
																<div class="form-group" style="margin-top: 10px">
																	<label>Menu Title</label>
                                                                                                                                        <input type="text" name="Title" class="form-control" />
																</div>
																<div class="form-group" style="margin-top: 10px">
																	<label>Menu Link</label>
                                                                                                                                        <input type="text" name="Link" class="form-control" />
																</div>
                                                                                                                        </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-info waves-effect waves-light" value="Add New" />
                                                    </div>
                                                </div>
                                            </div>
                                          </form>
                                        </div><!-- /.modal -->
		

                                         
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
    $('#postsTable').DataTable( {
        search: {
            return: true
        }
    } );


} );
  </script>
<?=$this->endSection()?>