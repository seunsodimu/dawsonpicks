<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\PickModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;

class KpiController extends BaseController
{
    public function __construct()
    {
        
    }
    public function index()
    { 
        $pick = new PickModel();
        $data['pickers'] = $pick->distinctPickers();
        $data['title'] = "KPI Tool";
        return view("admin/tool", $data);
    }
    
     public function getData()
    { var_dump($this->request->getVar()); exit;
        $interval = $this->request->getPost('interval'); // Get the selected interval from the view
        $date = $this->request->getPost('date'); // Get the selected date from the view

        // Calculate the start and end time based on the selected interval
        $startTime = strtotime('00:00:00');
        $endTime = strtotime('23:59:59');
        $increments = [10, 15, 30, 45, 60]; // Available time intervals

        foreach ($increments as $inc) {
            if ($interval == $inc) {
                $endTime = strtotime('00:' . $interval . ':00', $startTime);
                break;
            }
        }

        // Query the database to get the counts of picks for each picker
        $pickModel = new PickModel();
        $results = $pickModel->getCounts($date, $startTime, $endTime);

        return json_encode($results); // Return the results as JSON
    }

}