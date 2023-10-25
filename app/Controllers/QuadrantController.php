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
        $mail_settings = $pick->mailReportSettings();
        $admin = new AdminController();
        $mailset = json_decode($mail_settings[0]->settings, true);
        $mailset['Interval'] = isset($this->request->getGet()['intv']) ? $this->request->getGet()['intv'] : $mailset['Interval'];
        $mailset['docType'] = isset($this->request->getGet()['docType']) ? $this->request->getGet()['docType'] : $mailset['docType'];
        $mailset['localTime'] = isset($this->request->getGet()['localTime']) ? $this->request->getGet()['localTime'] : $mailset['localTime'];
        $mailset['dataTime'] = isset($this->request->getGet()['dataTime']) ? $this->request->getGet()['dataTime'] : $mailset['dataTime'];
        $array = $pick->getAllCasesPallets($displayDate, $type, $mailset['docType'], $cust);
        
        $times = $admin->getBetweenTimes($mailset['FromTIme'], $mailset['ToTIme'], $mailset['Interval']);
        $data['table'] = "";
        $table_head = "<table id='rowtbl3' width=100% border='1' cellspacing=0 cellpadding=10><thead><tr><th></th>";
        $timeheads = [];
        foreach ($times as $time):
            $table_head .= "<th>" . $time['start'] . "</th>";
            $timehead = array('time' => $time['start'], 'count' => 0, 'time_top_right' => 0, 'time_top_left' => 0, 'time_bottom_right' => 0, 'time_bottom_left' => 0 );
            array_push($timeheads, $timehead);
        endforeach;
        $table_head .= "<th>Picker Total</th>";
        $table_head .= "</tr></thead>";
        $table_body = "<tbody>";
        $pickers = [];
        foreach ($array as $item) {
            if (!in_array($item->picker, $pickers)) {
                $pickers[] = $item->picker;
            }
        }
        $table_body = "<tbody>";
        $timestarts = [];
        foreach ($pickers as $picks):
            $table_body .= "<tr><td>" . $picks . "</td>";
            $time_count = 0;
            $picker_top_left = 0;
            $picker_top_right = 0;
            $picker_bottom_left = 0;
            $picker_bottom_right = 0;
            foreach ($times as $time):
                $countx = $this->pickerCountPerInterval($array, $picks, $time['start'], $time['end'], $mailset['dataTime'], $mailset['localTime'], $displayDate, 2);
                $count = $countx['cases'] + $countx['pallets'];
                $key = array_search($time['start'], $timehead);

                $bg1 = $admin->tdColor($countx['pallets']);
                $bg2 = $admin->tdColor($countx['cases']);
                $bg3 = $admin->tdColor($countx['cases_on_pallet']);
                $bg4 = $admin->tdColor($countx['caselabel']);

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

        $data['title'] = "KPI Quad Report";
        $data['customer'] = $cust;
        $data['type'] = "Four Square";
        $data['date'] = $displayDate;
        $data['docType'] = $mailset['docType'];
        $data['layout'] = "";
        $data['links'] = base_url('quad')."?type=view";
        $data['table'] = $table_head . $table_body . $table_foot;
        $data['summary'] = "";
        if($this->request->getGet()['type'] == 'view'){
        return view('admin/display3', $data);
        }else{
            $top = "<html><head><meta charset='UTF-8'><title>KPI Tool</title></head><body>";
     $top .="<p><strong>Pick/Putaway:</strong> Four Square</p><p><strong>Pick/Putaway:</strong> ".ucfirst($mailset['docType'])."</p>";
     $top .="<p><strong>Customer:</strong> ".$cust."</p>";
     $top .="<p><strong>Date: </strong>".date('m/d/Y', strtotime($displayDate))."</p>";
     $top .="<p><strong>Live link: </strong>https://dawson-reports.com/kpi/quad?type=view&intv=60</p>";
     $bottom =     "</body></html>";
        $html = $top.$table_head.$table_body.$table_foot.$bottom;
     $subject = $cust." KPI report (Quadrant Display)";
     $email_rec = "dennisb@dawsonlogistics.com";
     $emails_recs = "seun.sodimu@gmail.com";
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

}