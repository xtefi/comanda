<?php

class Mesa{
    public $id;
    public $idUsuario;
    public $idPedido;
    public $estado; // ESPERANDO - COMIENDO - PAGANDO - CERRADA
    public $nombreCliente;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (idUsuario, idPedido, estado, nombreCliente) VALUES ('$this->idUsuario', '$this->idPedido', '$this->estado', '$this->nombreCliente')");
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, idPedido, estado, nombreCliente FROM mesas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, idPedido, estado, nombreCliente FROM mesa WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query="UPDATE mesas SET estado = ? WHERE id = ?";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $consulta->bindParam(1, $estado);
        $consulta->bindParam(5, $id);
        $consulta->execute();
    }

    public static function cerrarMesa($id, $estado)  // SOLO PARA ADMIN - ESTADO PAGANDO O CERRADA
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query="UPDATE mesas SET estado = ?, idUsuario = ?, idPedido = ?, nombreCliente = ? WHERE id = ?";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $consulta->bindParam(1, $estado);
        $consulta->bindParam(2, "");
        $consulta->bindParam(3, "");
        $consulta->bindParam(4, "");
        $consulta->bindParam(5, $id);
        $consulta->execute();
    }

}


?>