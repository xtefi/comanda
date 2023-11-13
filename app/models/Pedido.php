<?php

class Pedido{
    public $id;    // 5 CARACTERES
    public $idMesa;
    public $idProducto;
    public $idUsuario;
    public $horaPedido;
    public $tiempoPreparacion;
    public $estado; // PENDIENTES - PREPARACION - LISTO - ENTREGADO - CANCELADO

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query="INSERT INTO pedidos (idMesa, idProductos, idUsuario, horaPedido, tiempoPreparacion, estado) VALUES ('$this->idMesa', '$this->idProductos', '$this->idUsuario', '$this->horaPedido', '$this->tiempoPreparacion', '$this->estado')";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, idProductos, idUsuario, horaPedido, tiempoPreparacion, estado FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, idProductos, idUsuario, horaPedido, tiempoPreparacion, estado FROM pedidos WHERE id = :id");
        $consulta->bindValue(':pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('pedido');
    }


}


?>