<?php $session = \Config\Services::session(); ?>
<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php

?>
<div class="container" style="margin-top:20px;">
    <div class="row pull-right">
        <h1>Uploads</h1>
    <a href="<?= site_url('admin') ?>">Home</a> | <a href="<?= site_url('upload-summary') ?>">Upload Summary</a> | <a href="<?= site_url('logout') ?>">Logout</a>

        
    </div>

</div>

<div class="row" style="padding-left:50px; padding-right: 50px">
    <table id='rowtbl2' class='table table-striped table-bordered' width='100%'>
     <thead>
<tr>
<th>Date</th>
<th>Count</th>
<th>Last pick of the day</th>
<th>Pick by</th>
<th>Upload Date</th>
<th>Upload by</th>
</tr>
     </thead>
     <tbody>
<?php foreach($summary as $item) { 
    $upload_date = new \DateTime($item['upload_date'], new \DateTimeZone('America/New_York'));
    $last_pick_time = new \DateTime($item['last_pick_time'], new \DateTimeZone('America/Denver'));
    $upload_date->setTimezone(new \DateTimeZone('America/Chicago'));
    $last_pick_time->setTimezone(new \DateTimeZone('America/Chicago'));
    ?>
    <tr>
<td><?= date('m/d/Y', strtotime($item['complete_date'])) ?> </td>
<td><?= $item['pick_count'] ?></td>
<td><?= date('m/d/Y', strtotime($item['complete_date']))." ".$last_pick_time->format('h:i a') ?> </td>
<td><?= $item['pick_by'] ?> </td>
<td><?= $upload_date->format('m/d/Y h:i a') ?> </td>
<td><?= $item['upload_by'] ?> </td>
    </tr>
<?php } ?>
     </tbody>
     </table>
    
   
       
   
   </div>

 
                   
<?= $this->endSection() ?>