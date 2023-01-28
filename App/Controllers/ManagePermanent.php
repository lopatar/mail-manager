<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\PermanentAccount;
use Sdk\Http\Request;
use Sdk\Http\Response;

final class ManagePermanent
{
    public static function render(Request $request, Response $response, array $args): Response
    {
        $permanentAccounts = PermanentAccount::getAll();
        $response->createView('Permanent.php')
            ?->setProperty('accounts', $permanentAccounts);

        return $response;
    }
}