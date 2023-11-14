<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $param = $request->getParsedBody();
        $idMesa = $param['idMesa'];
        $idProductos = $param['idProductos'];
        $idUsuario = $param['idUsuario'];
        $tiempo = $param['tiempo'];

        // El pedido se crea como PENDIENTE con la hora actual como horaPedido
        $pedido = new Pedido();
        $pedido->idMesa = $idMesa;
        $pedido->idProductos = $idProductos;
        $pedido->idUsuario = $idUsuario;
        $pedido->horaPedido = date("H:i");
        $pedido->tiempoPreparacion = $tiempo;
        $pedido->estado = "PENDIENTE";
        $pedido->crearPedido();

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
        $lista = Pedido::obtenerTodos();
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

    }
}