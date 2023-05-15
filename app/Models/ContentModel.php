<?php namespace App\Models;

use CodeIgniter\Model;

class ContentModel extends Model
{
    protected $table = 'content';
    protected $primaryKey = 'content_id';
    protected $allowedFields =[
        'subcat_id','cat_id','toc','postion','content','external_id','status','display_img','slug','title','created_by','last_update_by', 'is_searchable', 'content_type', 'downloadable_file'
    ];
    
	
	public function getSlug($title)
	{
		$f_title = strtolower($this->cleanString($title));
		$content = $this->where('slug', $f_title)->orderBy('content_id', 'DESC')->first();
		$slug = (!empty($content['slug'])) ? $content['slug']."-2" : $f_title;
		return $slug;
	}
	
	public function getPosts()
	{
		$not_in = array(23, 25, 26);
		$builder = $this->db->table("content");
        $builder->select('content.content_id, content.slug, content.title, content.created_by, content.created_at, content.status, content.content_type, content.display_img, subcategories.slug AS subcatslug, subcategories.title AS subcat_title');
        $builder->join('subcategories', 'content.subcat_id = subcategories.subcat_id');
		$builder->whereNotIn('content.subcat_id', $not_in);
        $builder->orderBy('content.created_at', 'DESC');
        $data = $builder->get()->getResult(); 
        return $data;
	}
	
	public function getPost($id)
	{
		$builder = $this->db->table("content");
        $builder->select('content.content_id, content.slug, content.title, content.created_by, content.created_at, content.status, content.content, content.content_type, content.display_img, content.toc, subcategories.slug AS subcatslug, subcategories.title AS subcat_title, subcategories.subcat_id');
        $builder->join('subcategories', 'content.subcat_id = subcategories.subcat_id');
        $builder->where('content.content_id', $id);
        $data = $builder->get()->getResult(); 
        return $data;
	}
	
	public function getSearch($string)
	{
		$builder = $this->db->table("content");
        $builder->select('content.content_id, content.like_count, content.slug, content.title, content.created_by, content.created_at, content.status, content.content, content.content_type, subcategories.slug AS subcatslug, subcategories.title AS subcat_title, subcategories.subcat_id');
        $builder->join('subcategories', 'content.subcat_id = subcategories.subcat_id AND content.is_searchable=1');
		$builder->where('content.is_searchable', 1)->like('content.content', $string)->orLike('content.title', $string); 
		
		$data = $builder->get()->getResult(); // var_dump($this->db->getLastQuery()); exit;
        return $data;
	}
	
	
	public function getMenuItems()
	{
		$builder = $this->db->table("content");
        $builder->select('*');
		$builder->where('cat_id', 8);
        $data = $builder->get()->getResult(); 
        return $data;
	}
	
	
	public function cleanString($string)
	{
		$string = str_replace(' ', '-', trim($string)); 
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); 

   return preg_replace('/-+/', '-', $string); 
	}
}

