<?php 

namespace App\Models;
use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
      'cust_name', 'cust_id'
    ];

    public function getCustomer($id = false)
    {
        if($id === false){
            return $this->findAll();
        } else {
            return $this->find($id);
        }   
    }


}