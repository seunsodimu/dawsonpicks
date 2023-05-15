<?php $session = \Config\Services::session(); ?>
<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<div class="container" style="margin-top:20px;">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Test FTP Connection</div>
                <div class="panel-body">
                   <?php
 
 if(!empty($session->getFlashdata('message'))){ 
    echo "<div class='alert ".$session->getFlashdata('alert_class')."'>".$session->getFlashdata('message')."</div>"; };
?>

<form id="" action="<?= base_url('post-ftp');?>" method="post">

<div class="form-group">
    <label>Origin IP Address</label>
    <input type="text" class="form-control" value="<?= $_SERVER['SERVER_ADDR'] ?>" readonly name="">
</div>
<div class="form-group">
<label>Port</label>
<input type="number" name="port" class="form-control" value="21" required>
</div>
<div class="form-group">
<label>Timeout</label>
<input type="number" name="ttl" class="form-control" value="20" required>
</div>
<div class="form-group">
<label>Destination IP Address</label>
<input type="text" name="ip" class="form-control" value="208.74.48.231" required>
</div>
<div class="form-group">
    <input type="submit" id="submit-btn-view" value="Submit" class="btn btn-lg btn-success" />
    <!--<input type="reset" id="reset-btn-view" value="Reset" class="btn btn-lg btn-danger" />-->
</div>
</form>
                </div>
            </div>
        </div>

        
    </div>
   
</div>
<?= $this->endSection() ?>