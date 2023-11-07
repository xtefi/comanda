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

require_once './db/dataAccess.php';
// require_once './middlewares/Logger.php';

require_once './controllers/UserController.php';

// Load ENV
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group('/users', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UserController::class . ':GetAll');
    $group->get('/{user}', \UserController::class . ':GetOne');
    $group->post('[/]', \UserController::class . ':NewUser');
  });


$app->get('[/]', function (Request $request, Response $response) {    
   $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
   $response->getBody()->write($payload);
   return $response->withHeader('Content-Type', 'application/json');
});  

// $app->get('/', function (Request $request, Response $response, $args) {
//     $response->getBody()->write("Hello world!");
//     return $response;
// });

$app->run();





?>