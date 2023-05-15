<?php

namespace App\Controllers;

use App\Models\ContentModel;
use App\Models\CategoriesModel;
use App\Models\SubcategoriesModel;
use App\Models\PersonModel;
use App\Models\LinksModel;
use CodeIgniter\I18n\Time;

class Users extends BaseController
{
    public function trainings()
    {
        $data = ['page_title'=>'Trainings'];
		$data['logged_user'] = $this->session->get('username');
        return view('trainings', $data);
    }
    
    public function directory()
    { 
        
        $employees = new PersonModel();
        $links = new LinksModel();
        $user = (!empty($session->user_email)) ? $session->user_email : ""; 
        $quicklinks = $links->where('user', $user)->findAll(5, 0);
        $data['quicklinks'] =$quicklinks;
        $data['page_title'] = "Personnel Directory";
        $data['users'] = $employees->where('status', 'active')->findAll();
        $data['current'] ='Directory';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('directory', $data);
        
    }
	
		public function leaveSchedule()
	{
		$content = new ContentModel();
        $employees = new PersonModel();
        $links = new LinksModel();
        $user = (!empty($session->user_email)) ? $session->user_email : ""; 
        $quicklinks = $links->where('user', $user)->findAll(5, 0);
			$data['head'] = $content->where('content_id', 453)->first();
			$data['foot'] = $content->where('content_id', 454)->first();
        $data['quicklinks'] =$quicklinks;
        $data['page_title'] = "OOO For ".date('l, m/d/Y');
        $data['late'] = $employees->getOOOWFH('Late Arrival');
        $data['early'] = $employees->getOOOWFH('Leave Early');
        $data['ooo'] = $employees->getOOOWFH('OOO');
        $data['wfh'] = $employees->getOOOWFH('WFH');
        $data['current'] ='Leave';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('leave', $data);
	}
    
    
    
}
