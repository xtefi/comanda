<?php

class Pedido{
    public $id;    // 5 CARACTERES
    public $codigo;
    public $idMesa;
    public $idProducto;
    public $idUsuario;
    public $horaPedido;
    public $tiempo;
    public $estado; // PENDIENTES - PREPARACION - LISTO - ENTREGADO - CANCELADO

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query="INSERT INTO pedidos (codigo, idMesa, idProducto, idUsuario, horaPedido, tiempo, estado) VALUES ('$this->codigo','$this->idMesa', '$this->idProducto', '$this->idUsuario', '$this->horaPedido', '$this->tiempo', '$this->estado')";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos($codigo='')
    {
        if(!empty($codigo)){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, idMesa, idProducto, idUsuario, horaPedido, tiempo, estado FROM pedidos WHERE codigo = ?");
            $consulta->bindParam(1, $codigo);
            $consulta->execute();    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }else{
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, idMesa, idProducto, idUsuario, horaPedido, tiempo, estado FROM pedidos");
            $consulta->execute();    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }
    }

    public static function obtenerPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigo, idMesa, idProducto, idUsuario, horaPedido, tiempo, estado FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPorCodigoYMesa($codigo, $idMesa)
    {
        if(!empty($codigo) && $idMesa != null){
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query="SELECT pedido.id, pedido.codigo, pedido.idMesa, pedido.idProducto, pedido.idUsuario, pedido.horaPedido, pedido.tiempo, pedido.estado 
                    FROM pedidos 
                    JOIN mesa ON pedido.idMesa = mesa.id 
                    WHERE pedido.codigo = ? AND pedido.idMesa = ?";
            $consulta = $objAccesoDatos->prepararConsulta($query);
            $consulta->bindParam(1, $codigo);
            $consulta->bindParam(2, $idMesa);
            $consulta->execute();    
        }
        return $consulta->fetchObject('Pedido');
    }

    public static function modificarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query="UPDATE pedidos SET tiempo = ?, horaPedido = ?, estado = ? WHERE id = ?";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $consulta->bindParam(1, $usuario);
        $consulta->bindParam(2, $clave);
        $consulta->bindParam(3, $rol);
        $consulta->bindParam(4, $estado);
        $consulta->bindParam(5, $id);
        $consulta->execute();
    }

    public static function cancelarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', "CANCELADO", PDO::PARAM_INT);
        $consulta->execute();
    }


    //# Si al mozo le hacen un pedido de un vino, una cerveza y unas empanadas, deberían los
// empleados correspondientes ver estos pedidos en su listado de “pendientes”, con la opción de
// tomar una foto de la mesa con sus integrantes y relacionarlo con el pedido.


}


?>