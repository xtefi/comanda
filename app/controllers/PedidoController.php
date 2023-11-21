<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $param = $request->getParsedBody();
      $codigo = $param['codigo'];
      $idMesa = $param['idMesa'];
      $idProducto = $param['idProducto'];
      $idUsuario = $param['idUsuario'];

      // El pedido se crea como PENDIENTE con la hora actual como horaPedido
      $pedido = new Pedido();
      $pedido->codigo = $codigo;
      $pedido->idMesa = $idMesa;
      $pedido->idProducto = $idProducto;
      $pedido->idUsuario = $idUsuario;
      $pedido->horaPedido = date("H:i");
      $pedido->tiempoPreparacion = '00:00';
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
      // Buscamos pedido por id
      $pdd = $args['pedido'];

      $pedido = Pedido::obtenerPedido($pdd);
      $payload = json_encode($pedido);

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $param = $request->getQueryParams();
      $codigo = $param['codigo'];
      $lista = Pedido::obtenerTodos($codigo);
      $payload = json_encode(array("listPedidos" => $lista));
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {

    }

    public function BorrarUno($request, $response, $args)
    {
      $parametros = $request->getQueryParams();
      $id = $parametros['id'];
      Pedido::cancelarPedido($id);

      $payload = json_encode(array("mensaje" => "Pedido cancelado"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}