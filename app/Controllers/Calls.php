<?php

namespace App\Controllers;

use App\Models\PersonModel;
use App\Models\ContentModel;
use CodeIgniter\I18n\Time;

class Calls extends BaseController
{
    public function paylocity()
    {
        $apiurl = 'https://api.paylocity.com/api/v2/companies/114216/employees/?pagesize=1000';
$cookie = '';
$people_array =[];
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiurl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(getenv('paylocityToken'),$cookie),
));

$response = curl_exec($curl);

curl_close($curl);
$response_decode = json_decode($response, true);
foreach($response_decode as $value) { //foreach element in $arr
  // echo $value['employeeId']." - ".$value['statusCode']."<br>";
   if($value['statusCode']=="A"){
       $curl1 = curl_init();

curl_setopt_array($curl1, array(
  CURLOPT_URL => 'https://api.paylocity.com/api/v2/companies/114216/employees/'.$value['employeeId'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(getenv('paylocityToken'),$cookie),
));
$response1 = curl_exec($curl1);
curl_close($curl1);
$response_decode1 = json_decode($response1, true);
  //echo $response_decode1['firstName']." ".$response_decode1['lastName']." (Supervisor:".$response_decode1['departmentPosition']['supervisorEmployeeId']. ")<br>";
$supervisor =!empty($response_decode1['departmentPosition']['supervisorEmployeeId']) ? $response_decode1['departmentPosition']['supervisorEmployeeId']: "";
$name_title = $response_decode1['firstName']." ".$response_decode1['lastName']." \n(".$response_decode1['departmentPosition']['jobTitle'].")";
$title = $response_decode1['departmentPosition']['jobTitle'];
$work_email = $response_decode1['workAddress']['emailAddress'];
$det= array('v'=>$value['employeeId'], 'f'=>$name_title);
$person = array($det, $supervisor, $title);
$person2 = array("head"=>$response_decode1['firstName']." ".$response_decode1['lastName'], "id"=>$value['employeeId'], "contents"=>$response_decode1['departmentPosition']['jobTitle']."<br>".$work_email);
$date = Time::parse($response_decode1['status']['hireDate'], 'America/Chicago');
$hiredate = $date->toLocalizedString('yyyy-MM-dd HH:MM:SS');
$insert_data = [
    'employee_id' => $value['employeeId'],
    'has_supervisor' =>!empty($supervisor) ? 1 : 0,
    'supervisor_id' =>!empty($supervisor) ? $supervisor : 0,
    'work_email' =>$work_email,
    'job_title' => $title,
    'first_name' => $response_decode1['firstName'],
    'last_name' => $response_decode1['lastName'],
    'chart_json' => json_encode($person2),
    'is_supervisor' => 0,
    'hire_date' => $hiredate
        
];
$insert_content = [
    'cat_id' => 3,
    'subcat_id' =>23,
    'external_id' =>$value['employeeId'],
    'content' =>$title." (".$work_email.")",
    'title' => $response_decode1['firstName']." ".$response_decode1['lastName'],
    'slug' => strtolower($response_decode1['lastName'])."-".$value['employeeId'],
    'status' => 'active',
    'is_searchable' => 1,
    'created_by' => 'paylocity@api',
    'updated_by' => 'paylocity@api',
	'content_type' => 'user'
];
//var_dump($insert_data); exit;
 $employee = new PersonModel();
 $employee->ignore(true)->insert($insert_data);
 if(!empty($employee->getInsertID())){
     $content = new ContentModel();
     $content->insert($insert_content);
 }

//var_dump($person); echo "<br>";
   }else{
      // echo $value['employeeId']." - ".$value['statusCode']."<br>";
//       $employee = new PersonModel();
//       $contents = new ContentModel();
//       $person = $employee->where('employee_id', $value['employeeId'])->first();      // var_dump($person); exit;
//       if(!empty($person['person_id'])){
//           $update_person = ['status'=>'inactive'];
//       $employee->update(['person_id'=>$person['person_id']], $update_person);
//       }
//       $contentd = $content->where('external_id', $value['employeeId'])->first();
//       if(!empty($contentd['content_id'])){
//       $update_content = ['status'=> 'inactive', 'is_searchable'=>0];
//       $content->update(['content_id'=>$contentd['content_id']], $update_content);
//       }
   }
//array_push($people_array, $person);
}
//var_dump(json_encode($people_array));
    }
    

    public function deactiveUsers()
    {
        $apiurl = 'https://api.paylocity.com/api/v2/companies/114216/employees/?pagesize=1000';
$cookie = '';
$people_array =[];
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiurl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(getenv('paylocityToken'),$cookie),
));

$response = curl_exec($curl);

curl_close($curl);
$response_decode = json_decode($response, true);
foreach($response_decode as $value) { //foreach element in $arr
   //echo $value['employeeId']." - ".$value['statusCode']."<br>";
   if($value['statusCode']=="T"){
    $people = new PersonModel();
    $person = $people->find($value['employeeId']);
    if(!empty($person['person_id'])){
        echo $person['first_name']." ".$person['last_name']."<br>";
        $update_data = ['status'=>'inactive']; var_dump($update_data);
        $people->update($value['employeeId'], $update_data);
        $content = new ContentModel();
        $update_data2 = ['status'=>'inactive', 'is_searchable'=>0];
        $where = ['external_id' => $value['employeeId']];
		$content->update($where, $update_data2);
    }
   }
}
//var_dump(json_encode($people_array));
    }
	
	

   public function updateOrgChart()
    {
        $people_array =[];
        $people = new PersonModel();
        $content = new ContentModel();

        $employees = $people->where('status', 'active')->orderBy('employee_id', 'ASC')->findAll();
        foreach($employees as $employee){
            $super_id = ($employee['supervisor_id']==0) ? "": $employee['supervisor_id'];
			$person = array(
                ['employee_id'=>$employee['employee_id'], 
				 'name'=> $employee['first_name']." ".$employee['last_name'],
                'supervisor_id' =>$super_id,
                'role' =>$employee['job_title']
				 ]
            );
			
        array_push($people_array, $person);
        }
    
        $where = ['content_id' => 6];
		$new_content = ['content'=>json_encode($people_array)];
		//$content->update($where, $new_content);
		echo json_encode($people_array);
    }
    

    
}
