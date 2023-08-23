<?php namespace App\Models;

use CodeIgniter\Model;

class PersonModel extends Model
{
    protected $table = 'people';
    protected $primaryKey = 'employee_id';
    protected $allowedFields = [
        'employee_id',
        'is_supervisor',
        'has_supervisor',
        'supervisor_id',
        'work_email',
        'job_title',
        'first_name',
        'last_name',
        'chart_json',
        'hire_date',
        'status',
        'profile_pic',
        'about_info'
    ];


    public function getAwards($type, $limit)
    {
        $builder = $this->db->table("people");
        $builder->select('awards.award_type, awards.award_name, awards.award_text, awards.awardee, people.first_name, people.last_name, people.profile_pic, people.about_info, people.employee_id');
        $builder->join('awards', 'people.employee_id = awards.employee_id');
        $builder->where('awards.award_type', $type);
        $builder->orderBy('awards.award_id', 'DESC');
        $builder->limit($limit);
        $data = $builder->get()->getResult(); //$builder->findAll(0, $limit);
        return $data;
    }

    public function awardUser($data)
    {
        $builder = $this->db->table("awards");
        $builder->insert($data);

        return true;
    }


    public function getUserAwards($id)
    {
        $builder = $this->db->table("people");
        $builder->select('awards.award_type, awards.award_name, awards.award_text, people.first_name, people.last_name, people.profile_pic, people.about_info');
        $builder->join('awards', 'people.employee_id = awards.employee_id');
        $builder->where('awards.employee_id', $id);
        $builder->orderBy('awards.award_id', 'DESC');
        $data = $builder->get()->getResult(); //$builder->findAll(0, $limit);
        return $data;
    }
	
	public function getSupervisors()
	{
		$subQuery = $this->db->table('people')->select('distinct(supervisor_id)');
		$builder = $this->db->table("people");
        $builder->select('employee_id, first_name, last_name, job_title');
		$builder->whereIn('employee_id', $subQuery);
		$builder->where('status', 'active');
		$builder->orderBy('supervisor_id', 'ASC');
        $data = $builder->get()->getResult(); 
        return $data;
	}
	
	
	
	public function distinctSupers()
	{
		$builder = $this->db->table('people')->select('distinct(supervisor_id)');
        $data = $builder->get()->getResult(); 
        return $data;
	}
	
	public function isSupervisor($id)
	{
		$subQuery = $this->db->table('people')->select('distinct(supervisor_id)');
		$builder = $this->db->table("people");
		$builder->select('employee_id');
		$builder->whereIn('employee_id', $subQuery);
		$builder->where('employee_id', $id);
		$data = $builder->get()->getResult();
		if(empty($data)){
			$resp = false;
		}else{
		$resp = true;	
		}
		return $resp;
	}
	
	
	public function selectBySuper($id)
	{
		$builder = $this->db->table("people");
        $builder->select('employee_id, first_name, last_name, work_email, job_title');
        $builder->where('supervisor_id', $id);
        $data = $builder->get()->getResult();
        return $data;
	}
	
	public function addLeave($data)
	{
        $builder = $this->db->table("ooo_wfh");
        $builder->insert($data);

        return true;
		
	}
	
	public function getLeave($data)
	{
		$where = array('date(date_format(`ooo_wfh`.`end`, "%Y-%m-%d")) >='=>'date(NOW())', 'ooo_wfh.type'=>$data);
        $builder = $this->db->table("people");
        $builder->select('people.first_name, people.last_name, ooo_wfh.report_to, ooo_wfh.start, ooo_wfh.end, ooo_wfh.arrival, ooo_wfh.note');
        $builder->join('ooo_wfh', 'people.employee_id = ooo_wfh.employee_id');
        $builder->where($where);
        $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
		
	}
	
	
	public function getOOOWFH($data)
	{
		switch ($data) {
			case 'OOO':
			$tablex = "daily_ooo_view";
				break;
				
			case 'WFH':
			$tablex = "daily_wfh_view";
				break;
				
			case 'Late Arrival':
			$tablex = "daily_late_view";
				break;
				
			case 'Leave Early':
			$tablex = "daily_early_view";
				break;
				
				default:
				
				break;
		}
		//var_dump($data); exit;
        $builder = $this->db->table($tablex);
        $builder->select('*');
        $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
		
	}
}

