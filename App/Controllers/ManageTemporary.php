<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\TemporaryAccount;
use Sdk\Http\Entities\StatusCode;
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
        $username = $args['username'];

        if (!TemporaryAccount::exists($username)) {
            $response->setStatusCode(StatusCode::NOT_FOUND);
            $response->addHeader('Location', '/temporary');
            return $response;
        }

        $response->createView('Management/Temporary.php')
            ?->setProperty('username', $username);
        return $response;
    }
}
