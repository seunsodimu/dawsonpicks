<?php $session = \Config\Services::session(); ?>
<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php
$pick_ = json_encode($picks);
//var_dump(array_column($picks, 'complete_time'));
?>
<div class="container" style="margin-top:20px;">
    <div class="row pull-right">
        <h1><?= date('F j, Y', strtotime($sDate)). " (".$type." | ".$docType.")" ?></h1>
    <a href="<?= site_url('admin') ?>">Home</a> | <a href="<?= site_url('logout') ?>">Logout</a>

        
    </div>

</div>

<div class="row" style="padding-left: 100px; padding-right: 100px; right: 50px">
       <?= $display ?>
   </div>

<div class="container" style="margin-top:20px;">
    <div class="row">
   
        <?= $display1 ?>
    </div>
   

</div>


                   
<?= $this->endSection() ?>