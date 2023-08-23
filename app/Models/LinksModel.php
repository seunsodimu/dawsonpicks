<?php namespace App\Models;

use CodeIgniter\Model;

class LinksModel extends Model
{
    protected $table = 'quicklinks';
    protected $primaryKey = 'link_id';
	protected $allowedFields = ['user', 'slug', 'title'];
	
	public function getLinks($user)
	{
			$builder = $this->db->table("content");
        $builder->select('content.content_id, content.slug, content.content, content.title, content.created_by, content.created_at, content.status, content.content_type, quicklinks.link_id');
        $builder->join('quicklinks', 'content.slug = quicklinks.slug');
        $builder->orderBy('quicklinks.created_date', 'DESC');
        $data = $builder->get()->getResult(); 
        return $data;
	}
}

