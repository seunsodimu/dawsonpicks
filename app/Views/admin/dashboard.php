<?php $session = \Config\Services::session();
helper('date'); 
?>
<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<div class="container" style="margin-top:20px;">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Admin Dashboard</div>
                <div class="panel-body">
                    <h1>Hello <?= session()->get('name') ?>,</h1>
                    <div class="pull-right">
                    <a href="<?= site_url('upload-summary') ?>">Upload Summary</a> | 
                    <a href="<?= site_url('logout') ?>">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">View KPI Report</div>
                <div class="panel-body">
                    <form method="GET" id="displayform" action="<?= base_url('mail-report2') ?>">
                    <input type="hidden" name="view" value="html">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Customer</label>
                            <select name="cust" class="form-control">
                                <?php foreach($customers as $customer): 
                                    if($customer['cust_id']==session()->get('customer')){
                                        $selectedcust = "selected";
                                    }else{
                                        $selectedcust = "";
                                    }
                                    ?>
                                  <option value="<?= $customer['cust_id'] ?>" <?= $selectedcust ?>><?= $customer['cust_name'] ?></option>
                                <?php endforeach;    ?>
                            </select><br/><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Local Time</label>
                            <select name="localTime" class="form-control">
<option value="America/Chicago" selected <?= (session()->get('dataTime')=='America/Chicago') ? " selected" : "" ?>>(GMT-06:00) Central Time (US & Canada)</option>
<option value="America/Los_Angeles" <?= (session()->get('dataTime')=='America/Los_Angeles') ? " selected" : "" ?>>(GMT-08:00) Pacific Time (US & Canada)</option>
<option value="America/Denver" <?= (session()->get('dataTime')=='America/Denver') ? " selected" : "" ?>>(GMT-07:00) Mountain Time (US & Canada)</option>
<option value="America/Dawson_Creek" <?= (session()->get('dataTime')=='America/Dawson_Creek') ? " selected" : "" ?>>(GMT-07:00) Arizona</option>
<option value="America/New_York" <?= (session()->get('dataTime')=='America/New_York') ? " selected" : "" ?>>(GMT-05:00) Eastern Time (US & Canada)</option>        
                        </select>
                        </div>
                        <div class="col-md-6">
                            <label>Data Time</label>
                            <select name="dataTime" class="form-control">
<option value="America/New_York" <?= (session()->get('dataTime')=='America/New_York') ? " selected" : "" ?>>(GMT-05:00) Eastern Time (US & Canada)</option>               
<option value="America/Los_Angeles" <?= (session()->get('dataTime')=='America/Los_Angeles') ? " selected" : "" ?>>(GMT-08:00) Pacific Time (US & Canada)</option>
<option value="America/Denver" <?= (session()->get('dataTime')=='America/Denver') ? " selected" : "" ?>>(GMT-07:00) Mountain Time (US & Canada)</option>
<option value="America/Dawson_Creek" <?= (session()->get('dataTime')=='America/Dawson_Creek') ? " selected" : "" ?>>(GMT-07:00) Arizona</option>
<option value="America/Chicago" <?= (session()->get('dataTime')=='America/Chicago') ? " selected" : "" ?>>(GMT-06:00) Central Time (US & Canada)</option>

                        </select>
                        </div>
                    </div><br/>
<div class="form-group">
    <label>Date to display</label>
    <input type="date" name="displayDate" id="displayDate" class="form-control" value="<?= (!empty(session()->get('displayDate')))?session()->get('displayDate'): date('Y-m-d') ?>" required>
</div>
<div class="form-group">
    <label>Interval</label>
    <select name="Interval" class="form-control"><option value="10" <?= (session()->get('Interval')==10)?"selected": "" ?>>10 mins</option><option value="15" <?= (session()->get('Interval')==15)?"selected": "" ?>>15 mins</option><option value="30"  <?= (session()->get('Interval')==30)?"selected": "" ?>>30 mins</option><option value="60" <?= ((session()->get('Interval')==60) || empty(session()->get('docType')))?"selected": "" ?>>1 hour</option></select>
</div>
<div class="form-group">
    <label>Doc Type</label>
    <select name="docType" class="form-control"><option value="Putaway" <?= (session()->get('docType')=="Putaway")?"selected": "" ?>>Putaway</option><option value="Pick" <?= ((session()->get('docType')=="Pick") || empty(session()->get('docType')))?"selected": "" ?>>Pick</option><option value="Pallet Checked In" <?= (session()->get('docType')=="Pallet Checked In")?"selected": "" ?>>Pallet Checked In</option><option value="Pallet Assembly" <?= (session()->get('docType')=="Pallet Assembly")?"selected": "" ?>>Pallet Assembly</option><option value="Move" <?= (session()->get('docType')=="Move")?"selected": "" ?>>Move</option></select>
</div>
<div class="form-group">
    <label>Type</label>
    <select name="type" class="form-control"><option <?= (session()->get('Type')=="Pallet")?"selected": "" ?>>Pallet</option><option <?= ((session()->get('Type')=="Cases") || (empty(session()->get('Type'))))?"selected": "" ?>>Cases</option></select>
</div>
<div class="form-group">
    <input type="submit" id="submit-btn-view" value="Submit" class="btn btn-lg btn-success showSpinner" />
    <!--<input type="reset" id="reset-btn-view" value="Reset" class="btn btn-lg btn-danger" />-->
</div>
                    </form>
                </div>
            </div>
        </div>
        
        
     

        
    </div>
   <div class="row">
   <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Upload FIle</div>
                <div class="panel-body">
                   <?php
 
 if(!empty($session->getFlashdata('message'))){ 
    echo "<div class='alert ".$session->getFlashdata('alert_class')."'>".$session->getFlashdata('message')."</div>"; };
?>

<form id="upload-form" action="<?= base_url('import-file');?>" method="post" enctype="multipart/form-data">
    <label><input type="checkbox" name="Overwrite" value="Yes" checked> Overwrite if data for selected date exists </label>
    <span><br>(All data for the date selected will be removed and replaced by the new uploaded data)</span>
<br> <br>
<div class="form-group">
<label>Select Date</label>
<input type="date" name="data_date" class="form-control" required>
</div>
<div class="form-group">
    Upload csv file : 
    <input type="file" name="file" value="" required /><br><br>
    <input type="submit" name="submit" id="submit-btn" value="Upload" class="btn btn-lg btn-success" />
    <span id="upldn-msg" style="display: none;">
    Uploading <i class="fa fa-spinner fa-fw fa-xl margin-right-md fa-spin" style="color: var(--white); --fa-animation-duration:2s;"></i>
</span>
</div>

</form>
                </div>
            </div>
        </div>  


        <div class="col-sm-6">
            <div class="panel panel-primary">
                <div class="panel-heading">Selector Display</div>
                <div class="panel-body">
                    <form method="GET" id="displayform" action="<?= base_url('pickSelectors') ?>">
<div class="form-group">
    <label>Selectors</label>
    <select name="pickSelector" class="form-control" id="pickSelector">
        <option>All Selectors</option>
        <?php foreach($pickers as $picker){
            echo "<option>".$picker->picker."</option>";
        }
        ?>
    </select>
</div>
<div class="form-group" style="padding-bottom: 60px">
<div class="col-md-3">
    <label>Report Type</label>
    <select name="reportType" id="reportType" class="form-control">
        <option <?= (session()->get('reportType')=="Week")?"selected": "" ?> selected>Week</option>
        <option <?= ((session()->get('reportType')=="Month") || (empty(session()->get('Type'))))?"selected": "" ?>>Months</option>
    </select>
</div>
    <div class="col-md-3">
    <label>Interval</label>
    <select name="Interval" class="form-control"><option value="10" <?= (session()->get('Interval')==10)?"selected": "" ?>>10 mins</option><option value="15" <?= (session()->get('Interval')==15)?"selected": "" ?>>15 mins</option><option value="30"  <?= (session()->get('Interval')==30)?"selected": "" ?>>30 mins</option><option value="60" <?= ((session()->get('Interval')==60) || empty(session()->get('docType')))?"selected": "" ?>>1 hour</option></select>
</div>
<div class="col-md-3">
    <label>Doc Type</label>
    <select name="docType" class="form-control"><option value="Putaway" <?= (session()->get('docType')=="Putaway")?"selected": "" ?>>Putaway</option><option value="Pick" <?= ((session()->get('docType')=="Pick") || empty(session()->get('docType')))?"selected": "" ?>>Pick</option><option value="Pallet Checked In" <?= (session()->get('docType')=="Pallet Checked In")?"selected": "" ?>>Pallet Checked In</option><option value="Pallet Assembly" <?= (session()->get('docType')=="Pallet Assembly")?"selected": "" ?>>Pallet Assembly</option><option value="Move" <?= (session()->get('docType')=="Move")?"selected": "" ?>>Move</option></select>
</div>
<div class="col-md-3">
    <label>Type</label>
    <select name="Type" class="form-control"><option <?= (session()->get('Type')=="Pallet")?"selected": "" ?>>Pallet</option><option <?= ((session()->get('Type')=="Cases") || (empty(session()->get('Type'))))?"selected": "" ?>>Cases</option></select>
</div>
</div>

<div class="form-group" style="padding-bottom: 60px">
    <p><strong>DateTime range to display</strong></p>
      <div class="col-md-4" id="froms">
        <label>Range</label>
        <input type="date" name="fromDate" id="fromDate" class="form-control" value="<?= (!empty(session()->get('FromDate')))?session()->get('FromDate'): date('Y-m-d') ?>">
        <select name="fromMonth" id="fromMonth" class="form-control" required style="display: none;">
            <option>January</option>
            <option>February</option>
            <option>March</option>
            <option>April</option>
            <option>May</option>
            <option>June</option>
            <option>July</option>
            <option>August</option>
            <option>September</option>
            <option>October</option>
            <option>November</option>
            <option>December</option>
        </select>
    </div>
    <div class="col-md-4">
    <label>From time</label>
    <input type="time" name="FromTIme" class="form-control" value="<?= (!empty(session()->get('FromTIme')))?session()->get('FromTIme'): "06:00" ?>" required>
</div>
<div class="col-md-4">
    <label>To time</label>
    <input type="time" name="ToTIme" class="form-control" value="<?= (!empty(session()->get('ToTIme')))?session()->get('ToTIme'): "18:00" ?>" required>
</div>
</div>


<div class="form-group">
    <input type="submit" id="submit-btn-view" value="Submit" class="btn btn-lg btn-success" />
</div>
                    </form>
                </div>
            </div>
        </div>

   </div>
</div>
<?= $this->endSection() ?>
