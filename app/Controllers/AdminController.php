<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\PickModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use Exception;


class AdminController extends BaseController
{
    public function __construct()
    {
        
    }
    public function index()
    { 
        $pick = new PickModel();
        $data['pickers'] = $pick->distinctPickers();
        $data['title'] = "";
        return view("admin/dashboard", $data);
    }


    public function viewPicksTable()
    { 
        $pick = new PickModel();
        $data['sDate'] = $this->request->getGet('displayDate');
        $data['fTime'] = date('H:i:s', strtotime($this->request->getGet('FromTIme')));
        $data['tTime'] = date('H:i:s', strtotime( $this->request->getGet('ToTIme'))); //var_dump($data['fTime']." - ".$data['tTime']); exit;
        $data['interval'] = $this->request->getGet('Interval');
        $data['docType'] = $this->request->getGet('docType');
        $type = $this->request->getGet('Type');
        $cust = $this->getCustomerName($this->request->getGet('cust'));
        $data['type'] = $this->request->getGet('Type');
        $data['picks'] = $pick->viewDisplay($data['sDate'], $type, $data['docType']);
        $data['pickers'] = $pick->distinctActivePickers($data['sDate'], $type, $data['docType']);
        $int_avg = $data['interval'] / 60;
//set values in session
        session()->set('displayDate', date('Y-m-d', strtotime($this->request->getGet('displayDate'))));
        session()->set('FromTIme', date('H:i', strtotime($this->request->getGet('FromTIme'))));
        session()->set('ToTIme', date('H:i', strtotime($this->request->getGet('ToTIme'))));
        session()->set('Interval', $this->request->getGet('Interval'));
        session()->set('docType', $this->request->getGet('docType'));
        session()->set('Type', $this->request->getGet('Type'));
        
        $display ="";
        $display .="<table class='table table-striped table-bordered' width='30%'><tr><td style='width: 2%'><strong>Selector</strong><br><hr>";

        foreach($data['pickers'] as $item1){
    
            $display .= $item1->picker."<hr>";
           
        }
         $display .= "Total";
        $intv = ($data['interval']==60) ?  "+ 60 minutes": "+".$data['interval']." minutes";
        $start = new \DateTime($data['fTime']);
        $end = new \DateTime($data['tTime']);
        $current = clone $start;
        while ($current <= $end) {
           $floor = $current->format("G:i");
           $ceil = $current->modify($intv);
           $display .= "<td align='center'><strong>";
           $display .= $floor;
			//$display .= $floor." - ".$ceil->format("G:i");
            $display .= "</strong><hr>";
            $time_worked = 0;
            foreach($data['pickers'] as $item){
            $pick_count = $pick->countPicks($item->picker, $data['sDate'], $floor, $ceil->format("G:i"), $type, $data['docType']);
            $display .=($pick_count[0]->total==0) ?  "<span class='num-count zerocount'>".$pick_count[0]->total."</span>" : "<span class='num-count'>".$pick_count[0]->total."</span>";
            $display .= "<hr>";
            }

        $int_count = $pick->totalCountPerInterval($data['sDate'], $floor, $ceil->format("G:i"), $type, $data['docType']);
        $display .=($int_count[0]->total==0) ? "<span class='num-count zerocount'>".$int_count[0]->total."</span>" : "<span class='num-count'>".$int_count[0]->total."</span>";
        $display .= "<br><br></td>";
            
        }
        $display .= "<td align='center'>Total<hr>";
        foreach($data['pickers'] as $item2){
            $pick_count_day = $pick->totalPickerCountPerDay($item2->picker, $data['sDate'], $type, $data['docType']);
            $display .= ($pick_count_day[0]->total==0) ?  "<span class='num-count zerocount'>".$pick_count_day[0]->total."</span>" :   "<span class='num-count'>".$pick_count_day[0]->total."</span>";
            $display .= "<hr>";
            }
            $pick_full_day = $pick->totalPickDay($data['sDate'], $type, $data['docType']);
            $display .= ($pick_full_day[0]->total==0) ?  "<span class='num-count zerocount'>".$pick_full_day[0]->total."</span>" :   "<span class='num-count'>".$pick_full_day[0]->total."</span>";
            $display .= "</td>";
        $display .="</tr></table>";

        $display1 = "";
        $total_hrs=0;
        $total_avg =0;
        $display1 .="<table id='rowtbl' class='table table-striped table-bordered' width='50%'><thead><tr><th>Selector</th><th>Avg/hr</th><th>Hours</th><th>Total Picks</th></tr></thead><tbody>";
        foreach($data['pickers'] as $item3){
            $display1 .="<tr>";
            $display1 .="<td>".$item3->picker."</td>";
            $res = $this->pickerHoursWorked($item3->picker, $data['interval'], $data['fTime'], $data['tTime'], $data['sDate'], $type, $data['docType'], $cust);
            $display1 .="<td>".$res['avg_hr']."</td>";
            $display1 .="<td>".$res['hours']."</td>";
            $display1 .="<td>".$res['total']."</td>";
            $display1 .="</tr>";
        }
        $display1 .="</tbody><tfoot><tr><th>Totals</th><th></th><th></th><th></th></tr></tfoot></table>";
        $data['display']= $display;
        $data['display1']= $display1;
        $data['title'] = "Selectors";
        return view("admin/display1", $data);
    }

    public function pickerHoursWorked($picker, $intvl, $start, $end, $date, $type, $docType, $cust="Speedo C/O Dawson Logistics")
    {
        $pick = new PickModel();
        $intvl_to_hour = 60 /$intvl; 
        $time_worked = 0;
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $current = clone $start;
         while ($current <= $end) { 
            $floor = $current->format("G:i");
            $ceil = $current->modify("+".$intvl." minutes");
            $pick_count = $pick->countPicks($picker, $date, $floor, $ceil->format("G:i"), $type, $docType, $cust);
            if($pick_count[0]->total >=1 ){
                $time_worked++;
            }
         }
         $pickDay = $pick->totalPickerCountPerDay($picker, $date, $type, $docType, $cust); //var_dump($pickDay); exit;
		$total =    $pick->totalPickerCountPerDay($picker, $date, $type, $docType, $cust);
         $reponse['hours'] = round($time_worked / $intvl_to_hour, 1); //var_dump($intvl_to_hour); exit;
         $reponse['avg_hr'] = ($reponse['hours']!=0) ? round(($pickDay[0]->total / $reponse['hours']), 1): 0;
         $reponse['total'] = $total[0]->total;

         return $reponse;
    }

   public function viewPicks()
    {// 
        
        $pick = new PickModel();
        $data['sDate'] = $this->request->getGet('displayDate');
        $data['fTime'] = date('H:i:s', strtotime($this->request->getGet('FromTIme')));
        $data['tTime'] = date('H:i:s', strtotime( $this->request->getGet('ToTIme'))); //var_dump($data['fTime']." - ".$data['tTime']); exit;
        $data['interval'] = $this->request->getGet('Interval');
        $data['docType'] = $this->request->getGet('docType');
        $type = $this->request->getGet('Type');
        $cust = $this->getCustomerName($this->request->getGet('cust'));
        $data['type'] = $this->request->getGet('Type');
        $data['picks'] = $pick->viewDisplay($data['sDate'], $type, $data['docType']);
        $data['pickers'] = $pick->distinctActivePickers($data['sDate'], $type, $data['docType']);
        $int_avg = $data['interval'] / 60;
        $data['dataTime'] = $this->request->getGet('dataTime');
        $data['localTime'] = $this->request->getGet('localTime');
//set values in session
        session()->set('displayDate', date('Y-m-d', strtotime($this->request->getGet('displayDate'))));
        session()->set('FromTIme', date('H:i', strtotime($this->request->getGet('FromTIme'))));
        session()->set('ToTIme', date('H:i', strtotime($this->request->getGet('ToTIme'))));
        session()->set('Interval', $this->request->getGet('Interval'));
        session()->set('docType', $this->request->getGet('docType'));
        session()->set('Type', $this->request->getGet('Type'));
        
        $table_head = [];
        $table_body = [];
        $table_footer = [];

        array_push($table_head, array('start'=>'Selector', 'end'=>''));

        

        //fill up intervals
        $intv = ($data['interval']==60) ?  "+ 60 minutes": "+".$data['interval']." minutes";
        $start = new \DateTime($data['fTime'], new \DateTimeZone($data['dataTime']));
        $end = new \DateTime($data['tTime'], new \DateTimeZone($data['dataTime']));
        $current = clone $start;
        while ($current <= $end) {
           $floor = $current->format("G:i");
           $ceil = $current->modify($intv);
           array_push($table_head, array('start'=>$floor, 'end'=>$ceil->format("G:i")));
            
        }
        array_push($table_head, array('start'=>'Total', 'end'=>''));
        array_push($table_footer, 'Total');
        
         //var_dump($table_head); exit;
        
        foreach($data['pickers'] as $item){
        $user = array('picker'=>$item->picker);
            $user['pick_counts'] = [];
            foreach($table_head as $times){
               if($times['end']!="")
               {
                $timestart = new \DateTime($times['start'], new \DateTimeZone($data['localTime']));
                $timestart->setTimeZone(new \DateTimeZone($data['dataTime']));
                $timeend = new \DateTime($times['end'], new \DateTimeZone($data['localTime']));
                $timeend->setTimeZone(new \DateTimeZone($data['dataTime']));
                $pick_count = $pick->countPicks($item->picker, $data['sDate'], $timestart->format("G:i"), $timeend->format("G:i"), $type, $data['docType']);
                array_push($user['pick_counts'], $pick_count[0]->total);
               }
            }
            $pick_count_day = $pick->totalPickerCountPerDay($item->picker, $data['sDate'], $type, $data['docType']);
            array_push($user['pick_counts'], $pick_count_day[0]->total);
            array_push($table_body, $user);
        }
        // var_dump($table_body); exit;
        
        foreach($table_head as $times){
               if($times['end']!="")
               {
                $timestart = new \DateTime($times['start'], new \DateTimeZone($data['localTime']));
                $timestart->setTimeZone(new \DateTimeZone($data['dataTime']));
                $timeend = new \DateTime($times['end'], new \DateTimeZone($data['localTime']));
                $timeend->setTimeZone(new \DateTimeZone($data['dataTime']));
                $int_count = $pick->totalCountPerInterval($data['sDate'], $timestart->format("G:i"), $timeend->format("G:i"), $type, $data['docType']);
                array_push($table_footer, $int_count[0]->total);   
               }
        }
         $pick_full_day = $pick->totalPickDay($data['sDate'], $type, $data['docType']);
        array_push($table_footer, $pick_full_day[0]->total);
        


        $display1 = "";
        $total_hrs=0;
        $total_avg =0;
        $display1 .="<table id='rowtbl' class='table table-striped table-bordered' width='50%'><thead><tr><th>Selector</th><th>Avg/hr</th><th>Hours</th><th>Total Picks</th></tr></thead><tbody>";
        $tot_hr = 0;
        $tot_pic = 0;
        foreach($data['pickers'] as $item3){
            $display1 .="<tr>";
            $display1 .="<td>".$item3->picker."</td>";
            $res = $this->pickerHoursWorked($item3->picker, $data['interval'], $data['fTime'], $data['tTime'], $data['sDate'], $type, $data['docType'], $cust);
            $display1 .="<td>".$res['avg_hr']."</td>";
            $display1 .="<td>".$res['hours']."</td>";
            $tot_hr = $tot_hr + $res['hours'];
            $tot_pic = $tot_pic + $res['total'];
            $display1 .="<td>".$res['total']."</td>";
            $display1 .="</tr>";
        }
        if(($tot_pic>0) && ($tot_hr > 0)){
        $avg_per_hr = $tot_pic / $tot_hr;
        $avg_per_hr = round($avg_per_hr, 2);
    }else{
        $avg_per_hr =0;
    }
        $display1 .="</tbody><tfoot><tr><th>Totals</th><th>".$avg_per_hr."</th><th>".$tot_hr."</th><th>".$tot_pic."</th></tr></tfoot></table>";
        $data['table_head'] = $table_head;
        $data['table_body'] = $table_body;
        $data['table_footer'] = $table_footer;
        $data['display1']= $display1;
        $data['title'] = "Selectors";
        return view("admin/display1", $data);
    }

    public function pickSelectorByDate()
    {

        $pick = new PickModel();
        $data['pickselector'] = $this->request->getGet('pickSelector');
        $data['fDate'] = $this->request->getGet('fromDate');
        //$data['tDate'] = $this->request->getGet('toDate');
        $data['reportType'] = $this->request->getGet('reportType');
        $data['tDate'] = ($data['reportType']=="Week") ? date('Y-m-d', strtotime($this->request->getGet('fromDate'). "+6days")) : date('Y-m-d');
        $data['fTime'] = date('H:i:s', strtotime($this->request->getGet('FromTIme')));
        $data['tTime'] = date('H:i:s', strtotime( $this->request->getGet('ToTIme'))); 
        $data['interval'] = $this->request->getGet('Interval');
        $data['docType'] = $this->request->getGet('docType');
        $data['type'] = $this->request->getGet('Type');

        $int_avg = $data['interval'] / 60;

        //set values in session
        if($data['reportType']=='Week') {
        session()->set('FromDate', date('Y-m-d', strtotime($this->request->getGet('fromDate'))));
        session()->set('ToDate', date('Y-m-d', strtotime($this->request->getGet('fromDate'). "+6days")));
    }
        session()->set('PickSelector', $this->request->getGet('pickSelector'));
        session()->set('FromTIme', date('H:i', strtotime($this->request->getGet('FromTIme'))));
        session()->set('ToTIme', date('H:i', strtotime($this->request->getGet('ToTIme'))));
        session()->set('Interval', $this->request->getGet('Interval'));
        session()->set('docType', $this->request->getGet('docType'));
        session()->set('Type', $this->request->getGet('Type'));
        
        $table_head = [];
        $table_body = [];
        $table_footer = [];

        array_push($table_head, array('start'=>$data['reportType'], 'end'=>''));

        

        //fill up intervals
        $intv = ($data['interval']==60) ?  "+ 60 minutes": "+".$data['interval']." minutes";
        $start = new \DateTime($data['fTime'], new \DateTimeZone('America/Chicago'));
        $end = new \DateTime($data['tTime'], new \DateTimeZone('America/Chicago'));
        $current = clone $start;
        while ($current <= $end) {
           $floor = $current->format("G:i");
           $ceil = $current->modify($intv);
           array_push($table_head, array('start'=>$floor, 'end'=>$ceil->format("G:i")));
            
        }
        array_push($table_head, array('start'=>'Total', 'end'=>''));


        array_push($table_footer, 'Total');
        
         //var_dump($data); exit;
        
        $weekMonths = ($data['reportType']=='Week') ? $this->getDatesBetweenDays($data['fDate'], 7) : $this->getMonthsBetweenDates($data['fDate'], $data['tDate']);
//var_dump($weekMonths); exit;
        foreach($weekMonths as $weekMonth)
        {   
            $first_col = ($data['reportType']=='Week') ? date("m/d/Y l", strtotime($weekMonth)) : date('F Y', $weekMonth);
            $row_start_date = ($data['reportType']=='Week') ? date('Y-m-d', strtotime($weekMonth)): date('Y-m-d', $weekMonth);
            $row_end_date = ($data['reportType']=='Week') ? date('Y-m-d', strtotime($weekMonth)): date('Y-m-t', $weekMonth);
            $rows = array('first_col'=>$first_col);
            $rows['pick_counts'] = [];
            $totl =0;
            foreach($table_head as $times){
              
               if($times['end']!="")
               {
                $timestart = new \DateTime($times['start'], new \DateTimeZone('America/Chicago'));
                $timestart->setTimeZone(new \DateTimeZone('America/Denver'));
                $timeend = new \DateTime($times['end'], new \DateTimeZone('America/Chicago'));
                $timeend->setTimeZone(new \DateTimeZone('America/Denver'));
                 $pick_count = $pick->countPickSelector($data['pickselector'], $row_start_date, $row_end_date, $times['start'], $times['end'], $data['type'], $data['docType']);
            
                   array_push($rows['pick_counts'], $pick_count[0]->total);
                   $totl = $totl + $pick_count[0]->total;
               }
            }
            $pick_count_day = $pick->totalPickerCountPerPeriod($data['pickselector'], $row_start_date, $row_end_date, $data['type'], $data['docType']);
           // array_push($rows['pick_counts'], $pick_count_day[0]->total);
            array_push($rows['pick_counts'], $totl);
            //echo $row_start_date ." -  ". $row_end_date."</br>";
            array_push($table_body, $rows);
        }
        
      
        $totl2 = 0;
        foreach($table_head as $times){
               if($times['end']!="")
               {
                 $int_count =$pick->countPickSelector($data['pickselector'], $data['fDate'], $data['tDate'], $times['start'], $times['end'], $data['type'], $data['docType']);
         array_push($table_footer, $int_count[0]->total);   
         $totl2 = $totl2 + $int_count[0]->total;
               }
        }
         $pick_full_day = $pick->totalPickSelectorPeriod($data['pickselector'], $data['fDate'], $data['tDate'], $data['type'], $data['docType']);
       // array_push($table_footer, $pick_full_day[0]->total);
        array_push($table_footer, $totl2);


        $data['table_head'] = $table_head;
        $data['table_body'] = $table_body;
        $data['table_footer'] = $table_footer;
        $data['title'] = $data['pickselector']."'s ".str_replace('s', 'ly', $data['reportType']);
        return view("admin/display2", $data);
    }



    public function getBetweenTimes($startTime, $endTime, $int)
    {
        $time_array = [];
        $intv = $int." minutes";
        $start = new \DateTime($startTime);
        $end = new \DateTime($endTime);
        $current = clone $start;
        while ($current <= $end) {
           $floor = $current->format("G:i");
           $ceil = $current->modify($intv);
           array_push($time_array, array('start'=>$floor, 'end'=>$ceil->format("G:i")));
            
        }
        return $time_array; 
    }

    public function getWeeksBetweenDates($start_date, $end_Date)
    {

$startTime = strtotime($start_date);
$endTime = strtotime($end_Date);

$weeks = array();
$date = new \DateTime();
$i=0;
while ($startTime < $endTime) {  
    $weeks[$i]['week'] = date('W', $startTime);
    $weeks[$i]['year'] = date('Y', $startTime); 
    $date->setISODate($weeks[$i]['year'], $weeks[$i]['week']);
    $weeks[$i]['Monday']=$date->format('Y-m-d'); 
    $weeks[$i]['Sunday'] = date('Y-m-d',strtotime($weeks[$i]['Monday'] . "+6 days"));
    $startTime += strtotime('+1 week', 0);
    $i++;
}
return $weeks;
    }

     public function getMonthsBetweenDates( $start, $end ){
$start = strtotime($start);
$end = strtotime($end);
            $current = $start;
            $months = array();

            while( $current<$end ){
                
                $next = @date('Y-M-01', $current) . "+1 month";
                $current = @strtotime($next);
                $months[] = $current;
            }

            return $months;
        }

        
        public function uploadSummary2()
        {
            $pick = new PickModel();
            $data['summary'] = $pick->distinctDates();
            $data['title'] = "Upload Summary";
             return view("admin/summary2", $data);
}

public function getDatesBetweenDays($date, $days) 
{
    $days = "+ ".$days." day"; 
    $end = date('Y-m-d', strtotime($date. $days));
    $dates = []; //var_dump($end); exit;
    $period = new \DatePeriod(
     new \DateTime($date),
     new \DateInterval('P1D'),
     new \DateTime($end)
);

//Which should get you an array with DateTime objects. 

//To iterate

foreach ($period as $key => $value) {
    $dates[]=$value->format('Y-m-d');       
}
return $dates;
}

public function testDups()
{
    $ne = new PickModel();
    $nt = $ne->removeDups();
    var_dump($nt); exit;
}


        
        public function uploadSummary()
        {
            $pick = new PickModel();
            $data['summary'] = $pick->transactionSummary();
            $data['title'] = "Upload Summary";
             return view("admin/summary", $data);
}


public function dispPicks($displayDate, $FromTIme, $ToTIme, $Interval, $docType, $Type, $dataTime, $localTime, $cust="Speedo C/O Dawson Logistics")
{   
   $pick = new PickModel();
    $data['sDate'] = $displayDate;
    $data['fTime'] = date('H:i:s', strtotime($FromTIme));
    $data['tTime'] = date('H:i:s', strtotime($ToTIme)); //var_dump($data['fTime']." - ".$data['tTime']); exit;
    $data['interval'] = $Interval;
    $data['docType'] = $docType;
    $type = $Type;
    $data['type'] = $Type;
    $data['picks'] = $pick->viewDisplay($data['sDate'], $type, $data['docType'], $cust);
    $data['pickers'] = $pick->distinctActivePickers($data['sDate'], $type, $data['docType'], $cust);
    $int_avg = $data['interval'] / 60;
    $data['dataTime'] = $dataTime;
    $data['localTime'] = $localTime;
    
    $table_head = [];
    $table_body = [];
    $table_footer = [];

    array_push($table_head, array('start'=>'Selector', 'end'=>''));

    

    //fill up intervals
    $intv = ($data['interval']==60) ?  "+ 60 minutes": "+".$data['interval']." minutes";
    $start = new \DateTime($data['fTime'], new \DateTimeZone($data['dataTime']));
    $end = new \DateTime($data['tTime'], new \DateTimeZone($data['dataTime']));
    $current = clone $start;
    while ($current <= $end) {
       $floor = $current->format("G:i");
       $ceil = $current->modify($intv);
       array_push($table_head, array('start'=>$floor, 'end'=>$ceil->format("G:i")));
        
    }
    array_push($table_head, array('start'=>'Total', 'end'=>''));
    array_push($table_footer, 'Total');
    
    // var_dump($table_head); exit;
    
    foreach($data['pickers'] as $item){
    $user = array('picker'=>$item->picker);
        $user['pick_counts'] = [];
        foreach($table_head as $times){
           if($times['end']!="")
           {
            $timestart = new \DateTime($times['start'], new \DateTimeZone($data['localTime']));
            $timestart->setTimeZone(new \DateTimeZone($data['dataTime']));
            $timeend = new \DateTime($times['end'], new \DateTimeZone($data['localTime']));
            $timeend->setTimeZone(new \DateTimeZone($data['dataTime'])); 
            $pick_count = $pick->countPicks($item->picker, $data['sDate'], $timestart->format("G:i"), $timeend->format("G:i"), $type, $data['docType'], $cust);
            array_push($user['pick_counts'], $pick_count[0]->total);
           }
        }
        $pick_count_day = $pick->totalPickerCountPerDay($item->picker, $data['sDate'], $type, $data['docType'], $cust);
        array_push($user['pick_counts'], $pick_count_day[0]->total);
        array_push($table_body, $user);
    }
    
    foreach($table_head as $times){
           if($times['end']!="")
           {
            $timestart = new \DateTime($times['start'], new \DateTimeZone($data['localTime']));
            $timestart->setTimeZone(new \DateTimeZone($data['dataTime']));
            $timeend = new \DateTime($times['end'], new \DateTimeZone($data['localTime']));
            $timeend->setTimeZone(new \DateTimeZone($data['dataTime']));
            $int_count = $pick->totalCountPerInterval($data['sDate'], $timestart->format("G:i"), $timeend->format("G:i"), $type, $data['docType'], $cust);
            array_push($table_footer, $int_count[0]->total);   
           }
    }
     $pick_full_day = $pick->totalPickDay($data['sDate'], $type, $data['docType'], $cust);
    array_push($table_footer, $pick_full_day[0]->total);
    


    $display1 = "";
    $total_hrs=0;
    $total_avg =0;
    $display1 .="<table style='margin-top: 80px' border=1 cellspacing=0 cellpadding=0 width='50%'><thead><tr><th>Selector</th><th>Avg/hr</th><th>Hours</th><th>Total Picks</th></tr></thead><tbody>";
    $tot_hr = 0;
    $tot_pic = 0;
    foreach($data['pickers'] as $item3){
        $display1 .="<tr>";
        $display1 .="<td>".$item3->picker."</td>";
        $res = $this->pickerHoursWorked($item3->picker, $data['interval'], $data['fTime'], $data['tTime'], $data['sDate'], $type, $data['docType'], $cust);
        $display1 .="<td>".$res['avg_hr']."</td>";
        $display1 .="<td>".$res['hours']."</td>";
        $tot_hr = $tot_hr + $res['hours'];
        $tot_pic = $tot_pic + $res['total'];
        $display1 .="<td>".$res['total']."</td>";
        $display1 .="</tr>";
    }
    if(($tot_pic>0) && ($tot_hr > 0)){
    $avg_per_hr = $tot_pic / $tot_hr;
    $avg_per_hr = round($avg_per_hr, 2);
}else{
    $avg_per_hr =0;
}
    $display1 .="</tbody><tfoot><tr><th>Totals</th><th>".$avg_per_hr."</th><th>".$tot_hr."</th><th>".$tot_pic."</th></tr></tfoot></table>";
    $data['table_head'] = $table_head;
    $data['table_body'] = $table_body;
    $data['table_footer'] = $table_footer;
    $data['display1']= $display1;
    $data['title'] = "Selectors";
    $data['alltotalpick'] = $tot_pic;
        return $data;
}

public function mailReport()
{ 
    $pick = new PickModel();
        $mail_settings = $pick->mailReportSettings();
        $mailset = json_decode($mail_settings[0]->settings, true); 
        $displayDate = ($mailset['displayDate'] == 'current') ? date('Y-m-d') : date('Y-m-d', strtotime($mailset['displayDate']));
        $type = !isset($this->request->getVar()['type']) ? 'Pallet' : $this->request->getVar()['type'];
        $type =strtolower($type);
       $cust = $this->getCustomerName(($this->request->getVar()['cust']));
     //  echo $cust; exit;
        $newdata = $this->dispPicks($displayDate, $mailset['FromTIme'], $mailset['ToTIme'], $mailset['Interval'], $mailset['docType'], $type, $mailset['dataTime'], $mailset['localTime'], $cust); //var_dump($newdata); exit;
        if ($newdata['alltotalpick'] > 0) {
            $message = "
        <html><head><title>Report</title></head><body>";
          //hide parameters 03/26/2023
          //  $message .= "<u>Parameters</u> <br>";
            //$message .= "Date: " . $displayDate . "<br>";
            //$message .= "From: " . $mailset['FromTIme'] . "<br>";
            //$message .= "To: " . $mailset['ToTIme'] . "<br>";
            //$message .= "Interval: " . $mailset['Interval'] . "<br>";
            //$message .= "Document Type: " . $mailset['docType'] . "<br>";
            $message .= "Type: " . $type . "<br>";
            $message .= "Customer: " . $cust . "<br>";
            //$message .= "Data Time: " . $this->timeText($mailset['dataTime']) . "<br>";
            //$message .= "Local Time: " . $this->timeText($mailset['localTime']) . "<br>";
            $message .= '<table style="margin-top: 80px; margin-bottom: 80px; padding-top: 20px; padding-bottom: 20px;" border="1" cellpadding=0 cellspacing=0 width="100%" role="grid" aria-describedby="rowtbl1_info" style="width: 100%;"><thead><tr>';
            foreach ($newdata['table_head'] as $th) {
                $message .= "<th>" . $th['start'] . "</th>";
            }

            $message .= "</tr></thead><tbody>";

            foreach ($newdata['table_body'] as $tr) {
                $message .= "<tr align='center'><td>" . $tr['picker'] . "</td>";
                foreach ($tr['pick_counts'] as $td) {
                    if ($td == 0) {
                        $message .= "<td style='background-color: red'>" . $td . "</td>";
                    } else {
                        $message .= "<td style='background-color: green'>" . $td . "</td>";
                    }
                }
                $message .= "</tr>";
            }
            $message .= "</tbody><tfoot><tr>";
            foreach ($newdata['table_footer'] as $th1) {
                if (is_numeric($th1)) {
                    if (($th1 >= 1)) {
                        $message .= "<th style='background-color: green'>" . $th1 . "</td>";
                    } else {
                        $message .= "<th  style='background-color: red'>" . $th1 . "</td>";
                    }
                } else {
                    $message .= "<th>" . $th1 . "</th>";
                }
            }
            $message .= "</tr></tfoot></table><br><br>";
            $message .= $newdata['display1'];
            $subject = "Dawson KPI Tool " . $displayDate . " " . $mailset['FromTIme'] . " - " . $mailset['ToTIme'];
         //   echo $message;
            $this->send_email($message, $subject, "dlspeedooutbound@dawsonlogistics.com", "dlspeedooutbound", "seun.sodimu@gmail.com", "Seun Sodimu", "developer@seun.me", "DAWSON KPI Tool", "", "", "", "", "");

            $this->sendMailSMTP("dlspeedooutbound@dawsonlogistics.com", $subject, $message, "");
            $this->sendMailSMTP("dennisb@dawsonlogistics.com", $subject, $message, "");
           $this->sendMailSMTP("developer@seun.me", $subject, $message, "");

        //    $this->sendMailSMTP("seun.sodimu@gmail.com", $subject, $message);
        }else{
            echo "No Pick";
        }
}

public function send_email($message, $subject, $to, $to_name, $to1, $to_name1, $from, $from_name, $cc, $bcc, $attachment, $attachment_name, $attachment_type) {
    require("src/sendgrid-php/sendgrid-php.php");
    $email = new \SendGrid\Mail\Mail(); 
    $email->setFrom($from, $from_name);
$email->setSubject($subject);
$email->addTo($to, $to_name);
//$email->addCc($to1, $to_name1);
// $email->addCc('Damian.Williams@dawsonlogistics.com', 'Damian Williams');
// $email->addCc('jim.lafoe@dawsonlogistics.com', 'Jim Lafoe');
// $email->addCc('brooksb@dawsonlogistics.com', 'Brooks Bennett-Miller');
// $email->addCc('ander.cruz@dawsonlogistics.com', 'Ander Cruz');
 //$email->addCc('dennisb@dawsonlogistics.com', 'Dennis Brinkhus');
 //$email->addCc('seun.sodimu@gmail.com', 'Seun Sodimu');
//$email->addCc('', 'DL Speedo Team');
$email->addContent(
"text/html", $message
);

$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY1'));
try {
$response = $sendgrid->send($email);
return ($response->statusCode());
} catch (Exception $e) {
echo 'Caught exception: '. $e->getMessage() ."\n";
}
    
}

public function timeText($text)
{
        switch ($text) {
            case 'America/Los_Angeles':
                return '(GMT-08:00) Pacific Time (US & Canada)';
                break;
            case 'America/Denver':
                return '(GMT-07:00) Mountain Time (US & Canada)';
                break;
            case 'America/Dawson_Creek':
                return '(GMT-07:00) Arizona';
                break;
            case 'America/Chicago':
                return '(GMT-06:00) Central Time (US & Canada)';
                break;
            case 'America/New_York':
                return '(GMT-05:00) Eastern Time (US & Canada)';
                break;
            default:
                return $text . 'th';
                break;
        }
}

function sendMailIN() { 
        $to = 'seun.sodimu@gmail.com';
        $subject = 'Checking Mail';
        $message = 'This is the second email from orginal server';
        
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('report@dawson-reports.com', 'Dawson Reports');
        
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
		    $data = $email->printDebugger(['headers']);
            var_dump($email);
            
            echo '<br>Updated Email successfully sent';
        } 
		else 
		{
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }
    
    function sendMailSMTP($to, $subject, $message, $cc) { 
        //$to = 'seun.sodimu@gmail.com';
        //$subject = 'Checking Mail';
        //$message = 'This is the first email';
        
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setFrom('report@dawson-reports.com', 'Dawson Reports');
        if($cc!=""){
        $email->setCC($cc);
        }
        $email->setSubject($subject);
        $email->setMessage($message);
        if ($email->send()) 
		{
		    
            echo 'Email successfully sent to '.$to;
        } 
		else 
		{
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }
    
    public function mailReportInv()
{
    require_once 'assets/dompdf/autoload.inc.php';
        $pick = new PickModel();
        $dompdf = new \Dompdf\Dompdf();
        $mail_settings = $pick->mailReportSettings();
        $mailset = json_decode($mail_settings[0]->settings, true); 
        $displayDate = ($mailset['displayDate'] == 'current') ? date('Y-m-d') : date('Y-m-d', strtotime($mailset['displayDate']));
     //  $mailset['Interval'] =15;
     $displayDate = !isset($this->request->getVar()['displayDate']) ? $displayDate : $this->request->getVar()['displayDate'];
        $type = !isset($this->request->getVar()['type']) ? 'Pallet' : $this->request->getVar()['type'];
        $type =strtolower($type);
     $cust = $this->getCustomerName(($this->request->getVar()['cust'])); //var_dump($cust); exit;
        $array = $pick->getAllData($displayDate, $type, $mailset['docType'], $cust);
       $times = $this->getBetweenTimes($mailset['FromTIme'], $mailset['ToTIme'], $mailset['Interval']);
        $pickers = [];
        $picker_counts = [];
        
            foreach ($array as $item) {
        if (!in_array($item->picker, $pickers)) {
            $pickers[] = $item->picker;
            $picker_counts[$item->picker] = [0, 0, 0, 0];
        }
    
        $time = strtotime($item->start_time);
        $index = floor((date("i", $time) - 0) / 15);
        $picker_counts[$item->picker][$index]++;
    }
    
    $total_counts = [0, 0, 0, 0];
    foreach ($picker_counts as $picker => $counts) {
        foreach ($counts as $index => $count) {
            $total_counts[$index] += $count;
        }
    }
   // var_dump($picker_counts); exit;
     $top = "<html><head><meta charset='UTF-8'><title>KPI Tool</title></head><body>";
     $top .="<p><strong>Type:</strong> ".ucfirst($type)."</p><p><strong>Customer:</strong> ".$cust."</p><p><strong>Date: </strong>".date('m/d/Y', strtotime($displayDate))."</p>";
     $table_head = "<table id='rowtbl3' width=100% border='1' cellspacing=0 cellpadding=10><thead><tr><th></th>";//var_dump($mailset); exit;
     foreach($pickers as $picker):
         $table_head .="<th>".$picker."</th>";
         endforeach;
         $table_head .="<th>TimeTotal</th>";
    $table_head .="</tr></thead>";
    $table_body ="<tbody>";
        foreach($times as $time):
            $table_body .="<tr><td>".$time['start']."</td>";
            $time_count =0;
            foreach($pickers as $picks):
                $count = $this->pickerCountPerInterval($array, $picks, $time['start'], $time['end'], $mailset['dataTime'], $mailset['localTime'], $displayDat, $this->request->getVar()['cust']);
                $bg = $this->tdColor($count);
                $table_body .= "<td align='center'".$bg.">".$count."</td>";
                $time_count = $time_count + $count;
                endforeach;
                $bg2 = $this->tdColor($time_count);
                $table_body .= "<td".$bg2.">".$time_count."</td>";
                $table_body .="</tr>";
            endforeach;
    $table_body .="</tbody>";
    $table_foot = "<tfoot><tr><th>Picker Total</th>";
    foreach($pickers as $pick):
        $tot_count = $this->pickerTotal($array, $pick, $displayDate);
        $bg3 = $this->tdColor($tot_count);
        $table_foot .= "<th".$bg3.">".$tot_count."</th>";
        endforeach;
        $count_allData = $this->totalPickCount($array, $displayDate, $this->request->getVar()['cust']);
        $bg4 = $this->tdColor($count_allData);
        $table_foot .="<th".$bg4.">".$count_allData."</th>";
        $table_foot .="</tr></tfoot></table><br><br><br>";
       // $summary = $this->summaryTable($pickers, $array, $mailset['Interval'], $times);
      //$summary = $this->create_picks_table($array, $mailset['Interval'], $pickers, $mailset['FromTIme'], $mailset['ToTIme'], $displayDate);
      $summary =$this->tableSummary($pickers, $mailset['Interval'], $mailset['FromTIme'], $mailset['ToTIme'], $displayDate, $type, $mailset['docType'], $cust);
      $bottom =     "</body></html>";
    // echo $displayDate."<br>";
    if($this->request->getVar()['view'] == 'email'){
        $subject = $cust." KPI report for ".$displayDate;
        $link = "Click the link below to view table in a browser <br>".base_url()."/mail-report2?view=html&cust=".$this->request->getVar()['cust']."&type=".$this->request->getVar()['type'];
        $html = $top.$link.$table_head.$table_body.$table_foot.$summary.$bottom;
  // $this->send_email($html, $subject, "dlspeedooutbound@dawsonlogistics.com", "dlspeedooutbound", "dennisb@dawsonlogistics.com", "Dennis Brinkhus", "report@dawson-reports.com", "DAWSON KPI Tool", "", "", "", "", "");
   //   $this->sendMailSMTP("dennisb@dawsonlogistics.com", $subject, $html); echo "<br>";
    //  $this->sendMailSMTP("developer@seun.me", $subject, $html); echo "<br>";

       $this->sendMailSMTP("dlspeedooutbound@dawsonlogistics.com", $subject, $html, "");
        }elseif($this->request->getVar()['view'] == 'pdf'){
            $html = $top;
            $html .=$table_head;
            $html .=$table_body;
            $html .=$table_foot;
            $html .=$summary;
            $html .=$bottom;
       require_once 'assets/dompdf/autoload.inc.php';
       $dompdf = new \Dompdf\Dompdf();
       $dompdf->loadHtml($html);
       $dompdf->setPaper('11x17', 'landscape');
       $dompdf->render();
       $dompdf->stream();
       }elseif($this->request->getVar()['view'] == 'html'){
    
        $data['title'] = "KPI Report";
        $data['customer'] = $cust;
        $data['type'] = $type;
        $data['date'] = $displayDate;
        $data['layout'] = base_url()."/mail-report2?view=html&cust=".$this->request->getVar()['cust']."&type=".$this->request->getVar()['type'];
        $data['table'] = $table_head.$table_body.$table_foot;
        $data['summary'] = $summary;
        return view("admin/display3", $data);
    }else{
           $html = $top;
           $html .="<a href='".base_url()."/mail-report2?view=html&cust=".$this->request->getVar()['cust']."&type=".$this->request->getVar()['type']."'>Rotate Table</a>";
           $html .=$table_head;
           $html .=$table_body;
           $html .=$table_foot;
           $html .=$summary;
           $html .=$bottom;
           echo $html;
       }
}


    public function pickerCountPerInterval($allData, $picker, $start, $end, $data_time, $local_time, $date, $cust)
    {
        $start = $date." ".$start;
        $end = $date." ".$end;
        $end = new \DateTime($end, new \DateTimeZone($data_time));
       if($cust == 2){
        $end->setTimeZone(new \DateTimeZone($local_time));
       }
        $end->modify('-1 second');
        $end = $end->format('H:i');
        
        $start = new \DateTime($start, new \DateTimeZone($data_time));
        if($cust ==2){
       $start->setTimeZone(new \DateTimeZone($local_time));
        }
        $start->modify('-1 second');
        $start = $start->format('H:i');
        
        //$start = new \DateTime($start, new \DateTimeZone($data_time));
        //$start->setTimeZone(new \DateTimeZone($local_time));
        
        //$end = new \DateTime($end, new \DateTimeZone($data_time));
        //$end->setTimeZone(new \DateTimeZone($local_time));
     //   var_dump($start." - ".$end); exit;
        $count =0;
        foreach($allData as $data):
           $complete = $data->start_time;
            $complete = new \DateTime($complete, new \DateTimeZone($data_time));
            $complete->modify('-1 hour');
           $complete= $complete->format('H:i');
           // $complete->setTimeZone(new \DateTimeZone($data_time)); 
           //var_dump($complete); exit;
            if(($data->picker == $picker) && ($start <= $complete) &&($complete <= $end)){
                if(($cust == 2) && ($data->new_type == 'cases')){
                $count += abs($data->transaction_qty);
                }else{
                    $count ++;
                }
            }
            endforeach;
            return $count;
    }

    public function pickerCountPerIntervalQty($allData, $picker, $start, $end, $data_time, $local_time, $date)
    {
        $start = $date." ".$start;
        $end = $date." ".$end;
        $end = new \DateTime($end, new \DateTimeZone($local_time));
        //$end->setTimeZone(new \DateTimeZone($local_time));
        $end->modify('-1 second');
        $end = $end->format('H:i');
        
        $start = new \DateTime($start, new \DateTimeZone($local_time));
       // $start->setTimeZone(new \DateTimeZone($local_time));
        $start->modify('-1 second');
        $start = $start->format('H:i');
        
        //$start = new \DateTime($start, new \DateTimeZone($data_time));
        //$start->setTimeZone(new \DateTimeZone($local_time));
        
        //$end = new \DateTime($end, new \DateTimeZone($data_time));
        //$end->setTimeZone(new \DateTimeZone($local_time));
        $count =0;
        foreach($allData as $data):
           $complete = $data->start_time;
            $complete = new \DateTime($complete, new \DateTimeZone($local_time));
            $complete->modify('-1 hour');
           $complete= $complete->format('H:i');
           // $complete->setTimeZone(new \DateTimeZone($data_time)); 
           //var_dump($complete); exit;
            if(($data->picker == $picker) && ($start <= $complete) &&($complete <= $end)){
                $count += $data->transaction_qty; var_dump($count);
            }
            endforeach;
            return $count;
    }
    
    public function pickerTotal($allData, $picker, $date)
    {
        $count =0;
        foreach($allData as $data):
            if(($data->picker == $picker) && ($data->start_date == $date)){
                $count ++;
            }
            endforeach;
            return $count;
    }
    
    public function tdColor($count)
    {
        $color =  " style='background:red'";
        if($count > 0){
            $color =" style='background:green'";
        }
        return $color;
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
    
    

    public function summaryTable($pickers, $array, $interval, $timeArray, $cust)

    {
        $total_avg = 0;
        $totpik = 0;
        $block_total =0;
// Create the HTML table
$html = '<table style="margin-top: 80px" border=1 cellspacing=0 cellpadding=0 width="50%">
            <tr>
                <th>Picker</th>
                <th>Average Picks per '.$interval.' minutes interval</th>
                <th>Blocks of '.$interval.' minutes interval worked</th>
                <th>Total Picks</th>
            </tr>';

foreach ($pickers as $picker ) {
    $total_picks = $this->totalPickCount($array, $picker, $cust);
    $avgPicks = $this->avgCountPerInterval($total_picks, $interval);
    $blocks = $this->timeBlocksWorked($array, $picker, $timeArray);
    $html .= '<tr>';
    $html .= '<td>' . $picker . '</td>';
    $html .= '<td>' . round($avgPicks,2) . '</td>';
    $html .= '<td>' . round($blocks,2) . '</td>';
    $html .= '<td>' . $total_picks . '</td>';
    $html .= '</tr>';
    $total_avg = $total_avg + $avgPicks;
    $totpik = $totpik + $total_picks;
    $block_total = $block_total + $blocks;
}

// Add total row
$html .= '<tr>';
$html .= '<td><strong>Total</strong></td>';
$html .= '<td>' . round($total_avg, 2) . '</td>';
$html .= '<td>' . round($block_total, 2) . '</td>';
$html .= '<td>' . $totpik .'</td>';
// $html .= '<td>' . $this->totalPickCount($array, $date) .'('.$totpik. ')</td>';
$html .= '</tr>';

$html .= '</table>';

// Output the HTML table
return $html;
    }

    //////////////test/////////////////////

public function dispPicksTest($displayDate, $FromTIme, $ToTIme, $Interval, $docType, $Type, $dataTime, $localTime, $cust="Speedo C/O Dawson Logistics")
{   
    $pick = new PickModel();
    $data['sDate'] = $displayDate;
    $data['fTime'] = date('H:i:s', strtotime($FromTIme));
    $data['tTime'] = date('H:i:s', strtotime($ToTIme)); //var_dump($data['fTime']." - ".$data['tTime']); exit;
    $data['interval'] = $Interval;
    $data['docType'] = $docType;
    $type = $Type;
    $data['type'] = $Type;
    $data['picks'] = $pick->viewDisplay($data['sDate'], $type, $data['docType'], $cust);
    $data['pickers'] = $pick->distinctActivePickers($data['sDate'], $type, $data['docType'], $cust);
    $int_avg = $data['interval'] / 60;
    $data['dataTime'] = $dataTime;
    $data['localTime'] = $localTime;
    
    $table_head = [];
    $table_body = [];
    $table_footer = [];

    array_push($table_head, array('start'=>'Selector', 'end'=>''));

    

    //fill up intervals
    $intv = ($data['interval']==60) ?  "+ 60 minutes": "+".$data['interval']." minutes";
    $start = new \DateTime($data['fTime'], new \DateTimeZone($data['dataTime']));
    $end = new \DateTime($data['tTime'], new \DateTimeZone($data['dataTime']));
    $current = clone $start;
    while ($current <= $end) {
       $floor = $current->format("G:i");
       $ceil = $current->modify($intv);
       array_push($table_head, array('start'=>$floor, 'end'=>$ceil->format("G:i")));
        
    }
    array_push($table_head, array('start'=>'Total', 'end'=>''));
    array_push($table_footer, 'Total');
    
    // var_dump($table_head); exit;
    
    foreach($data['pickers'] as $item){
    $user = array('picker'=>$item->picker);
        $user['pick_counts'] = [];
        foreach($table_head as $times){
           if($times['end']!="")
           {
            $timestart = new \DateTime($times['start'], new \DateTimeZone($data['localTime']));
            $timestart->setTimeZone(new \DateTimeZone($data['dataTime']));
            $timeend = new \DateTime($times['end'], new \DateTimeZone($data['localTime']));
            $timeend->setTimeZone(new \DateTimeZone($data['dataTime'])); 
            $pick_count = $pick->countPicks($item->picker, $data['sDate'], $timestart->format("G:i"), $timeend->format("G:i"), $type, $data['docType'], $cust);
            array_push($user['pick_counts'], $pick_count[0]->total);
           }
        }
        $pick_count_day = $pick->totalPickerCountPerDay($item->picker, $data['sDate'], $type, $data['docType'], $cust);
        array_push($user['pick_counts'], $pick_count_day[0]->total);
        array_push($table_body, $user);
    }
    
    foreach($table_head as $times){
           if($times['end']!="")
           {
            $timestart = new \DateTime($times['start'], new \DateTimeZone($data['localTime']));
            $timestart->setTimeZone(new \DateTimeZone($data['dataTime']));
            $timeend = new \DateTime($times['end'], new \DateTimeZone($data['localTime']));
            $timeend->setTimeZone(new \DateTimeZone($data['dataTime']));
            $int_count = $pick->totalCountPerInterval($data['sDate'], $timestart->format("G:i"), $timeend->format("G:i"), $type, $data['docType'], $cust);
            array_push($table_footer, $int_count[0]->total);   
           }
    }
     $pick_full_day = $pick->totalPickDay($data['sDate'], $type, $data['docType'], $cust);
    array_push($table_footer, $pick_full_day[0]->total);
    


    $display1 = "";
    $total_hrs=0;
    $total_avg =0;
    $display1 .="<table style='margin-top: 80px' border=1 cellspacing=0 cellpadding=0 width='50%'><thead><tr><th>Selector</th><th>Avg/hr</th><th>Hours</th><th>Total Picks</th></tr></thead><tbody>";
    $tot_hr = 0;
    $tot_pic = 0;
    foreach($data['pickers'] as $item3){
        $display1 .="<tr>";
        $display1 .="<td>".$item3->picker."</td>";
        $res = $this->pickerHoursWorked($item3->picker, $data['interval'], $data['fTime'], $data['tTime'], $data['sDate'], $type, $data['docType'], $cust);
        $display1 .="<td>".$res['avg_hr']."</td>";
        $display1 .="<td>".$res['hours']."</td>";
        $tot_hr = $tot_hr + $res['hours'];
        $tot_pic = $tot_pic + $res['total'];
        $display1 .="<td>".$res['total']."</td>";
        $display1 .="</tr>";
    }
    if(($tot_pic>0) && ($tot_hr > 0)){
    $avg_per_hr = $tot_pic / $tot_hr;
    $avg_per_hr = round($avg_per_hr, 2);
}else{
    $avg_per_hr =0;
}
    $display1 .="</tbody><tfoot><tr><th>Totals</th><th>".$avg_per_hr."</th><th>".$tot_hr."</th><th>".$tot_pic."</th></tr></tfoot></table>";
    $data['table_head'] = $table_head;
    $data['table_body'] = $table_body;
    $data['table_footer'] = $table_footer;
    $data['display1']= $display1;
    $data['title'] = "Selectors";
    $data['alltotalpick'] = $tot_pic;
        return $data;
}

public function mailReportTest()
{ 
    $pick = new PickModel();
        $mail_settings = $pick->mailReportSettings();
        $mailset = json_decode($mail_settings[0]->settings, true); 
        $displayDate = ($mailset['displayDate'] == 'current') ? date('Y-m-d') : date('Y-m-d', strtotime($mailset['displayDate']));
        $type = !isset($this->request->getVar()['type']) ? 'Pallet' : $this->request->getVar()['type'];
        $type =strtolower($type);
       $cust = $this->getCustomerName(($this->request->getVar()['cust']));
     //  echo $cust; exit;
     //$displayDate ='2023-04-29';
     $mailset['Interval'] = 60;
        $newdata = $this->dispPicksTest($displayDate, $mailset['FromTIme'], $mailset['ToTIme'], $mailset['Interval'], $mailset['docType'], $type, $mailset['dataTime'], $mailset['localTime'], $cust);
        if ($newdata['alltotalpick'] > 0) {
            $message = "
        <html><head><title>Report</title></head><body>";
          //hide parameters 03/26/2023
          //  $message .= "<u>Parameters</u> <br>";
            //$message .= "Date: " . $displayDate . "<br>";
            //$message .= "From: " . $mailset['FromTIme'] . "<br>";
            //$message .= "To: " . $mailset['ToTIme'] . "<br>";
            //$message .= "Interval: " . $mailset['Interval'] . "<br>";
            //$message .= "Document Type: " . $mailset['docType'] . "<br>";
            $message .= "Type: " . $type . "<br>";
            $message .= "Customer: " . $cust . "<br>";
            //$message .= "Data Time: " . $this->timeText($mailset['dataTime']) . "<br>";
            //$message .= "Local Time: " . $this->timeText($mailset['localTime']) . "<br>";
            $message .= '<table style="margin-top: 80px; margin-bottom: 80px; padding-top: 20px; padding-bottom: 20px;" border="1" cellpadding=0 cellspacing=0 width="100%" role="grid" aria-describedby="rowtbl1_info" style="width: 100%;"><thead><tr>';
            foreach ($newdata['table_head'] as $th) {
                $message .= "<th>" . $th['start'] . "</th>";
            }

            $message .= "</tr></thead><tbody>";

            foreach ($newdata['table_body'] as $tr) {
                $message .= "<tr align='center'><td>" . $tr['picker'] . "</td>";
                foreach ($tr['pick_counts'] as $td) {
                    if ($td == 0) {
                        $message .= "<td style='background-color: red'>" . $td . "</td>";
                    } else {
                        $message .= "<td style='background-color: green'>" . $td . "</td>";
                    }
                }
                $message .= "</tr>";
            }
            $message .= "</tbody><tfoot><tr>";
            foreach ($newdata['table_footer'] as $th1) {
                if (is_numeric($th1)) {
                    if (($th1 >= 1)) {
                        $message .= "<th style='background-color: green'>" . $th1 . "</td>";
                    } else {
                        $message .= "<th  style='background-color: red'>" . $th1 . "</td>";
                    }
                } else {
                    $message .= "<th>" . $th1 . "</th>";
                }
            }
            $message .= "</tr></tfoot></table><br><br>";
            $message .= $newdata['display1'];
            $subject = "Dawson KPI Tool " . $displayDate . " " . $mailset['FromTIme'] . " - " . $mailset['ToTIme'];
           // echo $message; exit;
          //  $this->send_email($message, $subject, "dlspeedooutbound@dawsonlogistics.com", "dlspeedooutbound", "seun.sodimu@gmail.com", "Seun Sodimu", "developer@seun.me", "DAWSON KPI Tool", "", "", "", "", "");
         //   $this->sendMailSMTP("dlspeedooutbound@dawsonlogistics.com", $subject, $message);
            $this->sendMailSMTP("dennisb@dawsonlogistics.com", $subject, $message, "");
           $this->sendMailSMTP("developer@seun.me", $subject, $message, "");
           $this->sendMailSMTP("willr@dawsonlogistics.com", $subject, $message, "");
        //    $this->sendMailSMTP("seun.sodimu@gmail.com", $subject, $message);
        }else{
            echo "No Pick";
        }
}

  public function mailReport2()
{

        $pick = new PickModel();
        
        $mail_settings = $pick->mailReportSettings();
        $mailset = json_decode($mail_settings[0]->settings, true); 
       $displayDate = ($mailset['displayDate'] == 'current') ? date('Y-m-d') : date('Y-m-d', strtotime($mailset['displayDate']));
      // $mailset['Interval'] =15;
         $displayDate = !isset($this->request->getVar()['displayDate']) ? $displayDate : $this->request->getVar()['displayDate'];
        $type = !isset($this->request->getVar()['type']) ? 'Pallet' : $this->request->getVar()['type'];
        $type =strtolower($type);
        $mailset['docType'] = !isset($this->request->getVar()['docType']) ? $mailset['docType'] : $this->request->getVar()['docType'];
        $docType = $mailset['docType'];
        $customer = ($this->request->getVar()['cust']);
     $cust = $this->getCustomerName($customer); 
        $array = $pick->getAllData($displayDate, $type, $mailset['docType'], $cust);
        
       $times = $this->getBetweenTimes($mailset['FromTIme'], $mailset['ToTIme'], $mailset['Interval']);
        $pickers = [];
      
            foreach ($array as $item) {
        if (!in_array($item->picker, $pickers)) {
            $pickers[] = $item->picker;
        }
    }
    
   
    
     $top = "<html><head><meta charset='UTF-8'><title>KPI Tool</title></head><body>";
     $top .="<p><strong>Type:</strong> ".ucfirst($type)."</p>";
     $top .="<p><strong>Pick/Putaway:</strong> ".ucfirst($docType)."</p>";
     $top .="<p><strong>Customer:</strong> ".$cust."</p>";
     $top .="<p><strong>Date: </strong>".date('m/d/Y', strtotime($displayDate))."</p>";
     $table_head = "<table id='rowtbl3' width=100% border='1' cellspacing=0 cellpadding=10><thead><tr><th></th>";//var_dump($mailset); exit;
     $timeheads = [];
     foreach($times as $time):
         $table_head .="<th>".$time['start']."</th>";
         $timehead = array('time'=>$time['start'], 'count'=>0);
         array_push($timeheads, $timehead);
         endforeach;
        // var_dump($timeheads[2]['time']); exit;
         $table_head .="<th>Picker Total</th>";
    $table_head .="</tr></thead>";
    $table_body ="<tbody>";
    $timestarts =[];
        foreach($pickers as $picks):
            $table_body .="<tr><td>".$picks."</td>";
            $time_count =0;
            foreach($times as $time):
                $count = $this->pickerCountPerInterval($array, $picks, $time['start'], $time['end'], $mailset['dataTime'], $mailset['localTime'], $displayDate, $this->request->getVar()['cust']);
                $key = array_search($time['start'], $timehead);
               // echo $time['start']." -- ";var_dump($key); exit;
                $bg = $this->tdColor($count);
                $table_body .= "<td align='center'".$bg.">".$count."</td>";
                $time_count = $time_count + $count;
                foreach($timeheads as $key=>$timehead):
                    if(($timehead['time']==$time['start'])){ //var_dump($count); exit;
                    //    $timehead1 = array('time'=>$timehead['time'], 'count'=>$count+$timehead['count']);
                        $timeheads[$key]['count'] = $count+$timehead['count'];
                        //var_dump($key); echo "<br>"; var_dump($timeheads); exit;
                    }
                    endforeach;
                endforeach;
                $bg2 = $this->tdColor($time_count);
                $table_body .= "<td".$bg2.">".$time_count."</td>";
                $table_body .="</tr>";
            endforeach;
    $table_body .="</tbody>";
    $table_foot = "<tfoot><tr><th>Time Total</th>";
    $counts_tol = $this->timeCounts($times, $array, $displayDate);
   // var_dump($timeheads); exit;
    foreach($timeheads as $time):
        $tot_count = $time['count'];
        $bg3 = $this->tdColor($tot_count);
        $table_foot .= "<th".$bg3.">".$tot_count."</th>";
        endforeach;
        $count_allData = $this->totalPickCount($array, $displayDate, $this->request->getVar()['cust']);
        $bg4 = $this->tdColor($count_allData);
        $table_foot .="<th".$bg4.">".$count_allData."</th>";
        $table_foot .="</tr></tfoot></table><br><br><br>";
        //$summary = $this->summaryTable($pickers, $array, $mailset['Interval'], $times);
        //$summary = $this->create_picks_table($array, $mailset['Interval'], $pickers, $mailset['FromTIme'], $mailset['ToTIme'], $displayDate);
        $summary =$this->tableSummary($pickers, $mailset['Interval'], $mailset['FromTIme'], $mailset['ToTIme'], $displayDate, $type, $mailset['docType'], $cust);
        $bottom =     "</body></html>";
    // echo $displayDate."<br>";
     if($this->request->getVar()['view'] == 'email'){
        $override = !isset($this->request->getVar()['override']) ? false : true;
     $subject = $cust." KPI report for ".$displayDate;
     $link = "Click the link below to view table in a browser <br>".base_url()."/mail-report2?view=html&cust=".$this->request->getVar()['cust']."&type=".$this->request->getVar()['type']."&docType=".$docType."<br><br>";
     $html = $top.$link.$table_head.$table_body.$table_foot.$summary.$bottom;

 if($cust == "Speedo C/O Dawson Logistics"){
 $email_rec = "dlspeedooutbound@dawsonlogistics.com";
 $emails_recs = "";
 }else{
    $subject = $cust." KPI report";
    $email_rec = "brooksb@dawsonlogistics.com";
    $emails_recs = "dennisb@dawsonlogistics.com, jason.mcpherson@dawsonlogistics.com, Donald.Garza@dawsonlogistics.com, willr@dawsonlogistics.com, mtorma@dawsonlogistics.com, developer@seun.me";
 }
    $currentReport = json_encode($array, JSON_PRETTY_PRINT);
    $local_json = "assets/json/last_".$customer.$type.$docType.".json";
    $previousReport = file_exists($local_json) ? file_get_contents($local_json) : '';
if ($currentReport !== $previousReport && !empty($currentReport)) {
    $this->sendMailSMTP($email_rec, $subject, $html, $emails_recs); echo "<br>". $emails_recs;
    file_put_contents($local_json, $previousReport);
}else{
    if($override){
        $this->sendMailSMTP($email_rec, $subject, $html, $emails_recs); echo "<br>". $emails_recs;
    }
    echo "No new data";
}
 
     }elseif($this->request->getVar()['view'] == 'pdf'){
        $html = $top;
        $html .=$table_head;
        $html .=$table_body;
        $html .=$table_foot;
        $html .=$summary;
        $html .=$bottom;
    require_once 'assets/dompdf/autoload.inc.php';
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('11x17', 'landscape');
    $dompdf->render();
    $dompdf->stream();
    }elseif($this->request->getVar()['view'] == 'html'){

        // $currentReport = json_encode($array, JSON_PRETTY_PRINT);
        // $local_json = "assets/json/last_".$customer.$type.$docType.".json";
        // $previousReport = file_exists($local_json) ? file_get_contents($local_json) : '';
        // file_put_contents($local_json, $currentReport);

        $data['title'] = "KPI Report";
        $data['customer'] = $cust;
        $data['type'] = $type;
        $data['date'] = $displayDate;
        $data['docType'] = $mailset['docType'];
        $data['layout'] = base_url()."/mail-report-rev?view=html&cust=".$this->request->getVar()['cust']."&type=".$this->request->getVar()['type']."&docType=".$docType;
        $data['table'] = $table_head.$table_body.$table_foot;
        $data['summary'] = $summary;
        return view("admin/display3", $data);
    }
    else{
        $html = $top;
        $html .="<a href='".base_url()."/mail-report-rev?view=html&cust=".$this->request->getVar()['cust']."&type=".$this->request->getVar()['type']."'>Rotate Table</a>";
        $html .=$table_head;
        $html .=$table_body;
        $html .=$table_foot;
        $html .=$summary;
        $html .=$bottom;
        echo $html;
        echo "<table style='margin-top: 80px' border=1 cellspacing=0 cellpadding=0 width='50%'>";
    echo "<tr><th>Picker</th><th>Average Picks per Hour</th><th>Total Hours Worked</th><th>Total Picks</th></tr>";
    $totAvg1 =0;
    $totHr1 =0;
    $totPicks1 =0;
        foreach($pickers as $picker):
            $pickData = $this->calculatePickerStats($array, $picker, $displayDate);
            echo "<tr><td>".$picker."</td><td>".round($pickData['avg'], 2)."</td><td>".round($pickData['hours'], 2)."</td><td>".$pickData['total']."</td></tr>";
            $totAvg1 = $totAvg1 + $pickData['avg'];
            $totHr1 = $totHr1 + $pickData['hours'];
            $totPicks1 = $totPicks1 + $pickData['total'];    
        endforeach;
        echo "<tr><th>Totals</th><th>".round($totAvg1, 2)."</th><th>".round($totHr1, 2)."</th><th>".$totPicks1."</th></tr>";
        echo "</table>";
    }
}

 public function timeCounts($timeRanges, $picks, $date) { 
    $counts = array();

    foreach ($timeRanges as $range) {
        $count = 0; 
        $startRange = strtotime($date. ' '. $range['start']) -1; 
        $endRange = strtotime($date. ' '. $range['end']) -1;

        foreach ($picks as $pick) {
            $startPick = strtotime($pick->start_date . ' ' . $pick->start_time);
            $endPick = strtotime($pick->complete_date . ' ' . $pick->complete_time);

            if (($startPick >= $startRange && $startPick < $endRange) || ($endPick >= $startRange && $endPick < $endRange)) {
                $count++;
            }
        }

        $counts[] = $count;
    }

    return $counts;
}

public function totalPickCount($array, $date, $cust)
{
    $count =0;
    foreach($array as $item):
        if(($item->start_date == $date) && ($item->complete_date == $date)){
            if($cust ==1){
                $count ++;
            }else{
            $count +=abs($item->transaction_qty);
            }
        }
        endforeach;
        return $count;
}

public function timeBlocksWorked($array, $picker, $timeArray)
{
    foreach($timeArray as $time):
        $start = $time['start'];
        $end = $time['end'];
        $count =0;
        foreach($array as $item):
            if(($item->picker == $picker) && ($item->start_time >= $start) && ($item->complete_time <= $end)){
                $count ++;
            }
            endforeach;
            
        endforeach;
        return $count;
    
}

public function avgCountPerInterval($no_of_picks, $interval)
{
    $avg = $no_of_picks / $interval;
    return $avg;
}

public function totalPickerPick($array, $date, $picker)
{
    $count =0;
    foreach($array as $item):
        if(($item->start_date == $date) && ($item->complete_date == $date) && ($item->picker == $picker)){
            $count ++;
        }
        endforeach;
        return $count;
}

public function create_picks_table($array, $interval, $pickers, $start, $end, $date) {
    $display1 = "";
    $total_hrs=0;
    $total_avg =0;
    $display1 .="<table style='margin-top: 80px' border=1 cellspacing=0 cellpadding=0 width='50%'><thead><tr><th>Selector</th><th>Avg/hr</th><th>Hours</th><th>Total Picks</th></tr></thead><tbody>";
    $tot_hr = 0;
    $tot_pic = 0;
    foreach($pickers as $picker){
        $display1 .="<tr>";
        $display1 .="<td>".$picker."</td>";
        $res = $this->pickerHoursWorked2($picker, $interval, $start, $end, $date, $array);
        $display1 .="<td>".$res['avg_hr']."</td>";
        $display1 .="<td>".$res['hours']."</td>";
        $tot_hr = $tot_hr + $res['hours'];
        $tot_pic = $tot_pic + $res['total'];
        $display1 .="<td>".$res['total']."</td>";
        $display1 .="</tr>";
    }
    if(($tot_pic>0) && ($tot_hr > 0)){
    $avg_per_hr = $tot_pic / $tot_hr;
    $avg_per_hr = round($avg_per_hr, 2);
}else{
    $avg_per_hr =0;
}
    $display1 .="</tbody><tfoot><tr><th>Totals</th><th>".$avg_per_hr."</th><th>".$tot_hr."</th><th>".$tot_pic."</th></tr></tfoot></table>";
    return $display1;
}

  public function pickerHoursWorked2($picker, $intvl, $start, $end, $date, $array)
    {
        $intvl_to_hour = 60 /$intvl; 
        $time_worked = 0;
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $current = clone $start;
         while ($current <= $end) { 
            $floor = $current->format("G:i");
            $ceil = $current->modify("+".$intvl." minutes");
            $pick_count = $this->pickInterval($picker, $date, $floor, $ceil->format("G:i"), $array); //var_dump($pick_count); exit;
           // var_dump($ceil->format("G:i")); exit;
            if($pick_count >=1 ){
                $time_worked++;
            }
         }
        
         $pickDay = $this->pickDay($picker, $date, $array); //var_dump($pickDay); exit;
		
         $reponse['hours'] = round($time_worked / $intvl_to_hour, 1); //var_dump($intvl_to_hour); exit;
         $reponse['avg_hr'] = ($reponse['hours']!=0) ? round(($pickDay / $reponse['hours']), 1): 0;
         $reponse['total'] = $pickDay;

         return $reponse;
    }

    public function pickInterval($picker, $start, $end, $date, $array)
    {
        $count = 0;
        foreach($array as $item):
            if(($item->picker == $picker) && ($item->start_date == $date) && ($item->complete_date == $date)){
                $start_time = $item->start_time;
                $end_time = $item->complete_time;
                if(($start_time >= $start) && ($end_time <= $end)){
                    $count ++;
                }
            }
            endforeach;
            return $count;
    }

    public function pickDay($picker, $date, $array)
    {
        $count =0;
        foreach($array as $item):
            if(($item->picker == $picker) && ($item->start_date == $date) && ($item->complete_date == $date)){
                $count ++;
            }
            endforeach;
            return $count;
    }

    public function tableSummary($pickers, $interval, $fTime, $tTime, $sDate, $type, $docType, $cust)
    {
        $total_avg = 0;
        $totpik = 0;
        $block_total =0;
        
    $display1 = "";
    $total_hrs=0;
    $total_avg =0;
    $display1 .="<table style='margin-top: 80px' border=1 cellspacing=0 cellpadding=0 width='50%'><thead><tr align='center'><th>Selector</th><th align='center'>Avg/hr</th><th align='center'>Hours</th><th align='center'>Total Picks</th></tr></thead><tbody>";
    $tot_hr = 0;
    $tot_pic = 0;
    foreach($pickers as $picker){
        $display1 .="<tr>";
        $display1 .="<td>".$picker."</td>";
        $res = $this->pickerHoursWorked($picker, $interval, $fTime, $tTime, $sDate, $type, $docType, $cust);
        $display1 .="<td align='center'>".$res['avg_hr']."</td>";
        $display1 .="<td align='center'>".$res['hours']."</td>";
        $tot_hr = $tot_hr + $res['hours'];
        $tot_pic = $tot_pic + $res['total'];
        $display1 .="<td align='center'>".$res['total']."</td>";
        $display1 .="</tr>";
    }
    if(($tot_pic>0) && ($tot_hr > 0)){
    $avg_per_hr = $tot_pic / $tot_hr;
    $avg_per_hr = round($avg_per_hr, 2);
}else{
    $avg_per_hr =0;
}
    $display1 .="<tr><td><strong>Totals</strong></td><td align='center'><strong>".$avg_per_hr."</strong></td><td align='center'><strong>".$tot_hr."</strong></td><td align='center'><strong>".$tot_pic."</strong></td></tr></tbody></table>";
        return $display1;
    }
  


public function calculatePickerStats($data, $picker, $date)
{
    // Filter the data for the specified picker and date
    $filteredData = array_filter($data, function ($item) use ($picker, $date) {
        return $item->picker == $picker && $item->start_date == $date;
    });

    // Calculate the total picks and total hours
    $totalPicks = 0;
    $totalHours = 0;

    foreach ($filteredData as $item) {
        $startDateTime = new \DateTime($item->start_date . ' ' . $item->start_time);
        $completeDateTime = new \DateTime($item->complete_date . ' ' . $item->complete_time);
        $interval = $startDateTime->diff($completeDateTime);

        $totalPicks ++;
        $totalHours += $interval->h + ($interval->i / 60); // Convert minutes to hours
    }

    // Calculate the average pick per hour
    $averagePickPerHour = ($totalHours!=0) ?$totalPicks / $totalHours:0;
    $data = array(
        'picker' => $picker,
        'date' => $date,
        'avg' => $averagePickPerHour,
        'hours' => $totalHours,
        'total' => $totalPicks
    );
    return $data;
}


}