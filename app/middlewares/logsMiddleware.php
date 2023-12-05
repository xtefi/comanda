<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Log.php';
require_once './utils/AutentificadorJWT.php';

class logsMiddleware
{
    public static function LogOperacion(Request $request, RequestHandler $handler): Response
    {
        //Logueo de acciones de las acciones(cambio de estados de pedidos, mesas, usuarios)
        $response = new Response();
        $response = $handler->handle($request);
        $path = $request->getUri()->getPath();
        $metodo = $request->getMethod();
        $http_status_code = $response->getStatusCode();
        $param = $request->getParsedBody();

        $jwtHeader = $request->getHeaderLine('Authorization');
        $tokenWithoutBearer = str_replace('Bearer ', '', $jwtHeader);
        $usuario = AutentificadorJWT::ObtenerData($tokenWithoutBearer);

        if(str_contains($path, 'login'))                           // LOGIN
        { 
            $request = $request->getParsedBody();
            $id_usuario = $request['id'];
            $usuario = $request['usuario'];
            Log::Add($id_usuario, $usuario, 'LOGIN', 'LOGUEO-SISTEMA', "", "");

        }
        elseif(str_contains($path, 'mesas')){                       // CLIENTES
            if($metodo == 'POST'){
                Log::Add($usuario->id, $usuario->usuario, 'CLIENTES', 'CARGAR', json_encode($param), $response->getBody());
            }elseif($metodo == 'PUT'){
                Log::Add($usuario->id, $usuario->usuario, 'CLIENTES', 'MODIFICAR', json_encode($param), $response->getBody());
            }elseif($metodo == 'GET'){
                Log::Add($usuario->id, $usuario->usuario, 'CLIENTES', 'OBTENER', json_encode($param), $response->getBody());                  
            }elseif($metodo == 'DELETE'){
                Log::Add($usuario->id, $usuario->usuario, 'CLIENTES', 'ELIMINAR', json_encode($param), $response->getBody());
            }else{ 
                Log::Add($usuario->id, $usuario->usuario, 'CLIENTES-ERROR','ERROR', json_encode($param), $response->getBody());
            }        

        }elseif(str_contains($path, 'pedidos')){                       // RESERVAS
            if($metodo == 'POST'){
                Log::Add($usuario->id, $usuario->usuario, 'RESERVAS', 'CARGAR', json_encode($param), $response->getBody());
            }elseif($metodo == 'PUT'){
                Log::Add($usuario->id, $usuario->usuario, 'RESERVAS', 'MODIFICAR', json_encode($param), $response->getBody());
            }elseif($metodo == 'GET'){
                Log::Add($usuario->id, $usuario->usuario, 'RESERVAS', 'OBTENER', json_encode($param), $response->getBody());                   
            }elseif($metodo == 'DELETE'){
                Log::Add($usuario->id, $usuario->usuario, 'RESERVAS', 'ELIMINAR', json_encode($param), $response->getBody());
            }else { 
                Log::Add($usuario->id, $usuario->usuario, 'RESERVAS-ERROR', 'ERROR', json_encode($param), $response->getBody());
            } 
        }
        return $response;

    }
}