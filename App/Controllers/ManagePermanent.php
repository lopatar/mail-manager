<?php
declare(strict_types=1);

namespace App\Controllers;

use Sdk\Http\Request;
use Sdk\Http\Response;

final class ManagePermanent
{
    public static function render(Request $request, Response $response, array $args): Response
    {
        $response->createView('Permanent.php');
        return $response;
    }
}