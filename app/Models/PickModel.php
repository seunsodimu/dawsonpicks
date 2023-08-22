<?php 

namespace App\Models;
use CodeIgniter\Model;

class PickModel extends Model
{
    protected $table = 'pick_upload_temp';
    protected $primaryKey = 'id';
    protected $allowedFields = [
      'store_no', 'lot_no', 'item_no', 'storers_lot_no', 'doc_no', 'start_date', 'start_time', 'complete_date', 'complete_time', 'picker', 'pallet', 'doc_type', 'reason', 'full_desc', 'cust_name', 'ship_to_rec_from', 'transaction_qty', 'upload_by', 'upload_date', 'new_type'
    ];


public function viewDisplay($sDate, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  { 
    //$sDate = date('m/d/y', strtotime($sDate));
    $type = strtolower($type);
    $builder = $this->db->table("pick_upload_temp");
    $builder->select(array('picker', 'complete_time'));
    $builder->where(array('complete_date'=>$sDate, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type));
    
    
    $data = $builder->get()->getResult();  //var_dump($this->db->getLastQuery()); exit;
        return $data;
  
  }

  public function distinctPickers()
  {
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('picker');
    $builder->distinct('picker');
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }


  public function distinctDates()
  {
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('complete_date');
    $builder->distinct('complete_date');
    $dates = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
    $data = [];
    foreach ($dates as $value) {
     $builder1 = $this->db->table('pick_upload_temp');
    $builder1->select('complete_time, picker, upload_by, upload_date');
    $builder1->where('complete_date', $value->complete_date);
    $builder1->orderBy('complete_time', 'DESC');
    $builder1->limit(1);
    $dat = $builder1->get()->getResult();
    $builder2 = $this->db->table('pick_upload_temp');
    $builder2->select('COUNT(id) AS count');
    $builder2->where('complete_date', $value->complete_date);
    $dat2 = $builder2->get()->getResult();
    $date = array('complete_date'=>$value->complete_date, 'last_pick_time'=>$dat[0]->complete_time, 'pick_by'=>$dat[0]->picker, 'pick_count'=>$dat2[0]->count, 'upload_date'=>$dat[0]->upload_date, 'upload_by'=>$dat[0]->upload_by);
    array_push($data, $date);
    }
        return $data;
  }

  public function distinctActivePickers($date, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  {
    //$date = date('m/d/y', strtotime($date));
    $type = strtolower($type);
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('picker');
    $builder->distinct('picker');
    $builder->where(array('complete_date'=>$date, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type));
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }

  public function countPicks($picker, $date, $start, $end, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  {
    //$date = date('m/d/y', strtotime($date));
    $type = strtolower($type);
    $end = new \DateTime($end);
    $end->modify('-1 second');
    $end = $end->format('H:i:s');
    $builder = $this->db->table('pick_upload_temp');
    if($cust=='Speedo C/O Dawson Logistics'){
      $builder->select('COUNT(picker) AS total');
    }else{
      $builder->select('SUM(ABS(transaction_qty)) AS total');
    }
    
    $builder->where(array('picker'=>$picker, 'complete_date'=>$date, 'complete_time>='=>$start, 'complete_time<='=>$end, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type));
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }

  public function totalPickerCountPerDay($picker, $date, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  {
    //$date = date('m/d/y', strtotime($date));
    $type = strtolower($type);
    $builder = $this->db->table('pick_upload_temp');
    if($cust=='Speedo C/O Dawson Logistics'){
      $builder->select('COUNT(picker) AS total');
    }else{
      $builder->select('SUM(ABS(transaction_qty)) AS total');
    }
    $builder->where(array('picker'=>$picker, 'complete_date'=>$date, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type));
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }

  public function totalCountPerInterval($date, $start, $end, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  {
    //$date = date('m/d/y', strtotime($date));
    $type = strtolower($type);
    $end = new \DateTime($end);
    $end->modify('-1 second'); $end = $end->format('H:i:s');
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('COUNT(picker) AS total');
    $builder->where(array('complete_date'=>$date, 'complete_time>='=>$start, 'complete_time<='=>$end, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type));
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }



  public function totalPickDay($date, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  {
    //$date = date('m/d/y', strtotime($date));
    $type = strtolower($type);
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('COUNT(picker) AS total');
    $builder->where(array('complete_date'=>$date, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type));
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }

 


public function countPickSelector($picker, $date1, $date2, $start, $end, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  { 
    //$start = date("h:i", strtotime($start. "+1 hour")); $end = date("h:i", strtotime($end. "+1 hour"));
    $type = strtolower($type);
    $end = new \DateTime($end);
    $end->modify('-1 second'); $end = $end->format('H:i:s');
    $end = date("h:i", strtotime($end. "-1 minute"));
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('COUNT(picker) AS total');
    $where = ($picker=="All Selectors") ? array('complete_date>='=>date('Y-m-d', strtotime($date1)), 'complete_date<='=>date('Y-m-d', strtotime($date2)), 'complete_time>='=>$start, 'complete_time<='=>$end, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type) : array('picker'=>$picker, 'complete_date>='=>date('Y-m-d', strtotime($date1)), 'complete_date<='=>date('Y-m-d', strtotime($date2)), 'complete_time>='=>$start, 'complete_time<='=>$end, 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type);
    $builder->where($where);
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
  //  var_dump($start); echo "<br>"; var_dump($end); echo "<br>"; var_dump($data); exit;
        return $data;
  }

  public function totalPickerCountPerPeriod($picker, $date1, $date2, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  {
    $type = strtolower($type);
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('COUNT(picker) AS total');
    $where = ($picker=="All Selectors") ? array('complete_date>='=>date('Y-m-d', strtotime($date1)), 'complete_date<='=>date('Y-m-d', strtotime($date2)), 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type) : array('picker'=>$picker, 'complete_date>='=>date('Y-m-d', strtotime($date1)), 'complete_date<='=>date('Y-m-d', strtotime($date2)), 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type);
    $builder->where($where);
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }

  public function totalPickSelectorPeriod($picker, $date1, $date2, $type, $docType, $cust='Speedo C/O Dawson Logistics')
  {
    //$date = date('m/d/y', strtotime($date));
    $type = strtolower($type);
    $builder = $this->db->table('pick_upload_temp');
    $builder->select('COUNT(picker) AS total');
    $where = ($picker=="All Selectors") ? array('complete_date>='=>date('Y-m-d', strtotime($date1)), 'complete_date<='=>date('Y-m-d', strtotime($date2)), 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type) : array('picker'=>$picker, 'complete_date>='=>date('Y-m-d', strtotime($date1)), 'complete_date<='=>date('Y-m-d', strtotime($date2)), 'doc_type'=>$docType, 'cust_name'=>$cust, 'new_type'=>$type);
    $builder->where($where);
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }

  public function removeDups()
  {
   $query = $this->db->query('select id, picker, complete_date, complete_time, count(*) as NumDuplicates from pick_upload group by picker, complete_date, complete_time having NumDuplicates > 1');
  $rows = $query->getResult();
  foreach ($rows as $value) {
    //echo $value->id.": ".$value->picker.":-> ".$value->NumDuplicates."<br>";
    $qry = "DELETE FROM pick_upload WHERE id!=".$value->id." AND picker ='".$value->picker."' AND complete_date ='".$value->complete_date."' AND complete_time= '".$value->complete_time."'";
    $this->db->query($qry);
  } 
  return true;
  }

  public function removeDay($day)
  {
    $start = $day." 00:00:00";
    $end = $day." 23:59:59";
    $where = array('upload_by'=>'scheduled', "complete_date"=>$day);
    $builder = $this->db->table('pick_upload_temp');
    $builder->where($where);
    $builder->delete(); //var_dump($this->db->getLastQuery()); exit;
    return true;
  }

  public function transactionSummary()
  {
   $query = $this->db->query("select complete_date as transaction_date, upload_by, count(complete_date) as count, DATE_FORMAT(upload_date,'%Y-%m-%d') as upload_date from pick_upload_temp group by complete_date, upload_by, DATE_FORMAT(upload_date,'%Y-%m-%d') order by complete_date ASC");
  $rows = $query->getResult();
  return $rows;
  }

  public function mailReportSettings()
  {
    $builder = $this->db->table('gen_set');
    $builder->select('*');
    $builder->where('name', 'MAIL_REPORT');
    $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }
  
  public function getData($startTime, $endTime, $date, $type, $docType, $cust) {
        $builder = $this->db->table('pick_upload_temp');
        $builder->select('picker, start_time, complete_time, COUNT(*) as count');
        $builder->where('complete_date >=', $date);
        $builder->where('start_time >=', $startTime);
        $builder->where('complete_time <=', $endTime);
        $builder->where('cust_name', $cust);
        $builder->where('new_type', $type);
        $builder->where('doc_type', $docType);
        $builder->groupBy('picker, start_time, complete_time');
        $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }
  
  public function getAllData($date, $type, $docType, $cust){ 
      $builder = $this->db->table('pick_upload_temp');
        $builder->select('picker, start_date, start_time, complete_date, complete_time, transaction_qty');
        $builder->where('start_date', $date);
        $builder->where('cust_name', $cust);
        if($type == 'Pick'){
            $builder->where('new_type', $type);
        }
        $builder->where('doc_type', $docType);
        $builder->orderBy('picker ASC, complete_time DESC');
       // $builder->limit(100);
        $data = $builder->get()->getResult(); //var_dump($this->db->getLastQuery()); exit;
        return $data;
  }
  

}