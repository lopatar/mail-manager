<?php

namespace App\Controllers;

use Sdk\Http\Request;
use Sdk\Http\Response;

final class ImapView
{
    public static function fetchInbox(Request $request, Response $response, array $args): Response
    {
        $username = $args['username'];

        return $response;
    }
}