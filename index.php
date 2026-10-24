<?php

require __DIR__ . '/vendor/autoload.php';

use FrameworkX\App;
use FrameworkX\FilesystemHandler;
use Localpanel\Controllers\HomeController;
use Localpanel\Controllers\SiteController;

require_once "config.php";

$app = new App();

if(DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // handles /bin/localpanel commands
    $app->post('/api/run/{command}', [new SiteController(), 'test']);
}

$assets = new FilesystemHandler(__DIR__ . '/src/Views/assets/');
$scripts = new FilesystemHandler(__DIR__ . '/src/Views/scripts/');

$app->redirect('/database', DB_URL);


$app->get('/assets', $assets);
$app->get('/assets/{path:.+}', $assets);

$app->get('/scripts', $scripts);
$app->get('/scripts/{path:.+}', $scripts);

//pages
$app->get('/', [new HomeController(), 'render']);
//$app->get('/manage/{name})', [new ManageController(), 'render']);
//$app->get('/logs', [new LogController(), 'logs']);
//$app->get('/logs/{ip}', [new LogController(), 'logByIp']);

//api endpoints

// /api/site/create/{post data}
$app->post('/api/site/create', [new SiteController(), 'create']);
// /api/site/delete/127.0.0.2
$app->delete('/api/site/delete/{ip}', [new SiteController(), 'delete']);
// /api/site/update/127.0.0.2
$app->put('/api/site/update/{ip}', [new SiteController(), 'update']);
// /api/site/get?name=localpanel
$app->get('/api/site/get', [new SiteController(), 'get']);

$app->get('/info', function () {
    phpinfo();
    exit;
});

$app->run();


