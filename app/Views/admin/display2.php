<?php $session = \Config\Services::session(); ?>
<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<div class="container" style="margin-top:20px;">
    <div class="row pull-right">
        <h2><?= $pickselector."  ". date('F j, Y', strtotime($fDate))." to ".date('F j, Y', strtotime($tDate)). " (".str_replace('s','ly', $reportType)." ".$type." | ".$docType.")" ?></h2>
    <a href="<?= site_url('admin') ?>">Home</a> | <a href="<?= site_url('logout') ?>">Logout</a>

        
    </div>

</div>

<div class="row" style="padding-left:50px; padding-right: 50px">
    <table id='rowtbl2' class='table table-striped table-bordered' width='100%'>
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
            echo "<td>".$tr['first_col']."</td>";
            foreach($tr['pick_counts'] as $td){
                if($td==0) { echo "<td class='num-count zerocount'>".$td."</td>"; } else {echo "<td class='num-count'>".$td."</td>";}
            }
            echo "</tr>";
        }
        ?>  
       
       
            <tr>
            <?php
         foreach($table_footer as $th1){ 
            if(is_numeric($th1)){
             if(($th1>=1)) { echo "<th class='num-count'>".$th1."</td>"; } else { echo "<th class='num-count zerocount'>".$th1."</td>";}  
            }else{ echo "<th>".$th1."</th>";}
         }
        ?> 
        </tr>
        </tbody>
      
   
       
   
   </div>

                   
<?= $this->endSection() ?>