<div class="header-wrapper">
                        <header>
                                <div class="container">


                                        <div class="logo-container">
                                                <!-- Website Logo -->
                                                <a href="<?= base_url('home') ?>"  title="LAWBOSS CITADEL">
                                                    <img src="<?= base_url() ?>/assets/images/logo.png" width="250px" alt="LAWBOSS CITADEL">
                                                </a>
                                                <span class="tag-line">BossPoint</span>
                                        </div>
<?php
	if(!empty($logged_user)){
	?>

                                        <!-- Start of Main Navigation -->
                                        <nav class="main-nav">
                                                <div class="menu-top-menu-container">
                                                        <ul id="menu-top-menu" class="clearfix">
                                                                <li class=""><a href="<?= base_url('home') ?>">Home</a></li>
                                                                <li><a href="#">HR</a>
																	<ul class="sub-menu">
																		<li><a href="<?= base_url('training/2022-benefits-guide') ?>">Benefits & Guides</a></li>
																		<li><a href="<?= base_url('job-openings') ?>">Career Opportunities</a></li>
																		<li><a href="<?= base_url('policies') ?>">Handbook & Other Policies</a></li>
																		<li><a href="<?= base_url('suggest') ?>">Suggestion Box</a></li>
																		</ul>
																</li>
                                                                <li><a href="#">Trainings</a>
                                                                        <ul class="sub-menu">
                                                                            <li><a href="<?= base_url('trainings/attorneys-training') ?>">Attorneys</a></li>
                                                                                <li><a href="<?= base_url('trainings/case-management-training') ?>">Case Management</a></li>
                                                                                <li><a href="<?= base_url('trainings/closing-training') ?>">Closing</a></li>
                                                                                <!--<li><a href="<?= base_url('trainings/development-training') ?>">Development</a></li>-->
                                                                                <li><a href="<?= base_url('trainings/front-office-training') ?>">Front Office/Mailroom</a></li>
                                                                                <li><a href="<?= base_url('trainings/hr-training') ?>">HR</a></li>
                                                                                <li><a href="<?= base_url('trainings/intake-training') ?>">Intake</a></li>
                                                                                <li><a href="<?= base_url('trainings/isr-training') ?>">ISR</a></li>
                                                                                <li><a href="<?= base_url('trainings/litigation-training') ?>">Litigation</a></li>
                                                                               <!-- <li><a href="<?= base_url('trainings/marketing-training') ?>">Marketing</a></li> -->
                                                                                <li><a href="<?= base_url('trainings/medical-records-training') ?>">Medical Records</a></li>
                                                                                <li><a href="<?= base_url('trainings/negotiations-training') ?>">Negotiations</a></li>
                                                                                <li><hr></li>
                                                                                <li><a href="<?= base_url('trainings/firm-wide-training') ?>">Firm-Wide</a></li>
																			<li><a href="<?= base_url('trainings/it-knowledge-base') ?>">IT Knowledge Base</a></li>
                                                                        </ul>
                                                                </li>
                                                                <li><a href="#">Our Team</a>
                                                                        <ul class="sub-menu">
                                                                            <li><a href="<?= base_url('orgchart') ?>">Org Chart</a></li>
                                                                                <li><a href="<?= base_url('directory') ?>">Directory</a></li>
                                                                        </ul>
                                                                </li>
                                                               
                                                                <li><a href="#">Sites & Tools</a>
                                                                <ul class="sub-menu">
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://app.nectarhr.com/login-account')">Nectar Login</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://uvallelaw.my.salesforce.com/')">Salesforce Login</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://access.paylocity.com/')">Paylocity Login</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://trainual.com')">Trainual Login</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://my.voya.com/voyassoui/index.html?target=https%3A%2F%2Fmy.voya.com%2Fmyvoya%2Flogon#/login')">Voya Login</a></li>
                                                                    <li><hr></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://login.publicdata.com/index.html')" target="_blank">PublicData.com | Public Records</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://safer.fmcsa.dot.gov/')">Safer Web</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://apps.txdmv.gov/apps/mccs/truckstop/')">TxDMV - Texas Truck Stop</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://signin.lexisnexis.com/lnaccess/app/signin?back=https%3A%2F%2Fadvance.lexis.com%3A443%2Ftax&aci=la')" target="_blank">Lexis</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://www.efiletexas.gov/')">eFileTexas.Gov</a></li>
                                                                    <li><a href="javascript:void(0)" onclick="window.open('https://www.ewccv.com/cvs/?ref=https://www.tdi.texas.gov/')">TDI - Division of Workers Compensation</a></li>
                                                                </ul>
                                                                </li>
                                                        </ul>
                                                </div>
                                        </nav>
                                        <!-- End of Main Navigation -->
<?php } ?>
									
                                </div>
                        </header>
                </div>