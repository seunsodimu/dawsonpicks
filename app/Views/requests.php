<html>
    <head>
        <meta charset="utf-8" />
<title>LAWBOSS IT Request Form </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="" name="description" />
<meta content="LAWBOSS" name="author" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> 
       <style>
           .file-drop-area {
    position: relative;
    display: flex;
    align-items: center;
    width: 450px;
    max-width: 100%;
    padding: 25px;
    border: 1px dashed rgba(255, 255, 255, 0.4);
    border-radius: 3px;
    transition: 0.2s;
    background-color: #026890;
}

.choose-file-button {
    flex-shrink: 0;
    background-color: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    padding: 8px 15px;
    margin-right: 10px;
    font-size: 12px;
    text-transform: uppercase
}

.file-message {
    font-size: small;
    font-weight: 300;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis
}

.file-input {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    cursor: pointer;
    opacity: 0
}

.mt-100 {
    margin-top: 100px
}
       </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-md-6"><br>
                    
                   
                    <br>
                <h2>IT Request Form</h2>
                <div style="padding: 10px;">
                   <?php if(session()->getFlashdata('responseStatus')=="success") {?>
										<div class="row">
										<span class="alert alert-success">
										<?= session()->getFlashdata('msg') ?>
										</span>
										</div>
										<?php } elseif(session()->getFlashdata('responseStatus')=="error") { ?>
										<div class="row">
										<span class="alert alert-danger">
										There was an error!
										</span>
										</div>
										<?php } ?>
                </div>
                 </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-6">
                    <form method="POST" action="<?= base_url('post-request') ?>" enctype="multipart/form-data">
                        <input type="hidden" name="issue" value="true"/>
                        <div class="form-group">
                            <label for="title">Request Title</label>
                            <input type="text" class="form-control" name="Title" id="Title" required=""/>
                        </div>
						<div class="form-group">
                            <label for="Department">Your Department</label>
                            <select class="form-control" name="Department" id="Department" required>
                                <option value="">Choose one</option>
                                <option>Accounting</option>
                                <option>Attorneys</option>
                                <option>Case Management</option>
								<option>Client Services and Litigation Referrals</option>
                                <option>Closing</option>
                                <option>Development</option>
                                <option>Front Office/Mailroom</option>
                                <option>HR</option>
                                <option>Intake</option>
                                <option>ISR</option>
                                <option>Lit</option>
                                <option>Marketing</option>
                                <option>Medical Records</option>
                                <option>Negotiations</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="RequestType">Request Type</label>
                            <select class="form-control" name="RequestType" id="RequestType">
                                <option value="">Choose one</option>
                                <option>Hardware</option>
                                <option value="Office Supply">Office Requests</option>
                                <option>Software</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="form-group" id="appl" style="display: none;">
                            <label for="RequestApplication">Application</label>
                            <select class="form-control" name="RequestApplication" id="RequestApplication">
                                <option class="OtherAssign">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Description">Description</label>
                            <textarea class="form-control" name="Description" id="Description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for=""><input type="checkbox" name="AddFile" id="AddFile" value="1" /> Add a screenshot? <span style="font-size: 10px"> (Accepted files: xls, xlsx, csv, doc, docx, jpg, jpeg, gif, png)</span></label>
                             </div>
                        <div class="form-group">
                            <div class="file-drop-area" id="filearea"> 
                                <span class="choose-file-button">Choose files</span> 
                                <span class="file-message">or drag and drop files here</span> 
                                <input class="file-input" name="uploadedFile" id="uploadedFile" type="file" multiple>
                            </div><br><br>
                            <div class="form-group" style="padding-top:10px">
                            <input type="submit" class="btn btn-info" value="Submit"/>
                        </div>
                </div>
                        
                    </form>
                    
            </div>
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script> 
       
       <script type="text/javascript">
       $(document).ready(function(){
  $('#filearea').hide();
});
       $('#RequestType').on('change', function() {
  if($('#RequestType').val()=="Office Supply"){
      $("#appl").show();
      $("#RequestApplication").show();
      $("#RequestApplication").empty();
      $("#RequestApplication").append('<option>Computers, Printers & Accessories Order</option><option>Furniture</option><option>Office Supplies</option><option>Other</option>');
      
  }
  else if($('#RequestType').val()=="Software"){
      $("#appl").show();
      $("#RequestApplication").show();
      $("#RequestApplication").empty();
      $("#RequestApplication").append('<option>Adobe</option><option>Desktop Login</option><option>Monday.com</option><option>Ring Central</option><option>Sharepoint</option><option>Salesforce</option><option>Slack</option><option>Other</option>');
  }
  else if($('#RequestType').val()=="Hardware"){
       $("#appl").show();
      $("#RequestApplication").show();
      $("#RequestApplication").empty();
      $("#RequestApplication").append('<option>Headset connectivity issues</option><option>System Errors</option><option>Other</option>');
  }
  else if($('#RequestType').val()=="Other"){
      $("#appl").hide();
      $("#RequestApplication").hide();
  }
  
        console.log($('#RequestType').val());
});
   $('#AddFile').change(function() {
        if(this.checked) {
            $('#filearea').show();
            $("file").prop('required',true);
        }else{
           $('#filearea').hide(); 
           $("file").prop('required',false);
        }      
    });
    
    $(document).on('change', '.file-input', function() {


var filesCount = $(this)[0].files.length;

var textbox = $(this).prev();

if (filesCount === 1) {
var fileName = $(this).val().split('\\').pop();
textbox.text(fileName);
} else {
textbox.text(filesCount + ' files selected');
}
});
       </script>
    </body>
</html>