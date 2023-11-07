<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $param = $request->getParsedBody();
        $estado = $param['estado'];
        $idMesa = $param['idMesa'];
        $idProductos = $param['idProductos'];
        $tiempo = $param['tiempo'];

        // Creamos el pedido
        $pedido = new Pedido();
        $pedido->estado = $estado;
        $pedido->idMesa = $idMesa;
        $pedido->idProductos = $idProductos;
        $pedido->tiempo = $tiempo;
        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $pdd = $args['pedido'];
        $pedido = Pedido::obtenerUsuario($pdd);
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