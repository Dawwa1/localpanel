<?php

require __DIR__ . '/vendor/autoload.php';

use FrameworkX\App;
use FrameworkX\FilesystemHandler;
use Localpanel\Controllers\HomeController;
use Localpanel\Controllers\RunController;
use Localpanel\Controllers\SiteController;

require_once "config.php";

if(DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$app = new App();
$assets = new FilesystemHandler(__DIR__ . '/src/Views/assets/');

$app->redirect('/database', DB_URL);


$app->get('/assets', $assets);
$app->get('/assets/{path:.+}', $assets);

//pages
$app->get('/', [new HomeController(), 'render']);
//$app->get('/manage/{name})', [new ManageController(), 'render']);

$app->get('/debug', function () {
    phpinfo();
    exit;
});

//api endpoints
$app->get('/api/site/create', [new SiteController(), 'create']);
//$app->post('/api/site/delete', [new SiteController(), 'render']);
//$app->post('/api/site/update', [new SiteController(), 'render']);
$app->get('/api/site/get', [new SiteController(), 'get']);

// handles /bin/localpanel commands
$app->post('/api/run/{command}', [new RunController(), 'render']);

$app->run();


