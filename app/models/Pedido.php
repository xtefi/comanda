<?php

class Pedido{
    public $id;    // 5 CARACTERES
    public $codigo;
    public $idMesa;
    public $idProducto;
    public $idUsuario;
    public $horaPedido;
    public $tiempoPreparacion;
    public $estado; // PENDIENTES - PREPARACION - LISTO - ENTREGADO - CANCELADO

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query="INSERT INTO pedidos (codigo, idMesa, idProducto, idUsuario, horaPedido, tiempoPreparacion, estado) VALUES ('$this->codigo','$this->idMesa', '$this->idProducto', '$this->idUsuario', '$this->horaPedido', '$this->tiempoPreparacion', '$this->estado')";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos($codigo='')
    {
        if(!empty($codigo)){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE codigo = ?");
            $consulta->bindParam(1, $codigo);
            $consulta->execute();    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }else{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
            $consulta->execute();    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }
    }

    public static function obtenerPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }


    public static function modificarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query="UPDATE pedidos SET tiempoPreparacion = ?, horaPedido = ?, estado = ? WHERE id = ?";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $consulta->bindParam(1, $tiempoPreparacion);
        $consulta->bindParam(2, $horaPedido);
        $consulta->bindParam(3, $estado);
        $consulta->bindParam(4, $id);
        $consulta->execute();
    }

    public static function cancelarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', "CANCELADO", PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function loginCliente($codigo, $idMesa){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos INNER JOIN productos ON pedidos.idProducto = productos.id WHERE codigo = :codigo AND idMesa = :idMesa");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');    
    }

    //# Si al mozo le hacen un pedido de un vino, una cerveza y unas empanadas, deberían los
// empleados correspondientes ver estos pedidos en su listado de “pendientes”, con la opción de
// tomar una foto de la mesa con sus integrantes y relacionarlo con el pedido.


}


?>