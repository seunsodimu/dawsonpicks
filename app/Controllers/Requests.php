<?php

namespace App\Controllers;

use App\Models\PersonModel;
use App\Models\ContentModel;
use App\Models\CategoriesModel;
use App\Models\SubcategoriesModel;
use CodeIgniter\I18n\Time;


class Requests extends BaseController
{

    public function index()
    {
        $data['page_title'] = "IT REQUESTS";
        $data['$logged_user'] = $this->session->get('username');
        return view('requests', $data);
    }
    
    public function newPost()
	{
		$new_id = $this->postToMonday($this->request->getPost());
		if(!empty($new_id['id'])){
			$status = "success";
			$msg = 'Your request was successfully posted!';
        if(!empty($this->request->getFile('uploadedFile')))
        {
            $filex = $this->upload($this->request->getFile('uploadedFile'));
            if(empty($filex['uploaded_flleinfo'])){
				$msg = "Your request was submitted, however your file could not be added. Please make sure the file size is under 250MB and is one of the following types: xls, xlsx, csv, doc, docx, jpg, jpeg, gif, png";
			}else{
			$file = base_url()."/render".str_replace('var/www/vhosts/my.lawboss.org/httpdocs/citadel/', '', $filex['uploaded_flleinfo']);
            $upl = $this->updateMondayWithFile($new_id['id'], $file);
			}
        } 
		}else{
			
			$status = "error";
			$msg = 'Your request could not be processed at the moment, please contact IT!';
		}
		
        $this->session->setFlashdata('responseStatus', $status);
        $this->session->setFlashdata('msg', $msg);  
        $redirect = base_url().'/it-request';
        return redirect()->to($redirect);
    }
    
    public function upload($file)
    { 
        $validationRule = [
            'uploadedFile' => [
                'label' => 'File',
                'rules' => 'uploaded[uploadedFile]'
                    . '|mime_in[uploadedFile,image/jpg,image/jpeg,image/gif,image/png,image/webp,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf]'
                    . '|max_size[uploadedFile,500]',
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
        return $data;
    }
    
    public function postToMonday($post)
    {
        $token = getenv('mondayToken');
        $apiUrl = getenv('mondayApiURL');
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
        $ram_id = '26269685';
        $amy_id = '28304544';
        $person = '26269685';
        if(($post['RequestType']=="Software") || ($post['Title']=="Hardware")){
            $person = $ram_id;
        }elseif($post['RequestType']=="Office Supply"){
        $person=$amy_id;
        }else{
            $person = "26269685"; 
        }

        $query = 'mutation ($myItemName: String!, $columnVals: JSON!) { create_item (board_id:2395646153, item_name:$myItemName, column_values:$columnVals) { id } }';
        $vars = ['myItemName' => $post['Title'], 
            'columnVals' => json_encode([
            'status' => ['label' => 'New'], 
            'date4' => ['date' => gmdate("Y-m-d"), 'time'=>gmdate("H:i:s")],
            'person' => ['personsAndTeams' => [['id' => $person, 'kind'=>'person']]],
            'text0'=> $this->session->get('username')." ".$this->session->get('userlast'),
            'request_type'=> $post['RequestType'],
            'text5'=> $post['RequestApplication'],
            'text4'=> $post['Description'],
            'dropdown'=> $post['Department']
        ])];

            $data = @file_get_contents($apiUrl, false, stream_context_create([
                'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => json_encode(['query' => $query, 'variables' => $vars]),
                        ]
                ]));
            $responseContent = json_decode($data);
            $response['id'] = !empty($responseContent->data->create_item->id) ? $responseContent->data->create_item->id:"";
            $response['data'] = $responseContent;
            
            return $response; 
    }
    
    public function updateMondayWithFile($post_id, $file) {
 
        $token = getenv('mondayToken');
        $apiUrl = getenv('mondayApiURL');
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
        $curl = curl_init();
        $fields = array('query' => 'mutation ($file: File!) { add_file_to_column (file: $file, item_id: '.$post_id.', column_id: "files") {  id  } }','variables[file]'=> new \CURLFILE($file));
        $header = array('Authorization: ' . $token);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.monday.com/v2/file',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => $header,
        ));

        $responseContent = curl_exec($curl);

        curl_close($curl);
        $responseContent = json_decode($responseContent);
        $response['id'] = !empty($responseContent->data->add_file_to_column->id) ? $responseContent->data->add_file_to_column->id:"";
        $response['data'] = $responseContent;
        return $response; 
} 
	
	
    
    public function postSuggestToMonday($post)
    {
        $token = getenv('mondayToken');
        $apiUrl = getenv('mondayApiURL');
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

        $query = 'mutation ($myItemName: String!, $columnVals: JSON!) { create_item (board_id:2701283680, item_name:$myItemName, column_values:$columnVals) { id } }';
        $vars = ['myItemName' => $post['Title'], 
            'columnVals' => json_encode([
            'status2' => ['label' => 'New'], 
            'long_text'=> $post['Description']
        ])];

            $data = @file_get_contents($apiUrl, false, stream_context_create([
                'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => json_encode(['query' => $query, 'variables' => $vars]),
                        ]
                ]));
            $responseContent = json_decode($data); //var_dump($responseContent); exit;
            $response['id'] = !empty($responseContent->data->create_item->id) ? $responseContent->data->create_item->id:"";
            $response['data'] = $responseContent;
            
            return $response; 
    }
	

    public function Suggest()
    {
        $data['page_title'] = "Suggestion Box";
        return view('suggest', $data);
    }
	
	public function newSuggest()
	{ //var_dump($this->request->getPost()); exit;
		$new_id = $this->postSuggestToMonday($this->request->getPost());
		if(!empty($new_id['id'])){
			$status = "success";
			$msg = 'Your suggestion was successfully posted!';
		}else{
			
			$status = "error";
			$msg = 'Your suggestion could not be processed at the moment, please try again later!';
		}
		
        $this->session->setFlashdata('responseStatus', $status);
        $this->session->setFlashdata('msg', $msg);  
        $redirect = base_url('suggest');
        return redirect()->to($redirect);
    }
}
