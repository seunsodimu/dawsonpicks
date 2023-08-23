<?php namespace App\Models;

use CodeIgniter\Model;

class SubcategoriesModel extends Model
{
    protected $table = 'subcategories';
    protected $primaryKey = 'subcat_id';
	
	public function getCatIdFromSubcatId($id)
	{
		$subcat = $this->where('subcat_id', $id)->first(); //var_dump($id); exit;
		return $subcat['cat_id'];
	}
}

