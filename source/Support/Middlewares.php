<?php

namespace Source\Support;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

abstract class Middlewares
{
    public static function auth(Request $request, RequestHandler $handler)
    {
        $response = $handler->handle($request);
        //$existingContent = (string) $response->getBody();

        //$token = explode(' ',$request->getHeader('authorization')[0])[1];
        $headers = getallheaders();

        $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

        /*if(is_null($token))
        {
            echo json_encode([
                "error"  => "Not authorized",
                "message" => "Token not provided"
            ]);
            exit();
        }*/
        echo 'Middleware deve acontecer antes!!!<br/>';

        //$response = new Response();
        //$response->getBody()->write('<br/>BEFORE');

        return $response;
    }
}
