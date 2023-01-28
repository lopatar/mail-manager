<?php
declare(strict_types=1);

namespace App\Middleware;

use Sdk\Http\Request;
use Sdk\Http\Response;
use Sdk\Middleware\Interfaces\IMiddleware;
use Sdk\Render\View;

class HtmlHeader implements IMiddleware
{

    public function execute(Request $request, Response $response, array $args): Response
    {
        (new View('Header.html'))->render();
        return $response;
    }
}