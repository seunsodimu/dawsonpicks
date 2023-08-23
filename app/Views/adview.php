<?=$this->extend("layout/admin")?>

<?=$this->section("custom_css")?>
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
                                <form method="post" action="" class="card-body" id="myForm">
                                                   <select class="form-control" id="callType" name="callType">
													   <option>pUpdate</option>
													   <option>pUpdateDeact</option>
													   <option>pUpdateOrgChart</option>
													   </select>
                                    <span class="input-icon icon-end">
                                        <textarea rows="3" class="form-control" id="tk" name="tk" placeholder=""></textarea>
                                    </span>
                                    <div class="pt-1 float-end">
                                       <input type="submit" id="submitform" class="btn btn-info waves-effect waves-light" value="Run" />
                                    </div>
                                    

                                </form>
                                </div>

                                
                                </div>
								</div>
							</div>
								
						</div>
					</div>
		 </div>

       
   

                                         
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript">
	 var baseurl = '<?= base_url() ?>';
	
	
	$("#myForm").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = baseurl+'/'+$('#callType').val();
    
    $.ajax({
        type: "POST",
        url: actionUrl,
        data: form.serialize(), // serializes the form's elements.
        success: function(data)
        {
          alert(data); // show response from the php script.
        }
    });
    
});
</script>
<?=$this->endSection()?>