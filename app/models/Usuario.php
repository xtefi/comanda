<?php

class Usuario
{
    public $id;
    public $usuario;
    public $clave;
    public $rol;           // SOCIO - MOZO - COCINERO - CERVECERO - BARTENDER 
    public $estado;        // ACTIVO - DESPEDIDO - LICENCIA 
    public $fechaAlta;
    public $fechaBaja;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave, rol, estado, fechaAlta, fechaBaja) VALUES ('$this->usuario', '$this->clave', '$this->rol', '$this->estado', '$this->fechaAlta', '$this->fechaBaja')");
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario($usuario, $clave, $rol, $estado, $id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query="UPDATE usuarios SET usuario = ?, clave = ?, rol = ?, estado = ? WHERE id = ?";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $consulta->bindParam(1, $usuario);
        $consulta->bindParam(2, $clave);
        $consulta->bindParam(3, $rol);
        $consulta->bindParam(4, $estado);
        $consulta->bindParam(5, $id);
        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja, estado = :estado WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':estado', "DESPEDIDO", PDO::PARAM_INT);
        $consulta->execute();
    }

}