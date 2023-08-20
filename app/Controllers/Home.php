<?php

namespace App\Controllers;

use App\Models\PersonModel;
use App\Models\ContentModel;
use App\Models\CategoriesModel;
use App\Models\SubcategoriesModel;
use App\Models\LinksModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
     public function index() {
        return view('import');
    }

   // File upload and Insert records
   public function importFile(){
var_dump('show');
      // Validation
      $input = $this->validate([
         'file' => 'uploaded[file]|max_size[file,1024]|ext_in[file,csv],'
      ]);

      if (!$input) { // Not valid
         $data['validation'] = $this->validator;

         return view('users/index',$data); 
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
                     
                     // Key names are the insert table field names - name, email, city, and status
                     $importData_arr[$i]['store_no'] = $filedata[0];
                     $importData_arr[$i]['lot_no'] = $filedata[1];
                     $importData_arr[$i]['item_no'] = $filedata[2];
                     $importData_arr[$i]['storers_lot_no'] = $filedata[3];
                     $importData_arr[$i]['doc_no'] = $filedata[4];
                     $importData_arr[$i]['start_date'] = $filedata[5];
                     $importData_arr[$i]['start_time'] = $filedata[6];
                     $importData_arr[$i]['complete_date'] = $filedata[7];
                     $importData_arr[$i]['complete_time'] = $filedata[8];
                     $importData_arr[$i]['picker'] = $filedata[9];
                     $importData_arr[$i]['pallet'] = $filedata[10];
                     $importData_arr[$i]['doc_tyoe'] = $filedata[11];
                     $importData_arr[$i]['full_desc'] = $filedata[12];
                     $importData_arr[$i]['cust_name'] = $filedata[13];
                     $importData_arr[$i]['shipt_to_rec_from'] = $filedata[14];
                     $importData_arr[$i]['transaction_qty'] = $filedata[15];

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

      return redirect()->route('/'); 
   }
    
}
