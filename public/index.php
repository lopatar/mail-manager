<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Middleware\HtmlHeader;
use App\SdkConfig;
use Sdk\App;
use Sdk\Middleware\HttpBasicAuth;

$config = new SdkConfig();
$app = new App($config);

$users = [
    ['username' => 'test', 'password' => '$2y$12$3W.teM8Ph5cp4CwZy9r0D.MtI.RYLW0kSsYpfvTrBio8tBevQCG2m']
];


$basicAuth = new HttpBasicAuth($users);
$htmlHeader = new HtmlHeader();

$app->addMiddleware($basicAuth);
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