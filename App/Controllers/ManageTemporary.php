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

    public static function createAccount(Request $request, Response $response, array $args): Response
    {
        $username = $request->getPost('username');

        if ($username === null || $username === '') {
            do {
                $username = Random::stringSafe(12);
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

    public static function deleteAccount(Request $request, Response $response, array $args): Response
    {
        $username = $request->getPost('username');

        $response->addHeader('Location', '/temporary');

        if ($username === null) {
            return $response;
        }

        $user = TemporaryAccount::fromUsername($username);

        $user?->scheduleDeletion(false);

        return $response;
    }
}
