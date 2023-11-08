<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\PickModel;
use CodeIgniter\I18n\Time;
use Exception;


class TimeBlockController extends BaseController
{
    public $docType ='Pick';
    public $pickType = '';
    public $start_time = '00:00';
    public $end_time = '23:59';

    public function __construct()
    {

    }
    

    public function pickerTimeBlockPicks()
    {
        $pick = new PickModel();
        $interval = $this->request->getGet('intv');
        $start_date = $this->request->getGet('start');
        $end_date = $this->request->getGet('end');
        $customer_id = $this->request->getGet('customer_id');
        $customer = $this->getCustomerName($customer_id);

        //override default doctype and picktype if passed in url
        if($this->request->getGet('doctype') != null){
            $this->docType = $this->request->getGet('doctype');
        }
        if($this->request->getGet('picktype') != null){
            $this->pickType = $this->request->getGet('picktype');
        }
        $alldata = $pick->getAllDataBetweenDates($start_date, $end_date, $customer, $this->docType, $this->pickType); //var_dump($alldata); die();
        $pickers = $this->getDistinctPickersFromArray($alldata); //var_dump($pickers); die();
        $time_array = $this->getTimeArray($interval); //var_dump($time_array); die();
        $date_array = $this->getDateArray($start_date, $end_date);
        $table = "<table id='rowtbl3' class='table table-bordered table-striped table-hover'>";
        $table .= "<thead><tr><th>Picker</th>";
        foreach($date_array as $date){
            $table .= "<th>".$date['start']."</th>";
        }   
        $table .= "</tr></thead><tbody>";
        foreach($pickers as $picker){
            $table .= "<tr><td>".$picker."</td>";
            foreach($date_array as $date){
                $table .= "<td>".$this->getPickerPickHitsInTimeArray($alldata, $picker, $time_array, $date['start'])."</td>";
            }
            $table .= "</tr>";
        }
        $table .= "</tbody></table>";
        //echo $table;
        $data = [
            'table' => $table,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'interval' => $interval,
            'customer' => $customer,
            'docType' => $this->docType,
            'type' => ($this->pickType=='') ? 'Pallets & Cases' : $this->pickType,
            'title' => 'Picker Time Block Report',
            'layout' => '',
            'date' => date('m/d/Y', strtotime($start_date))." - ".date('m/d/Y', strtotime($end_date)),
            'links' => base_url('picker-time-block')."?start=".$start_date."&end=".$end_date."&customer_id=".$customer_id,
            'summary' => '',
            'description' => 'Report showing pick hits by picker in '.$interval.' minute time blocks.'
        ];
        return view('admin/display3', $data);
        
    }

    public function getDistinctPickersFromArray($array)
    {
        $pickers = [];
        foreach($array as $row){
            if(!in_array($row->picker, $pickers)){
                array_push($pickers, $row->picker);
            }
        }
        return $pickers;
    }

    public function getTimeArray($interval)
    {
        $time_array = [];
        $interval = $interval." minutes";
        $start = new \DateTime($this->start_time);
        $end = new \DateTime($this->end_time);
        $current = clone $start;
        while ($current <= $end) {
           $floor = $current->format("G:i");
           $ceil = $current->modify($interval);
           array_push($time_array, array('start'=>$floor, 'end'=>$ceil->format("G:i")));
            
        }
        return $time_array; 
    }

    public function getDateArray($start_date, $end_date)
    {
        $date_array = [];
        $start = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        $current = clone $start;
        while ($current <= $end) {
           $floor = $current->format("Y-m-d");
           $ceil = $current->modify('+1 day');
           array_push($date_array, array('start'=>$floor, 'end'=>$ceil->format("Y-m-d")));
            
        }
        return $date_array; 
    }

    public function getPickerPickHitsInTimeArray($array, $picker, $time_array, $date)
    {
        $hits = 0;
        foreach($array as $row){
            if($row->picker == $picker){
                foreach($time_array as $time){
                    if($row->start_time = $time['start'] && $row->complete_time <= $time['end'] && $row->complete_date == $date){
                        $hits++;
                    }
                }
            }
        }
        return $hits;
    }

    public function getCustomerName($id)
    {
        $cust = "";
        switch($id){
            case 1:
                $cust = "Speedo C/O Dawson Logistics";
                break;
            case 2:
                $cust ="Sprout Foods, Inc";
                break;
                default:
                    $cust = "Speedo C/O Dawson Logistics";
                    break;
        }
        return $cust;
    }

}