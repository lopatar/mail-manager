<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Middleware\HtmlHeader;
use App\SdkConfig;
use Sdk\App;

$config = new SdkConfig();
$app = new App($config);

$htmlHeader = new HtmlHeader();
$app->addMiddleware($htmlHeader);

$app->view('/', 'Home.html');

$app->get('/temporary', 'ManageTemporary::render');
$app->get('/permanent', 'ManagePermanent::render');

$app->run();