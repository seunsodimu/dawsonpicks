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
<form method="POST" action="<?= base_url('update_post') ?>" id="newPostForm" enctype='multipart/form-data'>
	<input type="hidden" name="postId" value="<?= $post->content_id ?>">
	 <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid"> 

                        <div class="row">

                            <div class="col-9">
								<div class="card">
                                    <div class="card-body">
										<?php if(session()->getFlashdata('responseStatus')=="success") {?>
										<div class="row">
										<span class="alert alert-success">
										Post updated! 
										</span>
										</div>
										<?php } elseif(session()->getFlashdata('responseStatus')=="error") { ?>
										<div class="row">
										<span class="alert alert-danger">
										There was an error!
										</span>
										</div>
										<?php } ?>
                                        <div class="mb-3">
										<label for="subcat">Post Category</label>
											<select class="form-control" data-toggle="select2" data-width="100%" name="Subcat" id="Subcat">
												<?php foreach($subcats as $subcat){
													$selected = ($subcat['subcat_id'] == $post->subcat_id)?" selected":"";
	echo "<option value='".$subcat['subcat_id']."' ".$selected.">".$subcat['title']."</option>";
} ?>
											</select>
										</div>
                                        <div class="mb-3">
										<label for="PostTitle">Post Title</label>
											<input type="text" class="form-control" name="PostTitle" id="PostTitle" value="<?= $post->title ?>" />
										</div>
  <textarea cols="10" id="editor2" name="editor2" rows="10" data-sample-short><?= $post->content ?></textarea>
											

									</div>
								</div>
							
							</div>
								<div class="col-3">
								<div class="card">
									<div class="card-body">
								<div><a href="<?= base_url().'/training/'.$post->slug ?>" target="_blank">View Post</a></div>

                                        <div class="mb-3">
											<div class="input-group">
												<?php $select1 = ($post->status !="active") ? " selected":""; ?>
											<select class="form-control" data-width="100%" name="PostStatus">
												<option>Publish</option>
												<option <?= $selected ?>>Draft</option>
											</select>
                                                    <input type="submit" class="btn input-group-text btn-success waves-effect waves-light" value="Update" />
                                                </div>
										</div>
										<hr>
										
										<div class="mb-3">
											
												<label for="ContentType">Contet Type</label>
											<select class="form-control" data-width="100%" name="ContentType">
												<option <?= ($post->content_type == "standard") ? " selected": "" ?>>Standard</option>
												<option <?= ($post->content_type == "video") ? " selected": "" ?>>Video</option>
												<option <?= ($post->content_type == "image") ? " selected": "" ?>>Image</option>
											</select>
                                                
										</div>
										<hr>
										<div class="mb-3">
											<label for="DisplayImage">Display Image</label>
											<input type="hidden" name="olddp" value="<?= $post->display_img ?>" />
											<input type="file" data-plugins="dropify" name="DisplayImage" id="DisplayImage" data-default-file="<?= base_url().'/render/'.$post->display_img ?>" />
										</div>
										<hr>
										<div class="mb-3">
											<label for="Attachment">Add an attachment (pdf, word, excel)</label>
											<input type="file" data-plugins="dropify" name="Attachment" id="Attachment" />
										</div>
										<hr>
										<div class="mb-3">
											<a href="javascript:void(0)" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#tocModal">Manage Table of Content</a>
										</div>
									</div>
								</div>
								</div>
						</div>
						</div>
					</div>
		 </div>
	</form>

<div id="tocModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Table of Content</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
														
                                                <form id="tocForm" method="POST" action="<?= base_url('add-toc-item') ?>">
                                                   <input type="hidden" name="postId" id="postId" value="<?= $post->content_id ?>" />
													<table class="table table-bordered dt-responsive nowrap" width="100%">
					<tr><td>									<input type="text" name="tocItem" id="tocItem" class="for-control" placeholder="TOC Name"/></td><td>
														<input type="text" name="tocLink" id="tocLink" class="for-control" placeholder="TOC Link"/>
													
						</td><td><input type="submit" class="btn btn-info" value="Add New" />
						</td></tr></table>
													</form>
                                                        <table id="postsTable" class="table table-striped table-bordered dt-responsive nowrap" width="100%">
        <thead>
            <tr>
											<th>Name</th>
											<th>Link</th>
											</tr>
											</thead>
											<tbody>
										<?php   if(!empty(($post->toc))){ ?>
										<?php  foreach (json_decode($post->toc) as $value){ ?>
										<tr>
											<td><?= $value->name ?></td>
											<td><?= $value->link ?></td>
										</tr>
											<?php } } ?>
											</tbody>
										</table>	
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                          
                                        </div>
</div>
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script src="<?= base_url() ?>/assets/admin/libs/dropzone/min/dropzone.min.js"></script>
<script src="<?= base_url() ?>/assets/admin/libs/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>/assets/admin/js/pages/form-fileuploads.init.js"></script>
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
<input type="file" multiple="multiple" class="dz-hidden-input" tabindex="-1" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
<script>
   var baseurl = '<?= base_url() ?>';
	
	$('#tocForm').submit(function(){
	// Stop the form submitting
  	e.preventDefault(); // prevent actual form submit
    var form = $(this); 
    var url = form.attr('action'); //get submit url [replace url here if desired
		console.log(form+url);
		   $.ajax({
         type: "POST",
         url: url,
         data: form.serialize(), // serializes form input
         success: function(data){
            // console.log(data);
         }
		});
 
});
	  CKEDITOR.replace('editor2', {
      extraPlugins: 'uploadimage, image2',
      height: 300,

      // Upload images to a CKFinder connector (note that the response type is set to JSON).
      uploadUrl: baseurl+'/writable/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',

      // Configure your file manager integration. This example uses CKFinder 3 for PHP.
      filebrowserBrowseUrl: baseurl+'/writable/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: baseurl+'/writable/ckfinder/ckfinder.html?type=Images',
      filebrowserUploadUrl: baseurl+'/writable/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: baseurl+'/writable/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

      // The following options are not necessary and are used here for presentation purposes only.
      // They configure the Styles drop-down list and widgets to use classes.

      stylesSet: [{
          name: 'Narrow image',
          type: 'widget',
          widget: 'image',
          attributes: {
            'class': 'image-narrow'
          }
        },
        {
          name: 'Wide image',
          type: 'widget',
          widget: 'image',
          attributes: {
            'class': 'image-wide'
          }
        }
      ],

      // Load the default contents.css file plus customizations for this sample.
      contentsCss: [
        'https://cdn.ckeditor.com/4.18.0/full-all/contents.css',
        'https://ckeditor.com/docs/ckeditor4/4.18.0/examples/assets/css/widgetstyles.css'
      ],

      // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
      // resizer (because image size is controlled by widget styles or the image takes maximum
      // 100% of the editor width).
      image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
      image2_disableResizer: true,
      removeButtons: 'PasteFromWord'
    });
  </script>
<?=$this->endSection()?>