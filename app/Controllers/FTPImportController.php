<?php namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\PickModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use Config\Services\Email;


class FTPImportController extends BaseController
{

    public function index() {
       // connect and login to FTP server 

$ftp_username = getenv('DAWSON_FTP_USER');
$ftp_userpass = getenv('DAWSON_FTP_PASS');
$ftp_server = !empty($this->request->getGet('access')) ? "172.28.208.10" : "199.19.210.20";
//$ftp_server = "172.28.208.10"; //local
//$ftp_server = "199.19.210.20"; //public
$ftp_conn = ftp_connect($ftp_server) or die($this->notifyAdmin($_SERVER['SERVER_ADDR']." Could not connect to ".$ftp_server, "seun.sodimu@gmail.com", "", "Dawson KPI Upload Issue"));
$login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
ftp_pasv($ftp_conn, true) or die("Passive mode failed");
$filename = !empty($this->request->getGet('half')) ? "fcst0016_".date('m-d')."-23:30.CSV" : "fcst0016_".date('m-d-H', strtotime('+1 hour')).":00.CSV";
//$filename = "fcst0016_10-20-23:30.CSV";
$local_file = "assets/archive/".$filename;
//$server_file = "/logimaxedi/Speedo/picking/fcst0016_10-20-23_30.CSV";
$server_file = "/logimaxedi/Speedo/picking/".$filename;
//var_dump( $server_file); exit;
// download server file
$file_list = ftp_nlist($ftp_conn, "/logimaxedi/Speedo/picking/");
//var_dump($file_list); exit;
if (in_array($server_file, $file_list)) { 
     echo "Found file ". $server_file."...<br>";
if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII))
  {
  echo "Successfully written to ".$local_file."...<br>";

           

               // Reading file
               //$file = fopen("../public/csvfile/".$newName,"r");
               $file = fopen($local_file,"r"); //var_dump($file); exit;
               //$file = fopen($file_path, "r"); var_dump($file); exit;
               $i = 0;
               $numberOfFields = 24; // Total number of fields

               $importData_arr = array();

               // Initialize $importData_arr Array
               while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                  $num = count($filedata);
//var_dump( $num); exit;
                  // Skip first row & check number of fields
                  if($i > 1 && $num == $numberOfFields){ 
                    
//var_dump( $filedata[8]); exit;
                     $date5 = date('Y-m-d', strtotime($filedata[7]));
                     $date7 = date('Y-m-d', strtotime($filedata[9]));
                     $time6 = date('H:i:s', strtotime($filedata[8]));
                     $time8 = date('H:i:s', strtotime($filedata[10]));

                     $importData_arr[$i]['store_no'] = $filedata[0];
                     $importData_arr[$i]['lot_no'] = $filedata[1];
                     $importData_arr[$i]['item_no'] = $filedata[2];
                     $importData_arr[$i]['storers_lot_no'] = $filedata[3];
                     $importData_arr[$i]['doc_no'] = $filedata[4];
                     $importData_arr[$i]['consignee'] = $filedata[5];
                     $importData_arr[$i]['caselabel'] = $filedata[6];
                     $importData_arr[$i]['start_date'] =$date5;
                     $importData_arr[$i]['start_time'] = $time6;
                     $importData_arr[$i]['complete_date'] = $date7;
                     $importData_arr[$i]['complete_time'] = $time8;
                     $importData_arr[$i]['picker'] = $filedata[11];
                     $importData_arr[$i]['pallet'] = $filedata[12];
                     $importData_arr[$i]['doc_type'] = $filedata[13];
                     $importData_arr[$i]['reason'] = $filedata[14];
                     $importData_arr[$i]['full_desc'] = $filedata[15];
                     $importData_arr[$i]['cust_name'] = $filedata[16];
                     $importData_arr[$i]['ship_to_rec_from'] = $filedata[17];
                     $importData_arr[$i]['transaction_qty'] = $filedata[18];
                    $importData_arr[$i]['upload_by'] = 'scheduled';

                    ////pallet and cases
                     if(str_contains($filedata[16], 'Speedo')){
                        if((str_contains($filedata[17], 'ASM')) || (str_contains($filedata[17], 'PALLET'))){
                            $importData_arr[$i]['new_type'] = 'pallet';
                        }else{
                            $importData_arr[$i]['new_type'] = 'cases';
                        }
                       }
                       elseif(str_contains($filedata[16], 'Sprout')){
                           if((trim($filedata[17])[-1] == 'P') || (trim($filedata[13]) == 'Putaway')){
                               $importData_arr[$i]['new_type'] = 'pallet';
                       }else{
                          $importData_arr[$i]['new_type'] = 'cases';
                      }
                       }
                       else{
                           $importData_arr[$i]['new_type'] = 'cases';
                       } 

                     /////// pallet and cases end
                  }

                  $i++;

               }
               fclose($file);
 //var_dump($importData_arr); exit;
               // Insert data
               $pick = new PickModel();  
                 $count = 0;
                 if(!empty($importData_arr)){
                    $pick->removeDay($date5);
                 }
     foreach($importData_arr as $userdata){
                     
                    if($pick->insert($userdata)){
                      //   var_dump($userdata); exit;
                         $count++;
                     } else{
                        echo "not inserted";
                     }                                 
                 }
                 echo $count." rows updated. Last pick at  ".$date5." : ".$time8;
                 $this->notifyAdmin("Uploaded ".$count." rows from ".$server_file, "developer@seun.me", "", "KPI Upload Details");

  }
else
  {
  echo "Failed to download the file ".$server_file;
    $this->notifyAdmin("Failed to download the file ".$server_file, "developer@seun.me", "","Drop File Error");
  }
}else{
    $missing_file = str_replace("fcst0016_", "", $filename);
    $missing_file = str_replace(".CSV", "", $missing_file);
    $notmsg = "The file does not exist on the FTP server ".$server_file;
    $notmsg .= "<br>This means the file has not been uploaded to the FTP server yet for the time ".$missing_file;
    $this->notifyAdmin($notmsg, "developer@seun.me", "DennisB@dawsonlogistics.com","Drop File Error");
}

// close connection
ftp_close($ftp_conn);
    }


   

   public function upload($file, $fieldname)
    { 
        $validationRule = [
            'uploadedFile' => [
                'label' => 'File',
                'rules' => 'uploaded['.$fieldname.']'
                   . '|max_size['.$fieldname.',100024]'
                    . '|ext_in['.$fieldname.',csv]',
            ],
        ];
        if (! $this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];
        }else{

        if (! $file->hasMoved()) {
            $filestore = $file->store();
            $filepath = WRITEPATH . 'uploads/' . $filestore;
            $data = ['uploaded_flleinfo' => $filepath];
            $data['readfilepath'] = 'writable/uploads/'.$filepath; 
            $data['filemime'] = $file->getClientMimeType();
            
        } else {
            $data = ['errors' => 'The file has already been moved.'];

            
        }
         
    }
    //  var_dump($data);  phpinfo(); exit;
        return $data;
    }

   

public function showFTP()
    {
        $to = "seun.sodimu@gmail.com";//$this->request->getVar('mailTo');
        $subject = "Dawson KPI Tool"; //$this->request->getVar('subject');
        $message = "This is to check sending"; //$this->request->getVar('message');
        
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('developer@seun.me', 'Dawson KPI Tool');
        
        $email->setSubject($subject);
        $email->setMessage($message);
        $send = $email->send();
        if ($send) 
		{
            echo 'Email successfully sent!';$data = $email->printDebugger();
            print_r($email);
        } 
		else 
		{
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }

    public function testFTP()
    {


try{
    $loc_ip = $_SERVER['SERVER_ADDR'];
$ftp_username = "SPEEDO";
$ftp_userpass = "Tech5633";
//$ftp_server = "172.28.208.10";
$ftp_server = $this->request->getPost('ip');
$port = $this->request->getPost('port');
$ttl = $this->request->getPost('ttl');
$fail_msg = $loc_ip." could not connect to ".$ftp_server." <a href='".base_url('test-ftp')."'>Back</a>";
$ftp_conn = ftp_connect($ftp_server, $port, $ttl) or die($fail_msg);
$login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
session()->setFlashdata('message', 'Connected and validated');
session()->setFlashdata('alert_class', 'alert-success'); 
        $data['title'] = "Test FTP";
        return view("admin/testFTP", $data);   

}catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
  session()->setFlashdata('message', 'Login failed');
    session()->setFlashdata('alert-class', 'alert-danger');
     return view("admin/testFTP", $data);
}
    }


    public function notifyAdmin($msg, $to1, $to2, $subj)
    {
        $email = \Config\Services::email();
$email->setFrom('report@dawson-reports.com', 'Dawson Reports');
$email->setTo($to1);
if(!empty($to2)){
    $email->setCC($to2);
}

$email->setSubject($subj);
$email->setMessage($msg);

$email->send();
    }

    public function manualFTP() {
        // connect and login to FTP server 
 
 $ftp_username = "SPEEDO";
 $ftp_userpass = "Tech5633";
 //$ftp_server = "172.28.208.10";
 $ftp_server = "199.19.210.20"; //public
 $ftp_conn = ftp_connect($ftp_server) or die($this->notifyAdmin($_SERVER['SERVER_ADDR']." Could not connect to ".$ftp_server, "seun.sodimu@gmail.com", "", "Dawson KPI Upload Issue"));
 $login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
 $filename = $this->request->getGet('fn');
 $local_file = "assets/archive/".$filename;
 //$server_file = "/logimaxedi/Speedo/picking/fcst0016_11-09-22-11:04:01.CSV";
 $server_file = "/logimaxedi/Speedo/picking/".$filename;
 
 // download server file
 if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII))
   {
  // echo "Successfully written to $local_file.";
 
            
 
                // Reading file
                //$file = fopen("../public/csvfile/".$newName,"r");
                $file = fopen($local_file,"r"); //var_dump($file); exit;
                //$file = fopen($file_path, "r"); var_dump($file); exit;
                $i = 0;
                $numberOfFields = 20; // Total number of fields
 
                $importData_arr = array();
 
                // Initialize $importData_arr Array
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                   $num = count($filedata);
 //var_dump( $num); exit;
                   // Skip first row & check number of fields
                   if($i > 1 && $num == $numberOfFields){ 
                     
 //var_dump( $filedata[8]); exit;
                      $date5 = date('Y-m-d', strtotime($filedata[7]));
                      $date7 = date('Y-m-d', strtotime($filedata[9]));
                      $time6 = date('H:i:s', strtotime($filedata[8]));
                      $time8 = date('H:i:s', strtotime($filedata[10]));
 
                      $importData_arr[$i]['store_no'] = $filedata[0];
                      $importData_arr[$i]['lot_no'] = $filedata[1];
                      $importData_arr[$i]['item_no'] = $filedata[2];
                      $importData_arr[$i]['storers_lot_no'] = $filedata[3];
                      $importData_arr[$i]['doc_no'] = $filedata[4];
                      $importData_arr[$i]['consignee'] = $filedata[5];
                      $importData_arr[$i]['caselabel'] = $filedata[6];
                      $importData_arr[$i]['start_date'] =$date5;
                      $importData_arr[$i]['start_time'] = $time6;
                      $importData_arr[$i]['complete_date'] = $date7;
                      $importData_arr[$i]['complete_time'] = $time8;
                      $importData_arr[$i]['picker'] = $filedata[11];
                      $importData_arr[$i]['pallet'] = $filedata[12];
                      $importData_arr[$i]['doc_type'] = $filedata[13];
                      $importData_arr[$i]['reason'] = $filedata[14];
                      $importData_arr[$i]['full_desc'] = $filedata[15];
                      $importData_arr[$i]['cust_name'] = $filedata[16];
                      $importData_arr[$i]['ship_to_rec_from'] = $filedata[17];
                      $importData_arr[$i]['transaction_qty'] = $filedata[18];
                     $importData_arr[$i]['upload_by'] = 'scheduled';
                     if((str_contains($filedata[17], 'ASM')) || (str_contains($filedata[17], 'PALLET'))){
                         $importData_arr[$i]['new_type'] = 'pallet';
                     }else{
                         $importData_arr[$i]['new_type'] = 'cases';
                     }
 
                   }
 
                   $i++;
 
                }
                fclose($file);
  //var_dump($importData_arr); exit;
                // Insert data
                $pick = new PickModel();  
                  $count = 0;
                  if(!empty($importData_arr)){
                     $pick->removeDay($date5);
                  }
      foreach($importData_arr as $userdata){
                      
                     if($pick->insert($userdata)){
                       //   var_dump($userdata); exit;
                          $count++;
                      } else{
                         echo "not inserted";
                      }                                 
                  }
                  echo $count." : ".$date5;
                  $this->notifyAdmin("Uploaded ".$count." rows from ".$server_file, "developer@seun.me", "", "Dawson KPI Upload Update");
 
   }
 else
   {
   $this->notifyAdmin("Error downloading ".$server_file, "developer@seun.me", "", "Dawson KPI Upload Issue");
   }
 
 // close connection
 ftp_close($ftp_conn);
     }

     public function getFTPFileList(){
$ftp_username = getenv('DAWSON_FTP_USER');
$ftp_userpass = getenv('DAWSON_FTP_PASS');
$ftp_server = !empty($this->request->getGet('access')) ? "172.28.208.10" : "199.19.210.20";

$ftp_conn = ftp_connect($ftp_server) or die($this->notifyAdmin($_SERVER['SERVER_ADDR']." Could not connect to ".$ftp_server, "seun.sodimu@gmail.com", "", "Dawson KPI Upload Issue"));
$login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
ftp_pasv($ftp_conn, true) or die("Passive mode failed");
$file_list = ftp_nlist($ftp_conn, "/logimaxedi/Speedo/picking/");
// close connection
ftp_close($ftp_conn);
return $file_list;
     }

     public function getFTPFileListCache() {
        // Load the cache library
        $cache = \Config\Services::cache();
    
        // Define a unique key for your cached file list
        $cacheKey = 'ftp_file_list';
        $file_list = $cache->get($cacheKey);
    
        // Check if file list is already in cache and not expired
        if ($file_list !== null) {
            return $file_list;
        }
            $file_list = $this->getFTPFileList();
    
        // Calculate the remaining time until the top of the next hour
    $current_time = new \DateTime();
    $top_next_hour = (clone $current_time)->modify('+1 hour')->setTime((int) $current_time->format('H') + 1, 0, 0);
    $expiration = $top_next_hour->getTimestamp() - $current_time->getTimestamp();

    // Store file list in cache with expiration set to the top of the next hour
    $cache->save($cacheKey, $file_list, $expiration);
    
        return $file_list;
    }

    public function showFileList()
    { //var_dump('show'); exit;
        // $file_list = $this->getFTPFileListCache();
        // echo "Last entry is: ".end($file_list);
        // foreach($file_list as $file){
        //     echo $file."<br>";
        // }
        $dir = "assets/archive/";
        $files2 = scandir($dir);
        $recent = end($files2);
        $modified_time = filemtime($dir.$recent);
        // foreach($files2 as $file){
        //     echo $file.". Last modified: ".date("F d Y H:i:s.", filemtime($dir.$file));
        //     echo "<br>";
        // }
        
      //  echo $recent.". Last modified: ".date("F d Y H:i:s.", $modified_time);
        $local_file = "assets/archive/".$recent;
        // Set headers to trigger the download
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.basename($local_file).'.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($local_file));
    
        // Clear output buffer
        ob_clean();
        flush();
    
        // Read the file and output its contents
        readfile($local_file);
        exit;
    }

    public function downloadLastCSVFromFTP() {
        // FTP credentials and server
        $ftp_username = getenv('DAWSON_FTP_USER');
        $ftp_userpass = getenv('DAWSON_FTP_PASS');
        $ftp_server = !empty($this->request->getGet('access')) ? "172.28.208.10" : "199.19.210.20";
    
        // Establish FTP connection and login
        $ftp_conn = ftp_connect($ftp_server) or die($this->notifyAdmin($_SERVER['SERVER_ADDR']." Could not connect to ".$ftp_server, "seun.sodimu@gmail.com", "", "Dawson KPI Upload Issue"));
        $login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
        ftp_pasv($ftp_conn, true) or die("Passive mode failed");
    
        // Retrieve file list from FTP server
        $file_list = ftp_nlist($ftp_conn, "/logimaxedi/Speedo/picking/");
    
        // Check if file list is empty
        if (empty($file_list)) {
            ftp_close($ftp_conn);
            die("No files found on FTP server.");
        }
    
        // Get the last file from the list
        $last_file = end($file_list);
    
        // Define the path for local and server file
        $local_file = "assets/archive/" . basename($last_file);
        $server_file = "/logimaxedi/Speedo/picking/" . $last_file;
    
        // Download the file
        if (ftp_get($ftp_conn, $local_file, $server_file, FTP_BINARY)) {
            echo "Successfully written to $local_file\n";
        } else {
            echo "There was a problem\n";
        }
    
        // Close FTP connection
        ftp_close($ftp_conn);
    
        // Set headers to trigger the download
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.basename($local_file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($local_file));
    
        // Clear output buffer
        ob_clean();
        flush();
    
        // Read the file and output its contents
        readfile($local_file);
        exit;
    }
    
    
    
    
}