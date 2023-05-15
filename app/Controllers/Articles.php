<?php

namespace App\Controllers;

use App\Models\ContentModel;
use App\Models\CategoriesModel;
use App\Models\SubcategoriesModel;
use App\Models\PersonModel;
use App\Models\LinksModel;
use CodeIgniter\I18n\Time;

class Articles extends BaseController
{
    public function trainings($slug)
    {
        $content = new ContentModel();
        $categories = new CategoriesModel();
        $subcat = new SubcategoriesModel();
        $links = new LinksModel();
        $user = (!empty($session->user_email)) ? $this->session->get('username') : "system"; 
        $quicklinks = $links->where('user', $user)->findAll(5, 0);
        $data['quicklinks'] =$quicklinks;
        
        $subcat_ = $subcat->where('slug', $slug)->where('status', 'active')->first();
        $articles = $content->where('subcat_id', $subcat_['subcat_id'])->where('status', 'active')->orderBy('position', 'DESC')->orderBy('content_id', 'ASC')->findAll();  
       // var_dump($articles); exit;
        $data['page_title'] = $subcat_['title'];
        $data['articles'] = $articles;
        $data['subcat'] = $subcat_;
        $data['current'] ='Trainings';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('subcategories', $data);
    }
    
    public function training($slug)
    { 
        $content = new ContentModel();
        $categories = new CategoriesModel();
        $subcat = new SubcategoriesModel();
        $links = new LinksModel();
        $user = (!empty($session->user_email)) ? $session->user_email : "system"; 
        $article = $content->where('slug', $slug)->first();        //var_dump($article); exit;
        $subcat_ = $subcat->where('subcat_id', $article['subcat_id'])->first();
        $related = $content->where('subcat_id', $article['subcat_id'])->where('status', 'active')->findAll(5, 0);
        $quicklinks = $links->where('user', $user)->findAll(5, 0);

        $data['person'] = [];
        if($article['subcat_id']==23){
            $people = new PersonModel();
            $data['person'] = $people->where('employee_id', $article['external_id'])->first();
        }
        $data['page_title'] = $article['title'];
        $data['article'] = $article;
        $data['subcat'] = $subcat_;
        $data['current'] ='Trainings';
        $data['related'] =  $related;
        $data['quicklinks'] =$quicklinks;
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('trainings', $data);
        
    }
    
     public function orgchart()
    {
        $content = new ContentModel();
        $categories = new CategoriesModel();
        $subcat = new SubcategoriesModel();
        
        $article = $content->where('slug', 'org-chart')->first();
        $subcat_ = $subcat->where('subcat_id', $article['subcat_id'])->first();
        $data['page_title'] = $subcat_['title'];
        $data['article'] = $article;
        $data['current'] ='Directory';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('orgchart', $data);
    }
    
     public function chartdisplay()
    {
        $content = new ContentModel();
        $categories = new CategoriesModel();
        $subcat = new SubcategoriesModel();
        
        $article = $content->where('slug', 'org-chart')->first();
        $subcat_ = $subcat->where('subcat_id', $article['subcat_id'])->first();
        $data['page_title'] = $subcat_['title'];
        $data['article'] = $article;       // var_dump($data); exit;
        $data['current'] ='Directory';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('chartdisplay1', $data);
    }
    
     public function nectar()
    {
        $content = new ContentModel();
        $categories = new CategoriesModel();
        $subcat = new SubcategoriesModel();
        
        $article = $content->where('slug', 'nectar')->first();
        $subcat_ = $subcat->where('subcat_id', $article['subcat_id'])->first();
        $data['page_title'] = $subcat_['title'];
        $data['article'] = $article;
        $data['current'] ='Nectar';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('pages', $data);
    }
    
     public function jobs()
    {
        $content = new ContentModel();
        $categories = new CategoriesModel();
        $subcat = new SubcategoriesModel();
        $links = new LinksModel();
        $user = (!empty($session->user_email)) ? $session->user_email : "system"; 
        $quicklinks = $links->where('user', $user)->findAll(5, 0);
        $data['quicklinks'] =$quicklinks;
        
        $article = $content->where('slug', 'job-openings')->first();
        $subcat_ = $subcat->where('subcat_id', $article['subcat_id'])->first();
        $data['page_title'] = $subcat_['title'];
		 $data['jobs'] = $content->where('subcat_id', 6)->findAll();
        $data['article'] = $article;
        $data['current'] ='HR';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('pages', $data);
    }
    
    public function search(){
     //   var_dump( $this->request->getGet('s')); exit;
        $content = new ContentModel();
        //$articles = $content->where('is_searchable', 1)->like('content', $this->request->getGet('s'))->orLike('title', $this->request->getGet('s'))->findAll();       // var_dump(strip_tags($article['content'])); exit;
		$articles = $content->getSearch($this->request->getGet('s'));
        $links = new LinksModel();
        $user = (!empty($session->user_email)) ? $session->user_email : "seun.sodimu@lawboss.com"; 
        $quicklinks = $links->where('user', $user)->findAll(5, 0);
        $data['quicklinks'] =$quicklinks;
        $data['search_string'] = $this->request->getGet('s');
        $data['page_title'] = "Search";
        $data['articles'] = $articles;
        $data['current'] ='';
        $data['weather'] = $this->userLocation();
		$data['logged_user'] = $this->session->get('username');
        return view('search_results', $data);
        
    }
	
	

    public function addQuickLink(){
        $links = new LinksModel();
        $content = new ContentModel();
        
        $article = $content->where('slug', $this->request->getPost('articleSlug'))->first();
        $user = (!empty($this->session->get('email'))) ? $this->session->get('email') : "seun.sodimu@lawboss.com"; 
        $posdata = [
        'slug' => $this->request->getPost('articleSlug'),
        'user' => $user,
        'title' => $article['title']
        ]; 
        $links->insert($posdata);
        echo json_encode("Post added to quick link!");
         return null;

    }

	public function removeLink()
	{
	 $links = new LinksModel();
		$links->where('link_id', $this->request->getPost('linkid'))->delete();
					  echo json_encode("Quick Link removed!");
					  return null;
	}

   
}
