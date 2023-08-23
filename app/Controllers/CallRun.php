<?php

namespace App\Controllers;

use App\Models\PersonModel;
use App\Models\ContentModel;
use CodeIgniter\I18n\Time;
use App\Libraries\Paylocity;

class CallRun extends BaseController
{
	
	public function paylocity()
    {
       
	$people_array =[];
	$paylocity = new Paylocity();
	$response_decode = $paylocity->getEmployees();
	foreach($response_decode as $value) { 
   		if($value['statusCode']=="A"){
			$response_decode1 = $paylocity->getEmployee($value['employeeId']);
			$supervisor =!empty($response_decode1['departmentPosition']['supervisorEmployeeId']) ? 											$response_decode1['departmentPosition']['supervisorEmployeeId']: "";
			$name_title = $response_decode1['firstName']." ".$response_decode1['lastName']." \n(".$response_decode1['departmentPosition']['jobTitle'].")";
			$title = $response_decode1['departmentPosition']['jobTitle'];
			$work_email = $response_decode1['workAddress']['emailAddress'];
			$det= array('v'=>$value['employeeId'], 'f'=>$name_title);
			$person = array($det, $supervisor, $title);
			$person2 = array("head"=>$response_decode1['firstName']." ".$response_decode1['lastName'], "id"=>$value['employeeId'], "contents"=>$response_decode1['departmentPosition']['jobTitle']."<br>".$work_email);
			$date = Time::parse($response_decode1['status']['hireDate'], 'America/Chicago');
			$hiredate = $date->toLocalizedString('yyyy-MM-dd HH:MM:SS');
			$people = new PersonModel();
    		$person = $people->find($value['employeeId']);
    		if(!empty($person['person_id'])){
        		if($person['supervisor_id']!=$supervisor){
					$update_data = ['supervisor_id'=>$supervisor]; 
        			$people->update($value['employeeId'], $update_data);
//echo $person['first_name']." ".$person['last_name']." || old super: ".$person['supervisor_id']." || new:".$supervisor." ==> ".$value['statusCode']."<br>";
						}
				if($person['job_title']!=$title){
					$update_data = ['job_title'=>$title]; 
        			$people->update($value['employeeId'], $update_data);
//echo $person['first_name']." ".$person['last_name']." || old super: ".$person['supervisor_id']." || new:".$supervisor." ==> ".$value['statusCode']."<br>";
						}
    	}else{
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
 			$people->ignore(true)->insert($insert_data);
 			if(!empty($people->getInsertID())){
     		$content = new ContentModel();
     			$content->insert($insert_content);
 			}		
				
			}
			
   			}	elseif($value['statusCode']=="T"){
    				$people = new PersonModel();
    				$person = $people->find($value['employeeId']);
    				if(!empty($person['person_id'])){
        				//echo $person['first_name']." ".$person['last_name']." --> ".$value['statusCode']."<br>";
        				$update_data = ['status'=>'inactive', 'first_name'=>'-', 'last_name'=>'-', 'work_email'=>'inactive@lawboss.com']; //var_dump($update_data);
        				$people->update($value['employeeId'], $update_data);
        				$content = new ContentModel();
        				$update_data2 = ['status'=>'inactive', 'is_searchable'=>0];
        				$where = ['external_id' => $value['employeeId']];
						$content->update($where, $update_data2);
    				}
   			}
		
		else{
      
   			}

		}
		$this->updateOrgChart();
    }
	
	
	
    public function paylocity_()
    {
       
	$people_array =[];
	$paylocity = new Paylocity();
	$response_decode = $paylocity->getEmployees();
	foreach($response_decode as $value) { 
   		if($value['statusCode']=="A"){
			$response_decode1 = $paylocity->getEmployee($value['employeeId']);
			$supervisor =!empty($response_decode1['departmentPosition']['supervisorEmployeeId']) ? 											$response_decode1['departmentPosition']['supervisorEmployeeId']: "";
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
 			$employee = new PersonModel();
 			$employee->ignore(true)->insert($insert_data);
 			if(!empty($employee->getInsertID())){
     		$content = new ContentModel();
     			$content->insert($insert_content);
 			}
			echo $response_decode1['firstName']." ".$response_decode1['lastName']." -- ".$value['statusCode']."<br>";
   			}	elseif($value['statusCode']=="T"){
    				$people = new PersonModel();
    				$person = $people->find($value['employeeId']);
    				if(!empty($person['person_id'])){
        				//echo $person['first_name']." ".$person['last_name']." --> ".$value['statusCode']."<br>";
        				$update_data = ['status'=>'inactive', 'first_name'=>'-', 'last_name'=>'-', 'work_email'=>'inactive@lawboss.com']; //var_dump($update_data);
        				$people->update($value['employeeId'], $update_data);
        				$content = new ContentModel();
        				$update_data2 = ['status'=>'inactive', 'is_searchable'=>0];
        				$where = ['external_id' => $value['employeeId']];
						$content->update($where, $update_data2);
    				}
   			}
		
		else{
      
   			}

		}
		$this->updateOrgChart();
    }
    
	

    public function updateOrgChart()
    {
        $people_array =[];
        $people = new PersonModel();
        $content = new ContentModel();

        $employees = $people->where('status', 'active')->findAll();
        foreach($employees as $employee){
            $super_id = ($employee['supervisor_id']==0) ? "": $employee['supervisor_id'];
			$person = array(
                ['v'=>$employee['employee_id'], 'f'=> $employee['first_name']." ".$employee['last_name']." \n(".$employee['job_title'].")"],
                $super_id,
                $employee['job_title']
            );
			
        array_push($people_array, $person);
        }
    
        $where = ['content_id' => 6];
		$new_content = ['content'=>json_encode($people_array)];
		$content->update($where, $new_content);
    }
    
	public function orgChart()
	{
		$people_array =[];
        $people = new PersonModel();
		$supervisors = $people->getSupervisors(); //var_dump($sp); exit;
        foreach($supervisors as $super){
            $child = $people->selectBySuper($super->employee_id);
			$child_array = [];
			foreach($child as $chi){
				$subch =[];
				if($people->isSupervisor($chi->employee_id)){
					$child1 = $people->selectBySuper($chi->employee_id);
					foreach($child1 as $chi1){
						$sc1 = array(
					'label'=>$chi1->job_title, 
					'name'=>$chi1->first_name." ".$chi1->last_name
				);
						array_push($subch, $sc1);
					}
				$sc = array(
					'label'=>$chi->job_title, 
					'name'=>$chi->first_name." ".$chi->last_name,
					'children' => $subch
				);
				array_push($child_array, $sc);
				}else{
				$sc = array(
					'label'=>$chi->job_title, 
					'name'=>$chi->first_name." ".$chi->last_name
				);
				array_push($child_array, $sc);
			}
			}
			$person = array(
                'label'=>$super->job_title, 
				 'name'=> $super->first_name." ".$super->last_name,
                'children' =>$child_array
            );
			
        array_push($people_array, $person);
        }
    
        var_dump(json_encode($people_array)); exit;
	}

     public function updateSuper()
    {
    $people = new PersonModel();
	$paylocity = new Paylocity();
	$response_decode = $paylocity->getEmployees(); //var_dump($response_decode['departmentPosition']['supervisorEmployeeId']); exit;
	foreach($response_decode as $value) { //var_dump($value['employeeId']); exit;
			$response_decode1 = $paylocity->getEmployee($value['employeeId']);
			$supervisor =!empty($response_decode1['departmentPosition']['supervisorEmployeeId']) ? 											$response_decode1['departmentPosition']['supervisorEmployeeId']: "";
		echo $value['employeeId']." || New:".$supervisor."<br>"; 
		//var_dump($supervisor); exit;
	//		$person = $people->where('employee_id', $value['employeeId'])->first(); 
		//if(!empty($person['person_id'])){
    	//			echo $person['work_email']." old: ".$person['supervisor_id']." || New:".$supervisor."<br>"; 
		//}
		}
	 }
	
		public function oooReport()
	{
		$content = new ContentModel();
        $employees = new PersonModel();
			$head = $content->where('content_id', 453)->first();
			$foot = $content->where('content_id', 454)->first();
        $data['page_title'] = "OOO For ".date('l, m/d/Y');
        $late = $employees->getOOOWFH('Late Arrival');
        $early = $employees->getOOOWFH('Leave Early');
        $ooo = $employees->getOOOWFH('OOO');
        $wfh = $employees->getOOOWFH('WFH');
        
			$email ="";
			$email .="<p>OOO For ".date('l, m/d/Y')."</p>";
			$email .=$head['content']."<br><br>";
			
			//ooo
			if(!empty($ooo)){
			$email .="<table cellspacing='0' border='1' width='100%'>
		   <tr>
			   <td colspan='4' align='center'>
				   <strong>OOO / PTO</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Returning on </strong></td>
			   <td><strong>Urgent Matters to</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>";
			foreach($ooo as $ooo_) {
			$email .="<tr>";
			$email .="<td>". $ooo_->first_name." ".$ooo_->last_name."</td>";
			$email .="<td>".date('m/d/Y', strtotime($ooo_->end))."</td>";
			$email .="<td>".$ooo_->report_to."</td>";
			$email .="<td>".$ooo_->note."</td>";
		    $email .="</tr>";
			}
			$email .="</table><br><br>";
			}
			
			//wfh
			if(!empty($wfh)){
			$email .="<table cellspacing='0' border='1' width='100%'>
		   <tr>
			   <td colspan='2' align='center'>
				   <strong>WFH</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>";
			foreach($wfh as $wfh_) {
			$email .="<tr>";
			$email .="<td>". $wfh_->first_name." ".$wfh_->last_name."</td>";
			$email .="<td>".$wfh_->note."</td>";
		    $email .="</tr>";
			}
			$email .="</table><br><br>";
			}
			
			//late
			if(!empty($late)){
			$email .="<table cellspacing='0' border='1' width='100%'>
		   <tr>
			   <td colspan='4' align='center'>
				   <strong>Late Arrivals</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Arriving At</strong></td>
			   <td><strong>Urgent Matters To</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>";
			foreach($late as $late_) {
			$email .="<tr>";
			$email .="<td>". $late_->first_name." ".$late_->last_name."</td>";
			$email .="<td>".date('h:i a', strtotime($late_->arrival))."</td>";
			$email .="<td>".$late_->report_to."</td>";
			$email .="<td>".$late_->note."</td>";
		    $email .="</tr>";
			}
			$email .="</table><br><br>";
			}
			
			//early
			if(!empty($early)){
			$email .="<table cellspacing='0' border='1' width='100%'>
		   <tr>
			   <td colspan='4' align='center'>
				   <strong>Early Departure</strong>
			   </td>
		   </tr>
		   <tr>
			   <td><strong>Employee</strong></td>
			   <td><strong>Leaving At</strong></td>
			   <td><strong>Urgent Matters To</strong></td>
			   <td><strong>Notes</strong></td>
		   </tr>";
			foreach($early as $early_) {
			$email .="<tr>";
			$email .="<td>". $early_->first_name." ".$early_->last_name."</td>";
			$email .="<td>".date('h:i a', strtotime($early_->arrival))."</td>";
			$email .="<td>".$early_->report_to."</td>";
			$email .="<td>".$early_->note."</td>";
		    $email .="</tr>";
			}
			$email .="</table><br><br>";
			}
			$email .=$foot['content']."<br><br>";
			
			echo $email;
	}
}
