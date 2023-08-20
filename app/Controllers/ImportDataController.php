<?php namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\PickModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class ImportDataController extends BaseController
{

    public function index() {
        return view('import');
      
    }

   // File upload and Insert records
   public function importFile(){
var_dump(session()->get('name')); exit;
      // Validation
$fieldname = 'file';
  $validationRule = [
            'uploadedFile' => [
                'label' => 'File',
                'rules' => 'uploaded['.$fieldname.']'
                    . '|mime_in['.$fieldname.',image/jpg,image/jpeg,image/gif,image/png,image/webp,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf]'
                    . '|max_size['.$fieldname.',1024]',
            ],
        ];

if (! $this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];
      // $input = $this->validate([
      //    'file' => 'uploaded[file]|max_size[file,1024]|ext_in[file,csv],'
      // ]); var_dump($input); exit;

      // if (!$input) { // Not valid
      //    $data['validation'] = $this->validator; var_dump($data); exit;
var_dump($data); exit;
 session()->setFlashdata('message', 'File not imported.');
               session()->setFlashdata('alert-class', 'alert-danger');
         return view('import',$data); 
      }else{ // Valid

         if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {

               // Get random file name
               $newName = $file->getRandomName();

               // Store file in public/csvfile/ folder
               $file->move('../public/csvfile', $newName);

               // Reading file
               $file = fopen("../public/csvfile/".$newName,"r");
               $i = 0;
               $numberOfFields = 16; // Total number of fields

               $importData_arr = array();

               // Initialize $importData_arr Array
               while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                  $num = count($filedata);

                  // Skip first row & check number of fields
                  if($i > 3 && $num == $numberOfFields){ 
                     
                   
                     $importData_arr[$i]['store_no'] = $filedata[0];
                     $importData_arr[$i]['lot_no'] = $filedata[1];
                     $importData_arr[$i]['item_no'] = $filedata[2];
                     $importData_arr[$i]['storers_lot_no'] = $filedata[3];
                     $importData_arr[$i]['doc_no'] = $filedata[4];
                     $importData_arr[$i]['start_date'] = date('m/d/y', strtotime($filedata[5]));
                     $importData_arr[$i]['start_time'] = $filedata[6];
                     $importData_arr[$i]['complete_date'] = date('m/d/y', strtotime($filedata[7]));
                     $importData_arr[$i]['complete_time'] = $filedata[8];
                     $importData_arr[$i]['picker'] = $filedata[9];
                     $importData_arr[$i]['pallet'] = $filedata[10];
                     $importData_arr[$i]['doc_tyoe'] = $filedata[11];
                     $importData_arr[$i]['full_desc'] = $filedata[12];
                     $importData_arr[$i]['cust_name'] = $filedata[13];
                     $importData_arr[$i]['shipt_to_rec_from'] = $filedata[14];
                     $importData_arr[$i]['transaction_qty'] = $filedata[15];
                     $importData_arr[$i]['upload_by'] = 'sample'; 
                     if((str_contains($filedata[14], 'ASM')) || (str_contains($filedata[14], 'PALLET'))){
                         $importData_arr[$i]['new_type'] = 'pallet';
                     }else{
                         $importData_arr[$i]['new_type'] = 'case';
                     }

                  }

                  $i++;

               }
               fclose($file);
 
               // Insert data
               $count = 0;
               foreach($importData_arr as $userdata){
                  $users = new Users();

                  // Check record
                  $checkrecord = $users->where('id',$userdata['item_no'])->countAllResults();

                  if($checkrecord == 0){

                     ## Insert Record
                     if($users->insert($userdata)){
                         $count++;
                     }
                  }

               }

               // Set Session
               session()->setFlashdata('message', $count.' Record inserted successfully!');
               session()->setFlashdata('alert-class', 'alert-success');

            }else{
               // Set Session
               session()->setFlashdata('message', 'File not imported.');
               session()->setFlashdata('alert-class', 'alert-danger');
            }
         }else{
            // Set Session
            session()->setFlashdata('message', 'File not imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
         }

      }

      return redirect()->route('/admin'); 
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

    public function importXls (){
//var_dump(session()->get('email')); exit;
        $sDate = date('m/d/y', strtotime($this->request->getPost('data_date')));
        $is_overwrite = $this->request->getPost('Overwrite');
     $file_p = $this->upload($this->request->getFile('file'), 'file'); //var_dump($file_p); exit;
     $file_path = $file_p['uploaded_flleinfo'];
      // Reading file
               $file = fopen($file_path, "r"); //var_dump($file); exit;
               $i = 0;
               $numberOfFields = 17; // Total number of fields

               $importData_arr = array();

               // Initialize $importData_arr Array
               while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                  $num = count($filedata);
//var_dump($num); exit;
                  // Skip first row & check number of fields
                  if($i > 1 && $num == $numberOfFields){ 
                     
                     $date6 = date('Y-m-d', strtotime($filedata[6]));
                     $date8 = date('Y-m-d', strtotime($filedata[8]));
                     $time7 = date('H:i:s', strtotime($filedata[7]));
                     $time9 = date('H:i:s', strtotime($filedata[9]));

                         $importData_arr[$i]['store_no'] = $filedata[0];
                     $importData_arr[$i]['lot_no'] = $filedata[1];
                     $importData_arr[$i]['item_no'] = $filedata[2];
                     $importData_arr[$i]['storers_lot_no'] = $filedata[3];
                     $importData_arr[$i]['doc_no'] = $filedata[4];
                     $importData_arr[$i]['reason'] = $filedata[5];
                     $importData_arr[$i]['start_date'] =$date6;
                     $importData_arr[$i]['start_time'] = $time7;
                     $importData_arr[$i]['complete_date'] = $date8;
                     $importData_arr[$i]['complete_time'] = $time9;
                     $importData_arr[$i]['picker'] = $filedata[10];
                     $importData_arr[$i]['pallet'] = $filedata[11];
                     $importData_arr[$i]['doc_type'] = $filedata[12];
                     $importData_arr[$i]['full_desc'] = $filedata[13];
                     $importData_arr[$i]['cust_name'] = $filedata[14];
                     $importData_arr[$i]['ship_to_rec_from'] = $filedata[15];
                     $importData_arr[$i]['transaction_qty'] = $filedata[16];
                    $importData_arr[$i]['upload_by'] = session()->get('email');
                     if((str_contains($filedata[15], 'ASM')) || (str_contains($filedata[15], 'PALLET'))){
                         $importData_arr[$i]['new_type'] = 'pallet';
                     }else{
                         $importData_arr[$i]['new_type'] = 'case';
                     }


                  }

                  $i++;

               }
               fclose($file);
 //var_dump($importData_arr); exit;
               // Insert data
               $pick = new PickModel();  
                $checkrecord = $pick->where('complete_date',$sDate)->countAllResults();
                
                 $count = 0;
if(empty($is_overwrite)){
                 foreach($importData_arr as $userdata){
                     
                    if($pick->insert($userdata)){
                         $count++;
                     }                                  
                 }
                    session()->setFlashdata('message', $count.' records appended to '.$checkrecord.' records with similar date!');
                    session()->setFlashdata('alert_class', 'alert-warning');
}else{
     if($checkrecord == 0){
       
         foreach($importData_arr as $userdata){
                     
                    if($pick->insert($userdata)){
                         $count++;
                     }                                  
                 }
                    //if($num!=17){ session()->setFlashdata('message', 'Record not uploaded. Please check column count!'); }else{ session()->setFlashdata('message', $count.' Record inserted successfully!'); }
                 session()->setFlashdata('message', $count.' Record inserted successfully!');
                    session()->setFlashdata('alert_class', 'alert-warning');
    }else{
        $pick->where('complete_date',$sDate)->delete();
        foreach($importData_arr as $userdata){
                     
                    if($pick->insert($userdata)){
                         $count++;
                     }                                  
                 }
               session()->setFlashdata('message', $count.' Record inserted successfully and '.$checkrecord.' records overwritten!');
               session()->setFlashdata('alert_class', 'alert-success');
    }
}

         
               return redirect()->route('admin'); 
    }

}