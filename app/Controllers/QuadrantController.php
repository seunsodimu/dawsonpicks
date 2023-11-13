<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\PickModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use Exception;
use App\Controllers\AdminController;
use App\Controllers\TimeBlockController;


class QuadrantController extends BaseController
{
    public function __construct()
    {

    }
    public function index()
    {

    }

    public function getQuadrant()
    {
        $displayDate = isset($this->request->getGet()['displayDate']) ? $this->request->getGet()['displayDate'] : date('Y-m-d');
        $type = "Cases";
        $cust = "Sprout Foods, Inc";
        $pick = new PickModel();
        $timeblock = new TimeBlockController();
        $mail_settings = $pick->mailReportSettings();
        $admin = new AdminController();
        $mailset = json_decode($mail_settings[0]->settings, true);
        $mailset['Interval'] = isset($this->request->getGet()['intv']) ? $this->request->getGet()['intv'] : $mailset['Interval'];
        $mailset['docType'] = isset($this->request->getGet()['docType']) ? $this->request->getGet()['docType'] : $mailset['docType'];
        $mailset['localTime'] = isset($this->request->getGet()['localTime']) ? $this->request->getGet()['localTime'] : $mailset['localTime'];
        $mailset['dataTime'] = isset($this->request->getGet()['dataTime']) ? $this->request->getGet()['dataTime'] : $mailset['dataTime'];
        $mailset['FromTIme'] = isset($this->request->getGet()['FromTIme']) ? $this->request->getGet()['FromTIme'] : $mailset['FromTIme'];
        $mailset['ToTIme'] = isset($this->request->getGet()['ToTIme']) ? $this->request->getGet()['ToTIme'] : $mailset['ToTIme'];
        $mailset['creditMultiplier'] = isset($this->request->getGet()['labelCreditMultiplier']) ? $this->request->getGet()['creditMultiplier'] : $mailset['labelCreditMultiplier'];
        $array = $pick->getAllCasesPallets($displayDate, $type, $mailset['docType'], $cust);
        
        $times = $admin->getBetweenTimes($mailset['FromTIme'], $mailset['ToTIme'], $mailset['Interval']);
        $data['table'] = "";
        $table_head = "<table id='rowtbl3' width=100% border='1' cellspacing=0 cellpadding=10><thead><tr><th></th>";
        $timeheads = [];
        $pickersSummary = [];
        foreach ($times as $time):
            $table_head .= "<th>" . $time['start'] . "</th>";
            $timehead = array('time' => $time['start'], 'count' => 0, 'time_top_right' => 0, 'time_top_left' => 0, 'time_bottom_right' => 0, 'time_bottom_left' => 0 );
            array_push($timeheads, $timehead);
        endforeach;
        $table_head .= "<th>Picker Total</th>";
        $table_head .= "</tr></thead>";
        $table_body = "<tbody>";
        $pickers = [];
        foreach ($array as $item):
            if (!in_array($item->picker, $pickers)) {
                $pickers[] = $item->picker;
            }
        endforeach;
        $table_body = "<tbody>";
        
        foreach ($pickers as $picks):
            $table_body .= "<tr><td>" . $picks . "</td>";
            $time_count = 0;
            $picker_top_left = 0;
            $picker_top_right = 0;
            $picker_bottom_left = 0;
            $picker_bottom_right = 0;
            $labelCredit = 0;
            foreach ($times as $time):
                $countx = $this->pickerCountPerInterval($array, $picks, $time['start'], $time['end'], $mailset['dataTime'], $mailset['localTime'], $displayDate, 2);
                $count = $countx['cases'] + $countx['pallets'];
                $key = array_search($time['start'], $timehead);

                $bg1 = $admin->tdColor($countx['pallets']);
                $bg2 = $admin->tdColor($countx['cases']);
                $bg3 = $admin->tdColor($countx['cases_on_pallet']);
                if($countx['caselabel']!=0){
                    $labelCredit += $countx['caselabel'] * $mailset['labelCreditMultiplier'];
                }
                $intervalToSec = $mailset['Interval'] * 60;
                if($countx['caselabel'] == 0){
                    if($labelCredit > $intervalToSec){
                    $bg4 = " align='center' style='background:yellow'";
                    $labelCredit = $labelCredit - $intervalToSec;
                    }else{
                        $bg4 = $admin->tdColor($countx['caselabel']);
                    }
                }else{
                $bg4 = $admin->tdColor($countx['caselabel']);
                }
                $table_body .= "<td style='padding: 0 0 0 0; margin: 0 0 0 0'>";
                $table_body .= "<table width=100% cellspacing=0 cellpadding=0>";
                $table_body .= "<tr>";
                $table_body .= "<td " . $bg2 . ">" . $countx['cases'] . "</td>";
                $table_body .= "<td " . $bg4 . ">" . $countx['caselabel'] . "</td>";
                $table_body .= "</tr>";
                $table_body .= "<tr>";
                $table_body .= "<td " . $bg1 . ">" . $countx['pallets'] . "</td>";
                $table_body .= "<td " . $bg3 . ">" . $countx['cases_on_pallet'] . "</td>";
                $table_body .= "</tr></table>";
                $table_body .= "</td>";
                $picker_top_left += $countx['cases'];
                $picker_top_right += $countx['caselabel'];
                $picker_bottom_left += $countx['pallets'];
                $picker_bottom_right += $countx['cases_on_pallet'];

                $time_count = $time_count + $count;
                foreach ($timeheads as $key => $timehead):
                    if (($timehead['time'] == $time['start'])) { 
                        $timeheads[$key]['time_top_left'] += $picker_top_left;
                        $timeheads[$key]['time_top_right'] += $picker_top_right;
                        $timeheads[$key]['time_bottom_left'] += $picker_bottom_left;
                        $timeheads[$key]['time_bottom_right'] += $picker_bottom_right;
                    }
                endforeach;
            endforeach;
            $bg_1 = $admin->tdColor($picker_top_left);
            $bg_2 = $admin->tdColor($picker_top_right);
            $bg_3 = $admin->tdColor($picker_bottom_left);
            $bg_4 = $admin->tdColor($picker_bottom_right);
            $table_body .= "<td style='padding: 0 0 0 0; margin: 0 0 0 0'>";
            $table_body .= "<table width=100% cellspacing=0 cellpadding=0>";
            $table_body .= "<tr>";
            $table_body .= "<td " . $bg_1 . ">" . $picker_top_left . "</td>";
            $table_body .= "<td " . $bg_2 . ">" . $picker_top_right . "</td>";
            $table_body .= "</tr>";
            $table_body .= "<tr>";
            $table_body .= "<td " . $bg_3 . ">" . $picker_bottom_left . "</td>";
            $table_body .= "<td " . $bg_4 . ">" . $picker_bottom_right . "</td>";
            $table_body .= "</tr></table>";
            $table_body .= "</td>";
            $table_body .= "</tr>";
            $pickerSummary = array('picker' => $picks, 'count' => $time_count, 'time_top_right' => $picker_top_right, 'time_top_left' => $picker_top_left, 'time_bottom_right' => $picker_bottom_right, 'time_bottom_left' => $picker_bottom_left );
            array_push($pickersSummary, $pickerSummary);
        endforeach;
        //$table_body .= "</tbody>";
        $table_foot = "<tr><td>Time Total</td>";
        $total_top_left = 0;
        $total_top_right = 0;
        $total_bottom_left = 0;
        $total_bottom_right = 0;
        foreach ($times as $timey):
            $county = $this->timeCountPerInterval($array, $timey['start'], $timey['end'], $mailset['dataTime'], $mailset['localTime'], $displayDate, 2);
           
            $bg1_1 = $admin->tdColor($county['time_top_left']);
            $bg1_2 = $admin->tdColor($county['time_top_right']);
            $bg1_3 = $admin->tdColor($county['time_bottom_left']);
            $bg1_4 = $admin->tdColor($county['time_bottom_right']);
            $table_foot .= "<td style='padding: 0 0 0 0; margin: 0 0 0 0'>";
            $table_foot .= "<table width=100% cellspacing=1 cellpadding=1 style='border: 1px solid'>  ";
            $table_foot .= "<tr>";
            $table_foot .= "<td " . $bg1_1 . ">" . $county['time_top_left'] . "</td>";
            $table_foot .= "<td " . $bg1_2 . ">" . $county['time_top_right'] . "</td>";
            $table_foot .= "</tr>";
            $table_foot .= "<tr>";
            $table_foot .= "<td " . $bg1_3 . ">" . $county['time_bottom_left'] . "</td>";
            $table_foot .= "<td " . $bg1_4 . ">" . $county['time_bottom_right'] . "</td>";
            $table_foot .= "</tr></table>";
            $table_foot .= "</td>";
            $total_top_left += $county['time_top_left'];
            $total_top_right += $county['time_top_right'];
            $total_bottom_left += $county['time_bottom_left'];
            $total_bottom_right += $county['time_bottom_right'];
        endforeach;
        $bg2_1 = $admin->tdColor($total_top_left);
        $bg2_2 = $admin->tdColor($total_top_right);
        $bg2_3 = $admin->tdColor($total_bottom_left);
        $bg2_4 = $admin->tdColor($total_bottom_right);
        $table_foot .= "<td style='padding: 0 0 0 0; margin: 0 0 0 0'>";
        $table_foot .= "<table width=100% cellspacing=1 cellpadding=1 style='border: 1px solid'>";
        $table_foot .= "<tr>";
        $table_foot .= "<td " . $bg2_1 . ">" . $total_top_left . "</td>";
        $table_foot .= "<td " . $bg2_2 . ">" . $total_top_right . "</td>";
        $table_foot .= "</tr>";
        $table_foot .= "<tr>";
        $table_foot .= "<td " . $bg2_3 . ">" . $total_bottom_left . "</td>";
        $table_foot .= "<td " . $bg2_4 . ">" . $total_bottom_right . "</td>";
        $table_foot .= "</tr></table>";
        $table_foot .= "</td>";
        $table_foot .= "</tr></tbody></table>";
        //$summary =$this->tableSummary($pickers, $mailset['Interval'], $mailset['FromTIme'], $mailset['ToTIme'], $displayDate, '', $mailset['docType'], $cust);
        $hour_array = $timeblock->getTimeArray(60);
        $summary = "<br><br><br>";
        $summary .= "<table style='margin-top: 80px' border=1 cellspacing=0 cellpadding=0 width='80%'><thead><tr align='center'><td align='center'>Selector</td><td align='center'>Avg/hr</td><td align='center'>Hours</td><td align='center'>Total Picks</td></tr></thead><tbody>";
        $total_avg_top_left = 0;
        $total_avg_top_right = 0;
        $total_avg_bottom_left = 0;
        $total_avg_bottom_right = 0;
        $total_hours = 0;
        $total_pick_top_left = 0;
        $total_pick_top_right = 0;
        $total_pick_bottom_left = 0;
        $total_pick_bottom_right = 0;
        foreach($pickersSummary as $ps):
            $pickerHoursWorked = $timeblock->getPickerPickHitsInTimeArray($array, $ps['picker'], $hour_array, $displayDate);
            $avg_top_left = ($ps['time_top_left']!=0 && $pickerHoursWorked!=0) ? round(($ps['time_top_left'] / $pickerHoursWorked), 1) : 0;
            $avg_bottom_left = ($ps['time_bottom_left']!= 0 && $pickerHoursWorked!=0) ? round(($ps['time_bottom_left'] / $pickerHoursWorked), 1) : 0;
            $avg_top_right = ($ps['time_top_right']!= 0 && $pickerHoursWorked!=0) ? round(($ps['time_top_right'] / $pickerHoursWorked), 1) : 0;
            $avg_bottom_right = ($ps['time_bottom_right']!= 0 && $pickerHoursWorked!=0) ? round(($ps['time_bottom_right'] / $pickerHoursWorked), 1) : 0;
            $summary .= "<tr>";
            $summary .= "<td>".$ps['picker']."</td>";
            $summary .= "<td align='left'>";
            $summary .= "<table width=100% cellspacing=0 cellpadding=0 border=1>";
            $summary .= "<tr>";
            $summary .= "<td align='center' style='width: 50%'>".$avg_top_left."</td>";
            $summary .= "<td align='center' style='width: 50%'>".$avg_top_right."</td>";
            $summary .= "</tr>";
            $summary .= "<tr>";
            $summary .= "<td align='center' style='width: 50%'>".$avg_bottom_left."</td>";
            $summary .= "<td align='center' style='width: 50%'>".$avg_bottom_right."</td>";
            $summary .= "</tr></table>";
            $summary .= "</td>";
            $summary .= "<td align='center'>".$pickerHoursWorked."</td>";
            $summary .= "<td align='left'>";
            $summary .= "<table width=100% cellspacing=0 cellpadding=0 border=1>";
            $summary .= "<tr>";
            $summary .= "<td align='center' style='width: 50%'>".$ps['time_top_left']."</td>";
            $summary .= "<td align='center' style='width: 50%'>".$ps['time_top_right']."</td>";
            $summary .= "</tr>";
            $summary .= "<tr>";
            $summary .= "<td align='center' style='width: 50%'>".$ps['time_bottom_left']."</td>";
            $summary .= "<td align='center' style='width: 50%'>".$ps['time_bottom_right']."</td>";
            $summary .= "</tr></table>";
            $summary .= "</td>";
            $summary .= "</tr>";
            $total_hours += $pickerHoursWorked;
            $total_pick_top_left += $ps['time_top_left'];
            $total_pick_top_right += $ps['time_top_right'];
            $total_pick_bottom_left += $ps['time_bottom_left'];
            $total_pick_bottom_right += $ps['time_bottom_right'];
        endforeach;
        $total_avg_top_left = ($total_pick_top_left!=0) ? round(($total_pick_top_left / $total_hours), 1): 0;
        $total_avg_top_right = ($total_pick_top_left!=0) ? round(($total_pick_top_right / $total_hours), 1): 0;
        $total_avg_bottom_left = ($total_pick_top_left!=0) ? round(($total_pick_bottom_left / $total_hours), 1): 0;
        $total_avg_bottom_right = ($total_pick_top_left!=0) ? round(($total_pick_bottom_right / $total_hours), 1): 0;
        $summary .= "<tr>";
        $summary .= "<td><strong>Totals</strong></td>";
        $summary .= "<td align='left'>";
        $summary .= "<table width=100% cellspacing=0 cellpadding=0 border=1>";
        $summary .= "<tr>";
        $summary .= "<td align='center' style='width: 50%'>".$total_avg_top_left."</td>";
        $summary .= "<td align='center' style='width: 50%'>".$total_avg_top_right."</td>";
        $summary .= "</tr>";
        $summary .= "<tr>";
        $summary .= "<td align='center' style='width: 50%'>".$total_avg_bottom_left."</td>";
        $summary .= "<td align='center' style='width: 50%'>".$total_avg_bottom_right."</td>";
        $summary .= "</tr></table>";
        $summary .= "</td>";
        $summary .= "<td align='center'>".$total_hours."</td>";
        $summary .= "<td align='left'>";
        $summary .= "<table width=100% cellspacing=0 cellpadding=0 border=1>";
        $summary .= "<tr>";
        $summary .= "<td align='center' style='width: 50%'>".$total_pick_top_left."</td>";
        $summary .= "<td align='center' style='width: 50%'>".$total_pick_top_right."</td>";
        $summary .= "</tr>";
        $summary .= "<tr>";
        $summary .= "<td align='center' style='width: 50%'>".$total_pick_bottom_left."</td>";
        $summary .= "<td align='center' style='width: 50%'>".$total_pick_bottom_right."</td>";
        $summary .= "</tr></table>";
        $summary .= "</td>";
        $summary .= "</tbody></table>";
        
        $data['title'] = "KPI Quad Report";
        $data['customer'] = $cust;
        $data['type'] = "Four Square";
        $data['date'] = $displayDate;
        $data['docType'] = $mailset['docType'];
        $data['layout'] = "";
        $data['links'] = base_url('quad')."?type=view";
        $data['table'] = $table_head . $table_body . $table_foot;
        $data['summary'] = $summary;
        $data['description'] = '';
        $data['FromTIme'] = $mailset['FromTIme'];
        $data['ToTIme'] = $mailset['ToTIme'];
        if($this->request->getGet()['type'] == 'view'){
        return view('admin/display3', $data);
        }else{
            $top = "<html><head><meta charset='UTF-8'><title>KPI Tool</title></head><body>";
     $top .="<p><strong>Type:</strong> Four Square</p><p><strong>Pick/Putaway:</strong> ".ucfirst($mailset['docType'])."</p>";
     $top .="<p><strong>Customer:</strong> ".$cust."</p>";
     $top .="<p><strong>Date: </strong>".date('m/d/Y', strtotime($displayDate))."</p>";
     $top .="<p><strong>Live link: </strong>https://dawson-reports.com/kpi/quad?type=view&intv=60</p>";
     
     $bottom =     "</body></html>";
        $html = $top.$table_head.$table_body.$table_foot.$summary.$bottom;
     $subject = $cust." KPI report (Quadrant Display)";
     $email_rec = "brooksb@dawsonlogistics.com";
     $emails_recs = "dennisb@dawsonlogistics.com, jason.mcpherson@dawsonlogistics.com, Donald.Garza@dawsonlogistics.com, willr@dawsonlogistics.com, mtorma@dawsonlogistics.com, developer@seun.me";
    $admin->sendMailSMTP($email_rec, $subject, $html, $emails_recs); 
        }
    }

    public function pickerCountPerInterval($allData, $picker, $start, $end, $data_time, $local_time, $date, $cust)
    {
        $start = $date . " " . $start;
        $end = $date . " " . $end;
        $end = new \DateTime($end, new \DateTimeZone($data_time));
        if ($cust == 2) {
            $end->setTimeZone(new \DateTimeZone($local_time));
        }
        $end->modify('-1 second');
        $end = $end->format('H:i');

        $start = new \DateTime($start, new \DateTimeZone($data_time));
        if ($cust == 2) {
            $start->setTimeZone(new \DateTimeZone($local_time));
        }
       // $start->modify('-1 second');
        $start = $start->format('H:i');
        $count = [];
        $all_count = 0;
        $pallet_count = 0;
        $cases_count = 0;
        $cases_on_pallet = 0;
        $case_labelled = 0;
        foreach ($allData as $data):
            $complete = $data->start_time;
            $complete = new \DateTime($complete, new \DateTimeZone($data_time));
            $complete->modify('-1 hour');
            $complete = $complete->format('H:i');
            if (($data->picker == $picker) && ($start <= $complete) && ($complete <= $end)) {
                if (($cust == 2) && ($data->new_type == 'cases')) {
                    $cases_count += abs($data->transaction_qty);
                    
                } elseif (($cust == 2) && ($data->new_type == 'pallet')) {
                    $pallet_count++;
                    $cases_on_pallet += abs($data->transaction_qty);
                }
                if(strtoupper(trim($data->caselabel)) != "*NONE"){
                    $case_labelled += abs($data->transaction_qty);
                }
            }
        endforeach;
        $count = array('pallets' => $pallet_count, 'cases' => $cases_count, 'cases_on_pallet' => $cases_on_pallet, 'caselabel' => $case_labelled);
        return $count;
    }

    public function timeCountPerInterval($allData, $start, $end, $data_time, $local_time, $date, $cust)
    {
        $start = $date . " " . $start;
        $end = $date . " " . $end;
        $end = new \DateTime($end, new \DateTimeZone($data_time));
        if ($cust == 2) {
            $end->setTimeZone(new \DateTimeZone($local_time));
        }
        $end->modify('-1 second');
        $end = $end->format('H:i');

        $start = new \DateTime($start, new \DateTimeZone($data_time));
        if ($cust == 2) {
            $start->setTimeZone(new \DateTimeZone($local_time));
        }
        $start->modify('-1 second');
        $start = $start->format('H:i');
        $count = [];
        $all_count = 0;
        $pallet_count = 0;
        $cases_count = 0;
        $cases_on_pallet = 0;
        $case_labelled = 0;
        foreach ($allData as $data):
            $complete = $data->start_time;
            $complete = new \DateTime($complete, new \DateTimeZone($data_time));
            $complete->modify('-1 hour');
            $complete = $complete->format('H:i');
            if (($start <= $complete) && ($complete <= $end)) {
                if (($cust == 2) && ($data->new_type == 'cases')) {
                    $cases_count += abs($data->transaction_qty);
                    
                } elseif (($cust == 2) && ($data->new_type == 'pallet')) {
                    $pallet_count++;
                    $cases_on_pallet += abs($data->transaction_qty);
                }
                if(strtoupper(trim($data->caselabel)) != "*NONE"){
                    $case_labelled += abs($data->transaction_qty);
                }
            }
        endforeach;
        $count = array('time_bottom_left' => $pallet_count, 'time_top_left' => $cases_count, 'time_bottom_right' => $cases_on_pallet, 'time_top_right' => $case_labelled);
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
        $reponse['hours'] = round($time_worked / $intvl_to_hour, 1); //var_dump($intvl_to_hour); exit;
        $reponse['avg_hr'] = ($reponse['hours']!=0) ? round(($pickDay[0]->total / $reponse['hours']), 1): 0;
        $reponse['total'] = $pickDay[0]->total;

         return $reponse;
    }

}