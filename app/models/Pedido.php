<?php

class Pedido{
    public $id;    // 5 CARACTERES
    public $estado; // PENDIENTES - EN PREPARACION - LISTO PARA SERVIR - CANCELADO
    public $idMesa;
    public $idProducto;
    public $tiempo;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (estado, idMesa, idProductos, tiempo) VALUES ('$this->estado', '$this->idMesa', '$this->idProductos', '$this->tiempo')");
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, idMesa, idProductos, tiempo FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, estado, idMesa, idProductos, tiempo FROM pedidos WHERE id = :id");
        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('pedido');
    }


}


?>