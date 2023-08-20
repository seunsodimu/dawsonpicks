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
        <button class="btn btn-primary" id="btn-refresh"><i class="fa fa-refresh" id="refresh-icon"></i> <span id="refresh-txt">Refresh</span></button> |
    <a href="<?= site_url('admin') ?>">Home</a> | <a href="<?= site_url('logout') ?>">Logout</a>

        
    </div>

</div>

<div class="row" style="padding-left:50px; padding-right: 50px">
    <table id='rowtbl1' class='table table-striped table-bordered' width='100%'>
        <thead>
            <tr>
                 <?php
        foreach($table_head as $th){ echo "<th>".$th['start']."</th>"; }
        ?>  
            </tr>
         
        </thead>
        <tbody>
           <?php
        foreach($table_body as $tr){
            echo "<tr>";
            echo "<td>".$tr['picker']."</td>";
            foreach($tr['pick_counts'] as $td){
                if($td==0) { echo "<td class='num-count zerocount'>".$td."</td>"; } else {echo "<td class='num-count'>".$td."</td>";}
            }
            echo "</tr>";
        }
        ?>  
        </tbody>
        <tfoot>
            <tr>
            <?php
         foreach($table_footer as $th1){ 
            if(is_numeric($th1)){
             if(($th1>=1)) { echo "<th class='num-count'>".$th1."</td>"; } else { echo "<th class='num-count zerocount'>".$th1."</td>";}  
            }else{ echo "<th>".$th1."</th>";}
         }
        ?> 
        </tr>
        </tfoot>
      </table>
   
       <div style="padding-bottom: 120px;"><hr></div>
   <?= $display1 ?>
   </div>

 

                   
<?= $this->endSection() ?>