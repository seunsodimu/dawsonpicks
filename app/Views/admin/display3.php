<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<div class="container" style="margin-top:20px;">
    <div class="row pull-right">
       
        
    </div>

</div>

<div class="row" style="padding-left:50px; padding-right: 50px">
<p><strong>Customer: </strong> <?= $customer ?></p>
<p><strong>Type: </strong><?= ucfirst($type) ?></p>
<p><strong>Document Type: </strong><?= ucfirst($docType) ?></p>
<p><strong>Report Date: </strong> <?= $date ?></p>
<p><a href="<?= base_url() ?>/admin"> <i class="fa fa-home"></i> Home</a> | <a href="<?= $layout ?>"><i class="fa fa-recycle"></i> Rotate</a></p>
   <div class="col-sm-12"> 
   <?= $table ?>
   </div>
   <div class="col-sm-12">
   <?= $summary ?>
   </div>
   </div>
                   
<?= $this->endSection() ?>