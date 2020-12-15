<?php
namespace App\Middlewares;

//use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Controllers\UsuarioController;
//use \Firebase\JWT\JWT;


class AuthMiddlewareAdmin
{
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        $token = $request->getHeaderLine('token');
        
        if (!UsuarioController::PermitirPermisos($token,'socio'))
        {
            $response = new Response();
            $response->getBody()->write("Usuario sin permiso");
            
            return $response->withStatus(403);
        } else {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);
            return $resp;
        }
    }
}