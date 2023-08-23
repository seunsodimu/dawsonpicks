<html>
    <head>
        <meta charset="utf-8" />
<title>LAWBOSS Suggestion Box Form </title>
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
            <div class="row justify-content-md-center" style="margin-top:160px">
				<div class="col-md-6">
				<div class="card">
  <div class="card-header text-center"><h2>LAWBOSS Suggestion Box</h2></div>
  <div class="card-body">
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
	  <div class="text-center">
		  <p>
		  From great Team Members come great ideas! 
<p>
	<p>
Lawboss values the ideas and thoughts of each Team Member, and we want you to have an easy way of sharing those ideas and thoughts. Via our online Suggestion Box, a Team Member can <u>anonymously</u> submit their contributions.  All suggestions will be reviewed and considered by management, but please understand that not all suggestions will be eligible for implementation.   
		  </p>
		  <p>
Take the opportunity today to communicate those terrific ideas and thoughts.  Be a meaningful contributor to Lawboss success!
		  </p>
	  </div>
					<form method="POST" action="<?= base_url('post-suggest') ?>">
                        <input type="hidden" name="issue" value="true"/>
                        <div class="form-group">
                            <label for="title">Category</label>
                            <select class="form-control" id="Title" name="Title">
								<option>Processes and/or Procedures</option>
								<option>Training</option>
								<option>Productivity</option>
								<option>Office Facility or Equipment </option>
								<option>Cost-Saving Ideas</option>
								<option>Firm Events</option>
								<option>Team Member Morale</option>
								<option>Other</option>
							</select>
                        </div>
                        <div class="form-group">
                            <label for="Description">Description</label>
                            <textarea class="form-control" name="Description" id="Description"></textarea>
                        </div>
                        <div class="form-group text-center">
                            <div class="form-group" style="padding-top:10px">
                            <input type="submit" class="btn btn-info" value="Submit"/>
                        </div>
                </div>
                        
                    </form>
	  </div>
					</div>
 
</div>
               
        </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script> 
       
       
    </body>
</html>