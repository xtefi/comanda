<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/permisosMiddleware.php';
require_once './middlewares/logsMiddleware.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/LogsController.php';
require_once './utils/AutentificadorJWT.php';
require_once './utils/archivosCsv.php';


// Load ENV
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->group('/ingresar', function (RouteCollectorProxy $group) {
    $group->post('/login', \UsuarioController::class . ':Login'); 
    $group->post('/clientes', \PedidoController::class . ':mostrarPedidoAlCliente');
})->add(\logsMiddleware::class . ':LogOperacion');

$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->post('/{usuario}', \UsuarioController::class . ':ModificarUno');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
  })->add(\permisosMiddleware::class . ':verificarRolSocio'); // solo el rol socio puede RWE en usuarios

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->post('/{id}', \ProductoController::class . ':ModificarUno');
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->post('[/]', \PedidoController::class . ':CargarUno');
    $group->post('/{id}', \PedidoController::class . ':ModificarUno');
    $group->delete('[/]', \PedidoController::class . ':BorrarUno');
})->add(\logsMiddleware::class . ':LogOperacion');

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos')->add(\permisosMiddleware::class . ':verificarRolSocioMozo');
    $group->get('/{mesa}', \MesaController::class . ':TraerUno')->add(\permisosMiddleware::class . ':verificarRolSocioMozo');
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(\permisosMiddleware::class . ':verificarRolSocioMozo');
    $group->post('/{id}', \MesaController::class . ':ModificarUno')->add(\permisosMiddleware::class . ':verificarRolSocioMozo');
    $group->post('/iniciar/{id}', \MesaController::class . ':IniciarMesa')->add(\permisosMiddleware::class . ':verificarRolSocioMozo');
    $group->delete('/{id}', \MesaController::class . ':BorrarUno')->add(\permisosMiddleware::class . ':verificarRolSocio');
})->add(\logsMiddleware::class . ':LogOperacion');

$app->group('/encuesta', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EncuestaController::class . ':TraerTodos');
    $group->get('/{valor}', \EncuestaController::class . ':MejoresPeoresComentarios'); // mejores - peores
    $group->post('[/]', \EncuestaController::class . ':CargarUno');
});

$app->group('/logs', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':MostrarLogs');
})->add(\permisosMiddleware::class . ':verificarRolSocio');

$app->group('/archivos', function (RouteCollectorProxy $group) {
    $group->get('/crearCsv', \LogsController::class . ':EndPointEscribirCsv');
    $group->get('/crearPdf', \LogsController::class . ':EndPointEscribirPdf');
});

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();





?>