<?php
declare(strict_types=1);

namespace App\Controllers;

use Sdk\Http\Request;
use Sdk\Http\Response;

final class ManageTemporary
{
    public static function renderAccounts(Request $request, Response $response, array $args): Response
    {
        $response->createView('Temporary.php');
        return $response;
    }
}