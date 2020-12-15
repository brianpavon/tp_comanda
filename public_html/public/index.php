<?php
//echo 'hola';
//die();

/*use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;*/

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Config\Database;
//use App\Modelos\Usuario;
use App\Controllers\UsuarioController;
use App\Controllers\PedidoController;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\AuthMiddlewareAdmin;
use App\Middlewares\AuthMiddlewareMozoSocio;
use App\Middlewares\AuthMiddlewareEmpleado;


require __DIR__ . '/../vendor/autoload.php';

new Database;

$app = AppFactory::create();
$app->add(new JsonMiddleware);
$app->addRoutingMiddleware();
$app->setBasePath('/public');

$app->post('/registro',UsuarioController::class.":addOne");
$app->post('/login',UsuarioController::class.":loginUsuario");

$app->group('/pedidos',function(RouteCollectorProxy $group)
{
    //van a poder abrir una mesa un socio o un mozo
    $group->post('[/]',PedidoController::class.":agregarPedido")->add(new AuthMiddlewareMozoSocio);
    //mostrar pendientes de un cliente
    $group->get('[/]',PedidoController::class.":mostrarPedidosCliente");
    //va el put, cambio de estado;
    $group->put('/preparacion',PedidoController::class.":prepararPedidos")->add(new AuthMiddlewareEmpleado);

});
$app->group('/mesas',function(RouteCollectorProxy $group)
{    
    
    $group->put('/{idMesa}',PedidoController::class.":servirMesas")->add(new AuthMiddlewareMozoSocio);
    $group->get('/{idMesa}',PedidoController::class.":estadoMesa")->add(new AuthMiddlewareMozoSocio);
    $group->post('/cerrar',PedidoController::class.":cerrarMesa")->add(new AuthMiddlewareAdmin);

});
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
//$app->add(new JsonMiddleware);

$app->run();