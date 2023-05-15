<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>

<?=$this->section("custom_css")?>
<link href="<?= base_url() ?>/assets/admin/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>/assets/admin/libs/dropify/css/dropify.min.css" rel="stylesheet" type="text/css">
<?=$this->endSection()?>
<?=$this->section("content")?>
<form method="POST" action="<?= base_url('create_post') ?>" id="newPostForm" enctype='multipart/form-data'>
	 <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid"> 

                        <div class="row">
<!--							<form method="POST" action="" id="newPostForm">-->
                            <div class="col-9">
								<div class="card">
                                    <div class="card-body">
										<?php if(session()->getFlashdata('responseStatus')=="success") {?>
										<div class="row">
										<span class="alert alert-success">
										New post added! <a href="<?= base_url().'/training/'.session()->getFlashdata('newSlug') ?>" target="_blank">View it here</a>
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
	echo "<option value='".$subcat['subcat_id']."'>".$subcat['title']."</option>";
} ?>
											</select>
										</div>
                                        <div class="mb-3">
										<label for="PostTitle">Post Title</label>
											<input type="text" class="form-control" name="PostTitle" id="PostTitle"/>
										</div>
  <textarea cols="10" id="editor2" name="editor2" rows="10" data-sample-short></textarea>
											

									</div>
								</div>
							</div>
								<div class="col-3">
								<div class="card">
									<div class="card-body">
								
                                        <div class="mb-3">
											<div class="input-group">
											<select class="form-control" data-width="100%" name="PostStatus">
												<option>Publish</option>
												<option>Draft</option>
											</select>
                                                    <input type="submit" class="btn input-group-text btn-success waves-effect waves-light" value="Save" />
                                                </div>
										</div>
										<hr>
										
										<div class="mb-3">
											
												<label for="ContetType">Contet Type</label>
											<select class="form-control" data-width="100%" name="ContentType">
												<option>Standard</option>
												<option>Video</option>
												<option>Image</option>
											</select>
                                                
										</div>
										<hr>
										<!--<div class="mb-3">
											<label for="DisplayImage">Display Image</label>
											<input type="file" data-plugins="dropify" name="DisplayImage" id="DisplayImage" />
										</div>
										<hr>-->
										<div class="mb-3">
											<label for="Attachment">Add an attachment (pdf, word, excel)</label>
											<input type="file" data-plugins="dropify" name="Attachment" id="Attachment" />
										</div>
									</div>
								</div>
								</div>
<!--								</form>-->
						</div>
						</div>
					</div>
		 </div>
	</form>
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script src="<?= base_url() ?>/assets/admin/libs/dropzone/min/dropzone.min.js"></script>
<script src="<?= base_url() ?>/assets/admin/libs/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>/assets/admin/js/pages/form-fileuploads.init.js"></script>
<input type="file" multiple="multiple" class="dz-hidden-input" tabindex="-1" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
<script>
   var baseurl = '<?= base_url() ?>';
	  CKEDITOR.replace('editor2', {
      extraPlugins: 'uploadimage, image, videoembed, videodetector',
      height: 300,

      // Upload images to a CKFinder connector (note that the response type is set to JSON).
      uploadUrl: baseurl+'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',

      // Configure your file manager integration. This example uses CKFinder 3 for PHP.
      filebrowserBrowseUrl: baseurl+'/assets/ckfinder/ckfinder.html',
      filebrowserImageBrowseUrl: baseurl+'/assets/ckfinder/ckfinder.html?type=Images',
      filebrowserUploadUrl: baseurl+'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
      filebrowserImageUploadUrl: baseurl+'/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

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
         baseurl+'/assets/ckeditor/contents.css'
       
      ],
		// 'https://ckeditor.com/docs/ckeditor4/4.18.0/examples/assets/css/widgetstyles.css'
      // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
      // resizer (because image size is controlled by widget styles or the image takes maximum
      // 100% of the editor width).
      image2_alignClasses: ['image-align-left', 'image-align-center', 'image-align-right'],
      image2_disableResizer: true,
      removeButtons: 'PasteFromWord'
    });
  </script>
<?=$this->endSection()?>