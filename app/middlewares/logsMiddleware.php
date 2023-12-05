<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Log.php';
require_once './models/Usuario.php';
require_once './utils/AutentificadorJWT.php';

class logsMiddleware
{
    public static function LogOperacion(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $response = $handler->handle($request);
        $path = $request->getUri()->getPath();
        $metodo = $request->getMethod();
        $http_status_code = $response->getStatusCode();
        $param = $request->getParsedBody();


        if(str_contains($path, 'login'))                           // LOGIN
        { 
            $request = $request->getParsedBody();
            $clave = $request['password'];
            $user = $request['usuario'];
            $usuario=Usuario::obtenerUsuario($user);
            Log::Add($usuario->id, $usuario->usuario, $usuario->rol, 'LOGIN', 'LOGUEO-SISTEMA', "", "");
        }

        //$id_usuario, $usuario, $rol, $entidad, $operacion, $datos_operacion, $datos_resultado_operacion

        else{
            $jwtHeader = $request->getHeaderLine('Authorization');
            $tokenWithoutBearer = str_replace('Bearer ', '', $jwtHeader);
            $usuario = AutentificadorJWT::ObtenerData($tokenWithoutBearer);

            if(str_contains($path, 'mesas')){                       // MESAS
                if($metodo == 'POST'){
                    Log::Add($usuario->id, $usuario->usuario, $usuario->rol, 'MESAS', 'CARGAR', json_encode($param), $response->getBody());
                }elseif($metodo == 'PUT'){
                    Log::Add($usuario->id, $usuario->usuario, $usuario->rol, 'MESAS', 'INICIAR', json_encode($param), $response->getBody());
                }elseif($metodo == 'GET'){
                    Log::Add($usuario->id, $usuario->usuario, $usuario->rol, 'MESAS', 'OBTENER', json_encode($param), $response->getBody());                  
                }elseif($metodo == 'DELETE'){
                    Log::Add($usuario->id, $usuario->usuario, $usuario->rol, 'MESAS', 'ELIMINAR', json_encode($param), $response->getBody());
                }else{ 
                    Log::Add($usuario->id, $usuario->usuario, $usuario->rol, 'MESAS-ERROR','ERROR', json_encode($param), $response->getBody());
                }        
    
            }elseif(str_contains($path, 'pedidos')){                       // RESERVAS
                if($metodo == 'POST'){
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol, 'PEDIDOS', 'CARGAR', json_encode($param), $response->getBody());
                }elseif($metodo == 'GET'){
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol,'PEDIDOS', 'OBTENER', json_encode($param), $response->getBody());                   
                }elseif($metodo == 'DELETE'){
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol, 'PEDIDOS', 'ELIMINAR', json_encode($param), $response->getBody());
                }else { 
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol, 'PEDIDOS-ERROR', 'ERROR', json_encode($param), $response->getBody());
                } 
           }elseif(str_contains($path, 'productos')){                       // RESERVAS
                if($metodo == 'POST'){
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol, 'PRODUCTOS', 'CARGAR', json_encode($param), $response->getBody());
                }elseif($metodo == 'GET'){
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol,'PRODUCTOS', 'OBTENER', json_encode($param), $response->getBody());                   
                }elseif($metodo == 'DELETE'){
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol, 'PRODUCTOS', 'ELIMINAR', json_encode($param), $response->getBody());
                }else { 
                    Log::Add($usuario->id, $usuario->usuario,  $usuario->rol, 'PRODUCTOS-ERROR', 'ERROR', json_encode($param), $response->getBody());
                } 
            }
        }
        
        return $response;

    }
}