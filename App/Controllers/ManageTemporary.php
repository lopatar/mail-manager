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
        $response->createView('Temporary.php')
            ?->setProperty('accounts', $tempAccounts);

        return $response;
    }
}