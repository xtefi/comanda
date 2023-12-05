<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $param = $request->getParsedBody();

      // LA MESA SE CREA VACIA
      $mesa = new Mesa();
      $mesa->estado = "CERRADA";
      $mesa->nombreCliente = "";
      $mesa->idUsuario = 0;
      $mesa->codigo = "";
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
      $id = $args['id'];
      $estado = strtoupper($parametros['estado']);
      $codigo = $parametros['codigo'];

      if($estado === "COMIENDO" || $estado === "PAGANDO"){
        Mesa::modificarEstado($estado, $id, $codigo);
        $payload = json_encode(array("mensaje" => "Mesa modificada con exito"));
      }else{
        $payload = json_encode(array("mensaje" => "ERROR, no se puede cambiar estado a CERRADA"));
      }
      


      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function IniciarMesa($request, $response, $args){
      $parametros = $request->getParsedBody();
      $id = $args['id'];
      $idUsuario = $parametros['idUsuario'];
      $estado = 'ESPERANDO';
      $nombreCliente = strtoupper($parametros['nombreCliente']);
      $codigo = Mesa::generarCodigoUnico();

      //SE PUEDE CARGAR LA FOTO      
      $fotoReserva = $_FILES['fotoCliente']['tmp_name'];
      $carpetaFoto = 'C:/Users/54113/Desktop/ImagenesDeClientes/';
      $nombreFoto = $codigo . "-" . $nombreCliente . "-" ;
      $extensionFoto = $_FILES['fotoCliente']['type'];
      $tamanoFoto = $_FILES['fotoCliente']['size'];
      $ruta_destino = $carpetaFoto . $nombreFoto . ".jpg";
      if(isset($fotoReserva)) {
        Mesa::guardarFoto($extensionFoto, $tamanoFoto, true, $carpetaFoto, $fotoReserva, $ruta_destino);
      }
      
      Mesa::modificarMesa($idUsuario, $estado, $nombreCliente, $id, $codigo);

      $payload = json_encode(array("mensaje" => "Mesa iniciada con exito"));
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');

    }

    public function BorrarUno($request, $response, $args)
    {
      $id = $args['id'];
      $param = $request->getQueryParams();
      Mesa::cerrarMesa($id);

      $payload = json_encode(array("mensaje" => "Mesa cerrada"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}