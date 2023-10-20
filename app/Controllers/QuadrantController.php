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
        $array = $pick->getAllCasesPallets($displayDate, $type, $mailset['docType'], $cust);
        //var_dump($array); exit;
        $times = $admin->getBetweenTimes($mailset['FromTIme'], $mailset['ToTIme'], $mailset['Interval']);
        $data['table'] = "";
        $table_head = "<table id='rowtbl3' width=100% border='1' cellspacing=0 cellpadding=10><thead><tr><th></th>";
        $timeheads = [];
        foreach ($times as $time):
            $table_head .= "<th>" . $time['start'] . "</th>";
            $timehead = array('time' => $time['start'], 'count' => 0);
            array_push($timeheads, $timehead);
        endforeach;
        $table_head .= "<th>Picker Total</th>";
        $table_head .= "</tr></thead>";
        $table_body = "<tbody>";
        $timestarts = [];
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
            foreach ($times as $time):
                $countx = $this->pickerCountPerInterval($array, $picks, $time['start'], $time['end'], $mailset['dataTime'], $mailset['localTime'], $displayDate, 2);
                $count = $countx['cases'] + $countx['pallets'];
                $key = array_search($time['start'], $timehead);

                $bg = $admin->tdColor($count);
                $bg1 = ($countx['pallets']) > 0 ? "num-count" : "zerocount";
                $bg2 = ($countx['cases']) > 0 ? "num-count" : "zerocount";
                $bg3 = ($countx['cases_on_pallet']) > 0 ? "num-count" : "zerocount";
                $bg4 = ($countx['caselabel']) > 0 ? "num-count" : "zerocount";
                $table_body .= "<td>";
                $table_body .= "<table width=100% cellspacing=0 cellpadding=0>";
                $table_body .= "<tr>";
                $table_body .= "<td class='" . $bg2 . "'>" . $countx['cases'] . "</td>";
                $table_body .= "<td class='" . $bg4 . "'>" . $countx['caselabel'] . "</td>";
                $table_body .= "</tr>";
                $table_body .= "<tr>";
                $table_body .= "<td class='" . $bg1 . "'>" . $countx['pallets'] . "</td>";
                $table_body .= "<td class='" . $bg3 . "'>" . $countx['cases_on_pallet'] . "</td>";
                $table_body .= "</tr></table>";
                $table_body .= "</td>";

                $time_count = $time_count + $count;
                foreach ($timeheads as $key => $timehead):
                    if (($timehead['time'] == $time['start'])) { //var_dump($count); exit;
                        //    $timehead1 = array('time'=>$timehead['time'], 'count'=>$count+$timehead['count']);
                        $timeheads[$key]['count'] = $count + $timehead['count'];
                        //var_dump($key); echo "<br>"; var_dump($timeheads); exit;
                    }
                endforeach;
            endforeach;
            $bg2 = $admin->tdColor($time_count);
            $table_body .= "<td" . $bg2 . ">" . $time_count . "</td>";
            $table_body .= "</tr>";
        endforeach;
        $table_body .= "</tbody>";
        $table_foot = "<tfoot><tr><th>Time Total</th>";
        $counts_tol = $admin->timeCounts($times, $array, $displayDate);
        // var_dump($timeheads); exit;
        foreach ($timeheads as $time):
            $tot_count = $time['count'];
            $bg3 = $admin->tdColor($tot_count);
            $table_foot .= "<th" . $bg3 . ">" . $tot_count . "</th>";
        endforeach;
        $count_allData = $admin->totalPickCount($array, $displayDate, 2);
        $bg4 = $admin->tdColor($count_allData);
        $table_foot .= "<th" . $bg4 . ">" . $count_allData . "</th>";
        $table_foot .= "</tr></tfoot></table>";

        $data['title'] = "KPI Quad Report";
        $data['customer'] = $cust;
        $data['type'] = $type;
        $data['date'] = $displayDate;
        $data['docType'] = $mailset['docType'];
        $data['layout'] = base_url() . "/mail-report-rev?";
        $data['table'] = $table_head . $table_body . $table_foot;
        $data['summary'] = "";
        if($this->request->getGet()['type'] == 'view'){
        return view('admin/display3', $data);
        }else{
            $top = "<html><head><meta charset='UTF-8'><title>KPI Tool</title></head><body>";
    //  $top .="<p><strong>Type:</strong> ".ucfirst($type)."</p>";
     $top .="<p><strong>Pick/Putaway:</strong> ".ucfirst($mailset['docType'])."</p>";
     $top .="<p><strong>Customer:</strong> ".$cust."</p>";
     $top .="<p><strong>Date: </strong>".date('m/d/Y', strtotime($displayDate))."</p>";
     $bottom =     "</body></html>";
        $html = $top.$table_head.$table_body.$table_foot.$bottom;
     $subject = $cust." KPI report (Quadrant Display)";
     $email_rec = "seun.sodimu@gmail.com";
     $emails_recs = "";
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
            // $complete->setTimeZone(new \DateTimeZone($data_time)); 
            //var_dump($complete); exit;
            if (($data->picker == $picker) && ($start <= $complete) && ($complete <= $end)) {
                if (($cust == 2) && ($data->new_type == 'cases')) {
                    $cases_count += abs($data->transaction_qty);
                    if(strtoupper($data->caselabel) != "*NONE"){
                        $case_labelled += abs($data->transaction_qty);
                    }
                } elseif (($cust == 2) && ($data->new_type == 'pallet')) {
                    $pallet_count++;
                    $cases_on_pallet += abs($data->transaction_qty);
                }
            }
        endforeach;
        $count = array('pallets' => $pallet_count, 'cases' => $cases_count, 'cases_on_pallet' => $cases_on_pallet, 'caselabel' => $case_labelled);
        return $count;
    }

}