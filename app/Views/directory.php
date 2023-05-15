<?=$this->extend("layout/pages")?>

<?=$this->section("content")?>
 <div class="span8 page-content">

<!--                                                <ul class="breadcrumb">
                                                        <li><a href="#">Knowledge Base Theme</a><span class="divider">/</span></li>
                                                        <li><a href="#" title="View all posts in Server &amp; Database">Server &amp; Database</a> <span class="divider">/</span></li>
                                                        <li class="active">Integrating WordPress with Your Website</li>
                                                </ul>-->

                                                <article class=" type-post format-standard hentry clearfix">
                                                    <h1><?= $page_title ?> </h1>
                                                    <br>
                                                    
    
    <hr>
    
        <table id="personnelTable" class="table table-striped dt-responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Job Title</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><span style="display: none"><?= $user['last_name'] ?></span><a href="<?= base_url().'/personnel/'.$user['last_name'].'-'.$user['employee_id'] ?>"/><?= $user['first_name']." ".$user['last_name'] ?></a></td>
                <td><?= $user['job_title'] ?></td>
                <td><?= $user['work_email'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
        
    </table>
    
    
                                                </article>

                                              

                                               <!-- end of comments -->

                                        </div>
                <!-- End of Page Container -->
<?=$this->endSection()?>
  
<?=$this->section("scripts")?>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>            
<script type="text/javascript">
               
$(document).ready(function() {
    $('#personnelTable').DataTable( {
        search: {
            return: true
        },
		"iDisplayLength": 50,
		"lengthMenu": [[10, 25, 50, 75, 100, 150, 200, -1], [10, 25, 50, 75, 100, 150, 200, "All"]]
    } );
} );
</script>
<?=$this->endSection()?>
  