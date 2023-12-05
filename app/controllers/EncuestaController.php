<?php
require_once './models/Encuesta.php';

class EncuestaController extends Encuesta
{
    public function CargarUno($request, $response, $args) // CREA ENCUESTA
    {
        $param = $request->getParsedBody();
        $mesa = $param['mesa'];
        $restaurante = $param['restaurante'];
        $mozo = $param['mozo'];
        $cocinero = $param['cocinero'];
        $experiencia = $param['experiencia'];
        $codigo = $param['codigo'];

        // Creamos la encuesta
        $encuesta = new Encuesta();
        $encuesta->mesa = $mesa;
        $encuesta->restaurante = $restaurante;
        $encuesta->mozo = $mozo;
        $encuesta->cocinero = $cocinero;
        $encuesta->experiencia = $experiencia;
        $encuesta->codigo = $codigo;
        $encuesta->fecha = date('Y-m-d H:i');
        $encuesta->crearEncuesta();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // Trae lista de productos
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MejoresPeoresComentarios($request, $response, $args) // Trae lista de productos
    {
        $valor = $args['valor'];
        if($valor === "mejores"){
            $lista = Encuesta::obtenerMejores();
            $payload = json_encode(array("listaProducto" => $lista));
        }elseif($valor === "peores"){
            $lista = Encuesta::obtenerPeores();
            $payload = json_encode(array("listaProducto" => $lista));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}