<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $param = $request->getParsedBody();
      $estado = $param['estado'];
      $nombreCliente = $param['nombreCliente'];
      $idUsuario = $param['idUsuario'];

      // Creamos la mesa
      $mesa = new Mesa();
      $mesa->estado = $estado;
      $mesa->nombreCliente = $nombreCliente;
      $mesa->idUsuario = $idUsuario;
      $mesa->codigo = Mesa::generarCodigoUnico();
      $mesa->crearMesa();
      $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)// Busca una mesa por ID
    {
      $id = $args['mesa'];
      $mesa = Mesa::obtenerMesa($id);
      $payload = json_encode($mesa);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Mesa::obtenerTodos();
      $payload = json_encode(array("listaMesa" => $lista));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $id = $parametros['id'];
      $idUsuario = $parametros['idUsuario'];
      $estado = $parametros['estado'];
      $nombreCliente = $parametros['nombreCliente'];
      $codigo = $parametros['codigo'];
      Mesa::modificarMesa($idUsuario, $estado, $nombreCliente, $id, $codigo);

      $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function IniciarMesa($request, $response, $args){
      $parametros = $request->getParsedBody();
      $id = $args['id'];
      $idUsuario = $parametros['idUsuario'];
      $estado = 'ESPERANDO';
      $nombreCliente = $parametros['nombreCliente'];
      $codigo = Mesa::generarCodigoUnico();

      Mesa::modificarMesa($idUsuario, $estado, $nombreCliente, $id, $codigo);
      $payload = json_encode(array("mensaje" => "Se ha iniciado la mesa, codigo: ". $codigo));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');

    }

    public function BorrarUno($request, $response, $args)
    {
      $id = $args['id'];
      $param = $request->getQueryParams();
      $estado = strtoupper($param['estado']);
      $mesa = Mesa::cerrarMesa($id, $estado);

      $payload = json_encode(array("mensaje" => "Mesa cerrada"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}