<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args) // CREA UN PRODUCTO
    {
        $param = $request->getParsedBody();
        $tipo = $param['tipo'];
        $descripcion = $param['descripcion'];

        // Creamos el usuario
        $prd = new Producto();
        $prd->tipo = $tipo;
        $prd->descripcion = $descripcion;
        $prd->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args) // Trae un producto por ID
    {
        $id = $args['id'];
        $producto = Producto::obtenerProducto($id);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) // Trae lista de productos
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProducto" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args) // Modifica un producto por ID
    {
      $parametros = $request->getParsedBody();
      $id = $args['id'];
      $descripcion = $parametros['descripcion'];
      $tipo = $parametros['tipo'];
      Producto::modificarProducto($tipo, $descripcion, $id);

      $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) // Elimina un producto de la base de datos
    {
      $id = $args['id'];
      Producto::borrarProducto($id);
      $payload = json_encode(array("mensaje" => "Producto eliminado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}