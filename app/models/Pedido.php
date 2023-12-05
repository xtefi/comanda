<?php

//require_once './utils/PdfServicio.php';

class Pedido{
    public $id;    // 5 CARACTERES
    public $codigo;
    public $idMesa;
    public $idProducto;
    public $idUsuario;
    public $horaPedido;
    public $tiempoPreparacion;
    public $estado; // PENDIENTE - PREPARACION - LISTO - ENTREGADO - CANCELADO
    public $precio;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query="INSERT INTO pedidos (codigo, idMesa, idProducto, idUsuario, horaPedido, tiempoPreparacion, estado, precio) VALUES ('$this->codigo','$this->idMesa', '$this->idProducto', '$this->idUsuario', '$this->horaPedido', '$this->tiempoPreparacion', '$this->estado', '$this->precio')";
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos($rol) // LE PASO UN ROL DESDE EL CONTROLER, QUE VIENE ATADO AL TOKEN
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query;
        if($rol === "SOCIO" || $rol === "MOZO"){
            $query="SELECT * FROM pedidos";
        }elseif($rol === "COCINERO"){
            $query="SELECT * FROM pedidos INNER JOIN productos ON pedidos.idProducto = productos.id WHERE productos.tipo = 'PLATOS' or productos.tipo = 'POSTRES' AND pedidos.estado = 'PENDIENTE' or pedidos.estado = 'PREPARACION'";
        }elseif($rol === "CERVECERO"){
            $query="SELECT * FROM pedidos INNER JOIN productos ON pedidos.idProducto = productos.id WHERE productos.tipo = 'CERVEZA' AND pedidos.estado = 'PENDIENTE' or pedidos.estado = 'PREPARACION'";
        }elseif($rol === "BARTENDER"){
            $query="SELECT * FROM pedidos INNER JOIN productos ON pedidos.idProducto = productos.id WHERE productos.tipo = 'TRAGOS-VINOS' AND pedidos.estado = 'PENDIENTE' or pedidos.estado = 'PREPARACION'";
        }
        $consulta = $objAccesoDatos->prepararConsulta($query);
        $consulta->execute();    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function tomarPedido($id, $tiempoPreparacion, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query;
        if($estado === 'PREPARACION'){
            $query="UPDATE pedidos SET tiempoPreparacion = ?, horaPedido = ?, estado = ? WHERE id = ?";
            $consulta = $objAccesoDato->prepararConsulta($query);
            $consulta->bindParam(1, $tiempoPreparacion);
            $consulta->bindParam(2, date('H:i'));
            $consulta->bindParam(3, $estado);
            $consulta->bindParam(4, $id);  
        }else{
            $query="UPDATE pedidos SET estado = ? WHERE id = ?";
            $consulta = $objAccesoDato->prepararConsulta($query);
            $consulta->bindParam(1, $estado);
            $consulta->bindParam(2, $id); 
        }
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



    // public static function CrearPDF(){
    //     $pdf = new PdfServicio();
        
    //     $pdf->SetTitle("Reportes Empleados");
    //     $pdf->AddPage();
    //     $pdf->Cell(150,10,'PEDIDOS ' ,0,1);
    //     $pdf->MultiCell(150,10, Pedidos::obtenerTodos("SOCIO"));
    //     $pdf->Output();
    //     ob_end_flush();
    // }

}


?>