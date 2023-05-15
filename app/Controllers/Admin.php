<?php

namespace App\Controllers;

use App\Models\PersonModel;
use App\Models\ContentModel;
use App\Models\CategoriesModel;
use App\Models\SubcategoriesModel;
use CodeIgniter\I18n\Time;


class Admin extends BaseController
{
//	const $session = session();
	
    public function index()
    {
        $data =[];
        return view('new_post', $data);
    }
	
	public function newPost()
	{
        $data =[];
		$subcats = new SubcategoriesModel();
		$data['subcats'] = $subcats->where('status', 'active')->findAll();
		$data['page_title'] = "New Post";
		$data['$logged_user'] = $this->session->get('username');
        return view('new_post', $data);
		
	}
	
	public function createPost()
	{
		
		//var_dump($this->request->getFile('Attachment')); exit;
		$subcats = new SubcategoriesModel();
		$content = new ContentModel();
		$disp_img ="";
		$attachment = "";
		$imgName = $this->request->getFile('DisplayImage');
		if(!empty($imgName)){
			$img = $this->upload($this->request->getFile('DisplayImage'), 'DisplayImage');
			$disp_img = $img['uploaded_flleinfo'];
		}
		$attachName = $this->request->getFile('Attachment'); //var_dump($attachName); exit;
			$attach = $this->upload($this->request->getFile('Attachment'), 'Attachment');
		if(empty($attach['errors'])){
			$attachment = str_replace('/var/www/vhosts/my.lawboss.org/httpdocs/citadel/', '', $attach['uploaded_flleinfo']);
		}
		
		$user = (!empty($this->session->get('email'))) ? $this->session->get('email') : "System";
		$status = ($this->request->getPost('PostStatus')=="Draft")?"draft":"active";
		$searchable = ($this->request->getPost('PostStatus')=="Draft")? 0:1;
		$cat_id = $subcats->getCatIdFromSubcatId($this->request->getPost('Subcat'));
		$slug = $content->getSlug($this->request->getPost('PostTitle'));
		$posdata = [
			'created_by' => $user,
			'subcat_id' => $this->request->getPost('Subcat'),
			'cat_id' => $cat_id,
			'content' => $this->request->getPost('editor2'),
			'title' => $this->request->getPost('PostTitle'),
			'is_searchable' => $searchable,
			'status' =>$status,
			'display_img' => $disp_img,
			'slug' => $slug, 
			'content_type' => strtolower($this->request->getPost('ContentType')),
			'downloadable_file' => $attachment
		];
		$content->insert($posdata);
		//var_dump($posdata); exit;
		if(!empty($content->getInsertID())){
		$this->session->setFlashdata('responseStatus', 'success');
		$this->session->setFlashdata('newSlug', $slug);	
		}else{
		$this->session->setFlashdata('responseStatus', 'error');
		$this->session->setFlashdata('newSlug', $slug);		
		}
		
		$redirect = base_url().'/new_post';
		return redirect()->to($redirect);
	}
	
	public function updatePost()
	{
		
		//var_dump($this->request->getFile('DisplayImage')); exit;
		$subcats = new SubcategoriesModel();
		$content = new ContentModel();
		$disp_img =$this->request->getPost('olddp');
		$imgName = $this->request->getFile('DisplayImage');
		if(!empty($imgName->isValid())){
			$img = $this->upload($this->request->getFile('DisplayImage'), 'DisplayImage'); //var_dump($img); exit;
		$disp_img = str_replace('/var/www/vhosts/my.lawboss.org/httpdocs/citadel/', '', $img['uploaded_flleinfo']);
		}
		$user = (!empty($this->session->get('email'))) ? $this->session->get('email') : "System";
		$status = ($this->request->getPost('PostStatus')=="Draft")?"draft":"active";
		$searchable = ($this->request->getPost('PostStatus')=="Draft")? 0:1;
		$cat_id = $subcats->getCatIdFromSubcatId($this->request->getPost('Subcat'));
		//$slug = $content->$this->request->getPost('Slug');
		$posdata = [
			'last_update_by' => $user,
			'subcat_id' => $this->request->getPost('Subcat'),
			'cat_id' => $cat_id,
			'content' => $this->request->getPost('editor2'),
			'title' => $this->request->getPost('PostTitle'),
			'is_searchable' => $searchable,
			'status' =>$status,
			'display_img' => $disp_img,
			'content_type' => strtolower($this->request->getPost('ContentType'))
		]; //var_dump($posdata); exit;
		$where = ['content_id'=> $this->request->getPost('postId')];
		$updated = $content->update($where, $posdata);
		//var_dump($content->getLastQuery()); exit;
		if($updated){
		$this->session->setFlashdata('responseStatus', 'success');
		
		}else{
		$this->session->setFlashdata('responseStatus', 'error');
			
		}
		$redirect = base_url().'/post/'.$this->request->getPost('postId'); //var_dump($redirect); exit;
		return redirect()->to($redirect);
	}
	
	public function upload($file, $fieldname)
    { 
        $validationRule = [
            'uploadedFile' => [
                'label' => 'File',
                'rules' => 'uploaded['.$fieldname.']'
                    . '|mime_in['.$fieldname.',image/jpg,image/jpeg,image/gif,image/png,image/webp,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf]'
                    . '|max_size['.$fieldname.',25000]',
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
		//var_dump($data); exit;
        return $data;
    }
	
	public function uploadDoc($file, $fieldname)
    {
        $validationRule = [
            'DisplayImage' => [
                'label' => 'Image File',
                'rules' => 'uploaded['.$fieldname.']'
                    . '|is_image[DisplayImage]'
                    . '|mime_in[DisplayImage,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
                    . '|max_size[DisplayImage,100]'
                    . '|max_dims[DisplayImage,1024,768]',
            ],
        ];
        if (! $this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];
        }else{

        

        if (! $img->hasMoved()) {
            $filepath = WRITEPATH . 'uploads/' . $img->store();

            $data = ['uploaded_flleinfo' => $filepath];

            
        } else {
            $data = ['errors' => 'The file has already been moved.'];

            
        }
	}
		return $data;
    }
	
	public function manageUsers()
	{
		$people = new PersonModel(); 
		$data['users'] = $people->where('status', 'active')->findAll();
		$data['supervisors'] = $people->getSupervisors();
		$data['page_title'] = "All Employees";
		$data['$logged_user'] = $this->session->get('username');
		return view('users', $data);
	}


	public function user($id)
	{
		$person = new PersonModel();
		$data['person'] = $person->where('employee_id', $id)->first();
		$data['page_title'] = $data['person']['first_name']." ".$data['person']['last_name'];
		$data['awards'] = $person->getUserAwards($id);
		$data['supervisors'] = $person->getSupervisors();
		$data['$logged_user'] = $this->session->get('username');
		return view('user', $data);
	}

	
	public function updateUser()
	{
		
		//var_dump($this->request->getPost()); exit;
		
		$users = new PersonModel();
		$redirect = base_url().'/user/'.$this->request->getPost('userid');
		
		switch ($this->request->getPost('updateVal')) {
			case 'displayPic':
			//var_dump($this->request->getFile('DisplayImage')); exit;
			$imgName = $this->request->getFile('DisplayImage');//var_dump($imgName); exit;
				
			$img = $this->upload($this->request->getFile('DisplayImage'), 'DisplayImage');
			if($img){ // var_dump($img); exit;
			$update_data['profile_pic'] = str_replace('writable/uploads//var/www/vhosts/my.lawboss.org/httpdocs/citadel/', '', $img['readfilepath']);
				$where = ['employee_id' => $this->request->getPost('userid')];
			 	$users->update($where, $update_data);
			 	$this->session->setFlashdata('responseStatus', 'success');
		}else{
				$this->session->setFlashdata('responseStatus', 'error');
		}
				break;
			case 'aboutInfo':
				//var_dump($this->request->getPost()); exit;
				$update_data['about_info'] = $this->request->getPost('PersonalInfo'); //var_dump($update_data); exit;
				$where = ['employee_id' => $this->request->getPost('userid')];
			 	$users->update($where, $update_data);
			 	$this->session->setFlashdata('responseStatus', 'success');
				break;

			case 'awarduser':
				$update_data['award_type'] = $this->request->getPost('AwardType');
				$update_data['award_name'] = $this->request->getPost('Award');
				$update_data['employee_id'] = $this->request->getPost('userid');
				$update_data['award_text'] = $this->request->getPost('AwardText');
				$users->awardUser($update_data);
				$this->session->setFlashdata('responseStatus', 'success');
				break;
				
			case 'ooo':
				$update_data['type'] = 'OOO';
				$update_data['report_to'] = $this->request->getPost('reportTo');
				$update_data['employee_id'] = $this->request->getPost('userid');
				$update_data['start'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('StartDateTime')));
				$update_data['end'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('EndDateTime')));
				$update_data['created_by'] = $this->session->get('email');
				$update_data['note'] = $this->request->getPost('Note');
				$users->addLeave($update_data);
				$redirect = base_url().'/users';
				$this->session->setFlashdata('responseStatus', 'success');
				$this->session->setFlashdata('responseStatusMsg', 'OOO status set for user');
				break;
				
			case 'wfh':
				$update_data['type'] = 'WFH';
				$update_data['report_to'] = $this->request->getPost('reportTo');
				$update_data['employee_id'] = $this->request->getPost('userid');
				$update_data['start'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('StartDateTime')));
				$update_data['end'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('EndDateTime')));
				$update_data['created_by'] = $this->session->get('email');
				$update_data['note'] = $this->request->getPost('Note');
				$users->addLeave($update_data);
				$redirect = base_url().'/users';
				$this->session->setFlashdata('responseStatus', 'success');
				$this->session->setFlashdata('responseStatusMsg', 'WFH status set for user');
				break;
				
			case 'late':
				$update_data['type'] = $this->request->getPost('late_early');
				$update_data['report_to'] = $this->request->getPost('reportTo');
				$update_data['employee_id'] = $this->request->getPost('userid');
				$update_data['end'] = date('Y-m-d H:i:s', strtotime($this->request->getPost('StartDateTime')));
				$update_data['arrival'] = $this->request->getPost('EndDateTime');
				$update_data['created_by'] = $this->session->get('email');
				$update_data['note'] = $this->request->getPost('Note');
				$users->addLeave($update_data);
				$redirect = base_url().'/users';
				$this->session->setFlashdata('responseStatus', 'success');
				$this->session->setFlashdata('responseStatusMsg', $this->request->getPost('late_early').' status set for user');
				break;
			
			default:
				
				break;
		}

	
		 
		return redirect()->to($redirect);
	}
    

	public function allPosts()
	{
		$contents = new ContentModel();
		$data['posts'] = $contents->getPosts();
		$data['page_title'] = "All Posts";
		$data['$logged_user'] = $this->session->get('username');
		return view('allposts', $data);
	}
    

	public function post($id)
	{
		$contents = new ContentModel();
		$subcats = new SubcategoriesModel();
		$data['subcats'] = $subcats->where('status', 'active')->findAll();
		$post = $contents->getPost($id); //var_dump($post); exit;
		$data['post'] =$post[0];
		$data['page_title'] = $post[0]->title;
		$data['logged_user'] = $this->session->get('username');
		return view('post', $data);
	}
	
	public function pUpdate()
	{
		$apiurl = 'https://api.paylocity.com/api/v2/companies/114216/employees/?pagesize=1000';
$cookie = '';
$people_array =[];
$curl = curl_init();
$token = $this->request->getPost('tk');

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiurl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array($token,$cookie),
));

$response = curl_exec($curl);

curl_close($curl);
$response_decode = json_decode($response, true);
foreach($response_decode as $value) { 
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
  CURLOPT_HTTPHEADER => array($token,$cookie),
));
$response1 = curl_exec($curl1);
curl_close($curl1);
$response_decode1 = json_decode($response1, true);
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

 $employee = new PersonModel();
 $employee->ignore(true)->insert($insert_data);
 if(!empty($employee->getInsertID())){
     $content = new ContentModel();
     $content->insert($insert_content);
 }


   }else{
   
   }

}
echo json_encode("Completed!");
		
	}
	
	public function pUpdateDeact()
	{
		$apiurl = 'https://api.paylocity.com/api/v2/companies/114216/employees/?pagesize=1000';
$cookie = '';
$people_array =[];
$curl = curl_init();
$token = $this->request->getPost('tk');

curl_setopt_array($curl, array(
  CURLOPT_URL => $apiurl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array($token,$cookie),
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
echo json_encode("Completed!");
	}
	
	public function pUpdateOrgChart()
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
		echo json_encode("Completed!");
	}
	
	public function adView()
	{
		
		$data['page_title'] = "Paylocity Connect";
		$data['logged_user'] = $this->session->get('username');
		return view('adview', $data);
	}
	
	public function menuItems()
	{
        $content = new ContentModel();
		$data['content'] = $content->getMenuItems(); 
		$data['page_title'] = "Menu Items";
		$data['logged_user'] = $this->session->get('username');
		return view('menu_items', $data);
		
	}
	
	public function newMenuItem()
	{
		
		//var_dump($this->request->getFile('Attachment')); exit;
		$content = new ContentModel();
		$disp_img ="";
		$imgName = $this->request->getFile('DisplayImage');
		if(!empty($imgName)){
			$img = $this->upload($this->request->getFile('DisplayImage'), 'DisplayImage');
			$disp_img = $img['uploaded_flleinfo'];
		}
		
		$user = (!empty($this->session->get('email'))) ? $this->session->get('email') : "System";
		$status = "active";
		$searchable = 1;
		$subcat = ($this->request->getPost('MenuType')=="Site") ? 25 : 26;
		$slug = $this->request->getPost('Link');
		$title = $this->request->getPost('Title');
		$posdata = [
			'created_by' => $user,
			'subcat_id' => $subcat,
			'cat_id' => 8,
			'content' => "",
			'title' => $title,
			'is_searchable' => $searchable,
			'status' =>$status,
			'display_img' => str_replace("/var/www/vhosts/my.lawboss.org/httpdocs/citadel/", "", $disp_img),
			'slug' => $slug, 
			'content_type' => strtolower($this->request->getPost('MenuType'))
		];
		$content->insert($posdata);
		//var_dump($posdata); exit;
		if(!empty($content->getInsertID())){
		$this->session->setFlashdata('responseStatus', 'success');
		$this->session->setFlashdata('newSlug', $slug);	
		}else{
		$this->session->setFlashdata('responseStatus', 'error');
		$this->session->setFlashdata('newSlug', $slug);		
		}
		
		$redirect = base_url().'/menu-items';
		return redirect()->to($redirect);
	}
	
	public function addTOC()
	{
		$item = $this->request->getPost('tocItem');
		$link = $this->request->getPost('tocLink');
		$id = $this->request->getPost('postId');
		if(empty($item) || empty($link)){
			$msg = "Please enter values for name or/and link";
		}else{
		$contents = new ContentModel();
		$post = $contents->where('content_id', $id)->first(); 
			if(!empty($post['toc'])){
		$toc = json_decode($post['toc']); //var_dump($toc); exit;
			}else{
			$toc = [];	
			}
			$new_item = array('name'=> $item, 'link'=>urlencode($link), 'options'=>"");
			array_push($toc, $new_item);
			$ntoc = json_encode($toc);
			 $where = ['content_id' => $id];
			$new_content = ['toc'=>$ntoc];
			$contents->update($where, $new_content);
			$msg = "Table of contents updated!";
		}
		$redirect = base_url().'/post/'.$id; //var_dump($redirect); exit;
		return redirect()->to($redirect);
	}

}
