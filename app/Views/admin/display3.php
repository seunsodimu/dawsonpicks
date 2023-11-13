<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<div class="container" style="margin-top:20px;">
    <div class="row pull-right">
       
        
    </div>

</div>

<div class="row" style="padding-left:50px; padding-right: 50px">
<div class="col-sm-12">
    <div class="col-md-3">
    <p><strong>Customer: </strong> <?= $customer ?></p>
    </div>
    <div class="col-md-3">
    <p><strong>Type: </strong><?= ucfirst($type) ?></p>
    </div>
    <div class="col-md-3">
    <p><strong>Document Type: </strong><?= ucfirst($docType) ?></p>
    </div>
    <div class="col-md-3">
    <p><strong>Report Date: </strong> <?= $date ?></p>
    </div>
</div>
<div class="col-sm-12">


<?php if($description != ""): ?>
<p><strong>Description: </strong> <?= $description ?></p>
<?php endif; ?>
<p><label> <input type="checkbox" id="hide7am" <?= ($FromTIme == '07:00') ? 'checked' : '' ?>> Hide Columns before 7am</label></p>
<p><label> <input type="checkbox" id="hide6pm" <?= ($ToTIme == '18:00') ? 'checked' : '' ?>> Hide Columns after 6pm</label></p>
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
</div>
   <div class="col-sm-12"> 
   <?= $table ?>
   </div>
   <div class="col-sm-12">
   <?= $summary ?>
   </div>
   </div>
                   
<?= $this->endSection() ?>