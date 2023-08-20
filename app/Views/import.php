<?php $session = \Config\Services::session(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Codeigniter 4 Import Excel or CSV File into Database Example - Laratutorials.com</title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<body>
<?php
 !empty($validation) ?var_dump($errors):""; 
 !empty($session->getFlashdata('message')) ?var_dump($session->getFlashdata('message')) :"";
?>

<form action="<?= base_url('import-file');?>" method="post" enctype="multipart/form-data">
    Upload excel file : 
    <input type="file" name="file" value="" /><br><br>
    <input type="submit" name="submit" value="Upload" />
</form>
</body>
</html>