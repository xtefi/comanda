<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';
require_once './utils/AutentificadorJWT.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $param = $request->getParsedBody();
      $codigo = $param['codigo'];
      $idMesa = $param['idMesa'];
      $idProducto = $param['idProducto'];
      $idUsuario = $param['idUsuario'];

      // El pedido se crea como PENDIENTE 
      $pedido = new Pedido();
      $pedido->codigo = $codigo;
      $pedido->idMesa = $idMesa;
      $pedido->idProducto = $idProducto;
      $pedido->idUsuario = $idUsuario;
      $pedido->horaPedido = '0';
      $pedido->tiempoPreparacion = '0';
      $pedido->estado = "PENDIENTE";
      $pedido->crearPedido();

      echo $pedido->horaPedido;

      $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      $pdd = $args['pedido'];

      $pedido = Pedido::obtenerPedido($pdd);
      $payload = json_encode($pedido);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)  // TRAE "TODOS" SEGUN ROL DE USUARIO
    {
      // CARGO EL TOKEN
      $jwtHeader = $request->getHeaderLine('Authorization');
      $tokenWithoutBearer = str_replace('Bearer ', '', $jwtHeader);
      $usuario = AutentificadorJWT::ObtenerData($tokenWithoutBearer);

      $rol = strtoupper($usuario->rol);

      $param = $request->getQueryParams();
      $lista = Pedido::obtenerTodos($rol);
      $payload = json_encode(array("listPedidos" => $lista));
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args) // Modifica un pedido por ID
    {
      $parametros = $request->getParsedBody();
      $id = $args['id'];
      $tiempoPreparacion = $parametros['tiempoPreparacion'];
      $estado = $parametros['estado'];
      Pedido::tomarPedido($id, $tiempoPreparacion, $estado);

      $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
      $id = $parametros['id'];
      Pedido::cancelarPedido($id);

      $payload = json_encode(array("mensaje" => "Pedido cancelado"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function mostrarPedidoAlCliente($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
      $idMesa = $parametros['idMesa'];
      $lista = Pedido::loginCliente($codigo, $idMesa);
      $payload = json_encode(array("listaUsuario" => $lista));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}