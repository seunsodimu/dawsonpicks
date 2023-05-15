<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>
<?=$this->extend("layout/admin")?>

<?=$this->section("custom_css")?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<style>
	#wrapper{
	overflow-y:	unset !important;
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


		<table id="postsTable" class="table table-striped table-bordered dt-responsive" width="100%">
        <thead>
            <tr>
                <th>Date Created</th>
                <th>Post Title</th>
               
                <th>Category</th>
                <th>Status</th>
                <th>Created By</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post) { ?>
            <tr>
                <td><?= $post->created_at ?></td>
                <td><a href="<?= base_url().'/post/'.$post->content_id ?>"><?= $post->title ?></a></td>
               
                <td><?= $post->subcat_title ?></td>
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

		

                                         
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>    
<script>
   var baseurl = '<?= base_url() ?>';
	  $(document).ready(function() {
    $('#postsTable').DataTable( {
       "iDisplayLength": 50,
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
} );
  </script>
<?=$this->endSection()?>