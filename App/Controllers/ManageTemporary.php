<?php
declare(strict_types=1);

namespace App\Controllers;

use App\AppConfig;
use App\Models\TemporaryAccount;
use Sdk\Http\Request;
use Sdk\Http\Response;
use Sdk\Utils\Random;

final class ManageTemporary
{
    public static function renderAccounts(Request $request, Response $response, array $args): Response
    {
        $tempAccounts = TemporaryAccount::getAll();
        $response->createView('ViewTemporary.php')
            ?->setProperty('accounts', $tempAccounts);

        return $response;
    }

    public static function renderManage(Request $request, Response $response, array $args): Response
    {
        $account = TemporaryAccount::fromUsername($args['username']);

        if ($account === null) {
            $response->addHeader('Location', '/temporary');
            return $response;
        }

        $response->createView('Management/Temporary.php')
            ?->setProperty('account', $account);
        return $response;
    }

    public static function createAccount(Request $request, Response $response, array $args): Response
    {
        $username = $request->getPost('username');

        if ($username === null || $username === '') {
            do {
                $username = Random::stringSafe(24);
            } while (TemporaryAccount::exists($username));
        }

        $response->addHeader('Location', '/temporary');

        $expirationMinutes = $request->getPost('expirationMinutes');

        if (!filter_var($expirationMinutes, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 30,
                'max_range' => AppConfig::MAX_TEMPORARY_EMAIL_MINUTES
            ]
        ])) {
            return $response;
        }

        $password = Random::stringSafe(32);

        TemporaryAccount::create($username, $password, intval($expirationMinutes));

        return $response;
    }
}
