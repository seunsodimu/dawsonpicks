<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('ImportDataController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'ImportDataController::index', ['filter'=> 'noauth']);
$routes->post('import-file', 'ImportDataController::importXls', ['filter'=> 'auth']);
$routes->get("/", "UserController::login");
$routes->match(['get', 'post'], 'login', 'UserController::login', ["filter" => "noauth"]);
// Admin routes
$routes->group("admin", ["filter" => "auth"], function ($routes) {
    $routes->get("/", "AdminController::index");

});
// Editor routes
$routes->group("editor", ["filter" => "auth"], function ($routes) {
    $routes->get("/", "EditorController::index");
});
$routes->get('logout', 'UserController::logout');
$routes->get("display", "AdminController::viewPicks", ["filter" => "auth"]);
$routes->get("display2", "AdminController::viewPicksTable", ["filter" => "auth"]);
$routes->get("pickSelectors", "AdminController::pickSelectorByDate", ["filter" => "auth"]);
$routes->get("upload-summary", "AdminController::uploadSummary", ["filter" => "auth"]);
$routes->get("upload-summary2", "AdminController::uploadSummary2", ["filter" => "auth"]);
$routes->get("get-ftp", "FTPImportController::index");
$routes->get("rmv-dups", "AdminController::testdups");
$routes->get("test-ftp", "FTPImportController::showFTP");
$routes->post("post-ftp", "FTPImportController::testFTP");
$routes->get("man-ftp", "FTPImportController::manualFTP");
$routes->get("sendmailtest", "EditorController::send_email");
$routes->get("mail-report", "AdminController::mailReport");
$routes->get("mail-report-smtp", "AdminController::mailReportTest");
$routes->get("kpi", "KpiController::index");
$routes->get("kpi-data", "KpiController::getData");
$routes->get("mail-report-rev", "AdminController::mailReportInv");
$routes->get("mail-report2", "AdminController::mailReport2");
$routes->get("quad", "QuadrantController::getQuadrant");
$routes->get("picker-time-block", "TimeBlockController::pickerTimeBlockPicks");
$routes->get("test-filelist", "FTPImportController::showFileList");
$routes->get('download-data-file', 'AdminController::downloadRecentRawData');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
