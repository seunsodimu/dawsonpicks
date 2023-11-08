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
<?php if($description != ""): ?>
<p><strong>Description: </strong> <?= $description ?></p>
<?php endif; ?>
<p><a href="<?= base_url() ?>/admin"> <i class="fa fa-home"></i> Home</a>
<?php if($layout != ""): ?>
    | <a href="<?= $layout ?>"><i class="fa fa-recycle"></i> Rotate</a>
<?php endif; ?>
<?php if($links != ""): ?>
    | <a href="<?= $links ?>&intv=5"><i class="fa fa-hourglass"></i> 5mins Interval</a>
    | <a href="<?= $links ?>&intv=10"><i class="fa fa-hourglass"></i> 10mins Interval</a>
    | <a href="<?= $links ?>&intv=15"><i class="fa fa-hourglass"></i> 15mins Interval</a>
    | <a href="<?= $links ?>&intv=30"><i class="fa fa-hourglass"></i> 30mins Interval</a>
    | <a href="<?= $links ?>&intv=60"><i class="fa fa-hourglass"></i> 1 hour Interval</a>
<?php endif; ?>
</p>
   <div class="col-sm-12"> 
   <?= $table ?>
   </div>
   <div class="col-sm-12">
   <?= $summary ?>
   </div>
   </div>
                   
<?= $this->endSection() ?>