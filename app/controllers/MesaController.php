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
        $idPedido = $param['idPedido'];
        $idPedido = $param['idUsuario'];


        // Creamos la mesa
        $mesa = new Mesa();
        $mesa->estado = $estado;
        $mesa->nombreCliente = $nombreCliente;
        $mesa->idPedido = $idPedido;
        $mesa->idUsuario = $idUsuario;
        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos mesa por ID
        $id = $args['id'];
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

    }

    public function BorrarUno($request, $response, $args)
    {

    }
}