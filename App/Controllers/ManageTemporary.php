<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\TemporaryAccount;
use Sdk\Http\Request;
use Sdk\Http\Response;

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
}
