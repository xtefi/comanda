<?php
require_once './models/Usuario.php';
require_once './models/log.php';
require_once './utils/AutentificadorJWT.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $param = $request->getParsedBody();
        $usuario = $param['usuario'];
        $clave = $param['clave'];
        $rol = $param['rol'];
        $estado = $param['estado'];
        $fechaAlta = $param['fechaAlta'];
        $fechaBaja = $param['fechaBaja'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->rol = $rol;
        $usr->estado = $estado;
        $usr->fechaAlta = $fechaAlta;
        $usr->fechaBaja = $fechaBaja;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $rol = $parametros['rol'];
        $estado = $parametros['estado'];
        Usuario::modificarUsuario($usuario, $clave, $rol, $estado, $id);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $args['id'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    // SE LOGUEA CON USUARIO Y CONTRASEÑA - VERIFICA QUE ESTÉ DISPONIBLE - NO DESPEDIDO/NO LICENCIA
    public function Login($request, $response, $args) {
      $parametros = $request->getParsedBody();
      $user =  $parametros['usuario'];
      $pass =  $parametros['password'];
  
      if (isset($user) && isset($pass)) {
        $usuario = Usuario::obtenerUsuario($user); 
        if (!empty($usuario) && ($pass == $usuario->clave) && (strtolower($usuario->estado) === "disponible")) {  
          $jwt = AutentificadorJWT::CrearToken($usuario);  
          $message = [
            'Autorizacion' => $jwt,
            'Status' => 'Login success'
          ];
        } else {
          $message = [
            'Autorizacion' => 'Denegate',
            'Status' => 'Login failed'
          ];
        }
      }  
      $payload = json_encode($message);  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function MostrarLogs($request, $response, $args){
      $parametros = $request->getQueryParams();
      $filtro =  $parametros['filtro'];
      $opcion =  $parametros['opcion'];
      if(empty($opcion) && isset($filtro)){
        $lista = Log::filtroLogsEmpleados($filtro, "");
      }elseif(isset($filtro)){
        $lista = Log::filtroLogsEmpleados($filtro, $opcion);
      }
      $payload = json_encode(array("Logs" => $lista));
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');        
    // a- Los días y horarios que se ingresaron al sistema.
    // b- Cantidad de operaciones de todos por sector.
    // c- Cantidad de operaciones de todos por sector, listada por cada empleado.
    // d- Cantidad de operaciones de cada uno por separado.
    // e- Posibilidad de dar de alta a nuevos, suspenderlos o borrarlos.
    }
}