<?php $session = \Config\Services::session(); ?>
<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php

?>
<div class="container" style="margin-top:20px;">
    <div class="row pull-right">
        <h1>Uploads</h1>
    <a href="<?= site_url('admin') ?>">Home</a> | <a href="<?= site_url('upload-summary2') ?>">Upload Summary2</a> | <a href="<?= site_url('logout') ?>">Logout</a>

        
    </div>

</div>

<div class="row" style="padding-left:50px; padding-right: 50px">
    <table id='rowtbl2' class='table table-striped table-bordered' width='100%'>
     <thead>
<tr>
<th>Transaction Date</th>
<th>Count</th>
<th>Upload Date</th>
<th>Uploaded by</th>
</tr>
     </thead>
     <tbody>
<?php foreach($summary as $item) { ?>
    <tr>
<td><?= date('m/d/Y', strtotime($item->transaction_date)) ?> </td>
<td><?= $item->count ?></td>
<td><?= date('m/d/Y', strtotime($item->upload_date)) ?> </td>
<td><?= $item->upload_by ?> </td>
    </tr>
<?php } ?>
     </tbody>
     </table>
    
   
       
   
   </div>

 
                   
<?= $this->endSection() ?>