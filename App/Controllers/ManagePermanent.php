<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\PermanentAccount;
use Sdk\Http\Request;
use Sdk\Http\Response;

final class ManagePermanent
{
    public static function renderAccounts(Request $request, Response $response, array $args): Response
    {
        $permanentAccounts = PermanentAccount::getAll();
        $response->createView('ViewPermanent.php')
            ?->setProperty('accounts', $permanentAccounts);

        return $response;
    }

    public static function renderManage(Request $request, Response $response, array $args): Response
    {
        $account = PermanentAccount::fromUsername($args['username']);

        if ($account === null) {
            $response->addHeader('Location', '/permanent');
            return $response;
        }

        $response->createView('Management/Permanent.php')
            ?->setProperty('account', $account);
        return $response;
    }
}