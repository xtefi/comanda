<?php

class Mesa{
    public $id;
    public $estado; // CLIENTE ESPERANDO - CLIENTE COMIENDO - CLIENTE PAGANDO - CERRADA
    public $nombreCliente;
    public $idPedido;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado, nombreCliente, idPedido) VALUES ('$this->estado', '$this->nombreCliente', '$this->idPedido')");
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, nombreCliente, idPedido FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, nombreCliente, idPedido FROM mesa WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa()
    {

    }

    public static function borrarMesa($mesa)
    {

    }
}


?>