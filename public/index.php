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

$app->get('/permanent', 'ManagePermanent::renderAccounts');
$app->get('/temporary', 'ManageTemporary::renderAccounts');

$app->get('/manage/{username}', 'ManagePermanent::renderManage')
    ?->whereParam('username')
    ->setMaxLimit(255)
    ->setShouldEscape(true);

$app->get('/manage-temp/{username}', 'ManageTemporary::renderManage')
    ?->whereParam('username')
    ->setMaxLimit(255)
    ->setShouldEscape(true);

$app->run();