<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\AppConfig;
use App\Middleware\HtmlHeader;
use App\SdkConfig;
use App\Utils\Authentication;
use Sdk\App;
use Sdk\Middleware\HttpBasicAuth;

$config = new SdkConfig();
$app = new App($config);

$authenticatedUsers = Authentication::getUsers();

$basicAuth = new HttpBasicAuth($authenticatedUsers);
$htmlHeader = new HtmlHeader();

$app->addMiddleware($basicAuth);
$app->addMiddleware($htmlHeader);

date_default_timezone_set(AppConfig::DEFAULT_TIMEZONE);

$app->view('/', 'Home.html');

$app->get('/permanent', 'ManagePermanent::renderAccounts');
$app->get('/temporary', 'ManageTemporary::renderAccounts');

$app->post('/api/permanent/create', 'ManagePermanent::createAccount');
$app->post('/api/permanent/delete', 'ManagePermanent::deleteAccount');

$app->post('/api/permanent/rotate-password', 'ManagePermanent::rotatePassword');
$app->post('/api/temporary/rotate-password', 'ManageTemporary::rotatePassword');

$app->post('/api/temporary/create', 'ManageTemporary::createAccount');
$app->post('/api/temporary/delete', 'ManageTemporary::deleteAccount');


$app->run();