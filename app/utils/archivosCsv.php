<?php

class Archivos{
    public $archivo_csv = "./archivos.csv";
    public function Cargar($request, $response, $args)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query= "LOAD DATA INFILE '$archivo_csv' INTO TABLE productos FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n'";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();
        
        $payload = json_encode(array("mensaje" => "Archivo cargado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}







?>