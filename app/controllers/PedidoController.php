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

      // El pedido se crea como PENDIENTE 
      $pedido = new Pedido();
      $pedido->codigo = Pedido::generarCodigoUnico();
      $pedido->idMesa = $idMesa;
      $pedido->idProducto = $idProducto;
      $pedido->idUsuario = $idUsuario;
      $pedido->horaPedido = '0';
      $pedido->tiempoPreparacion = '0';
      $pedido->tiempoRestante = '0';
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
    
    public function ModificarUno($request, $response, $args) // Modifica un pedido por ID
    {
      $parametros = $request->getParsedBody();
      $id = $args['id'];
      $horaPedido = $parametros['horaPedido'];
      $tiempoPreparacion = $parametros['tiempoPreparacion'];
      $estado = $parametros['estado'];
      Producto::modificarProducto($tipo, $descripcion, $id);

      $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

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