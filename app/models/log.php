<?php

class Log{
    public $id;
    public $id_usuario;
    public $usuario; // ESPERANDO - COMIENDO - PAGANDO - CERRADA
    public $rol;
    public $entidad;
    public $operacion;
    public $datos_operacion;
    public $datos_resultado_operacion;


    public static function Add($id_usuario, $usuario, $rol, $entidad, $operacion, $datos_operacion, $datos_resultado_operacion){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (id_usuario, usuario, rol, entidad, operacion, datos_operacion, datos_resultado_operacion, fecha_hora) VALUES (:id_usuario, :usuario, :rol, :entidad, :operacion, :datos_operacion, :datos_resultado_operacion, :fecha_hora)");
        $consulta->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $rol, PDO::PARAM_STR);
        $consulta->bindValue(':entidad', $entidad, PDO::PARAM_STR);
        $consulta->bindValue(':operacion', $operacion, PDO::PARAM_STR);
        $consulta->bindValue(':datos_operacion', $datos_operacion, PDO::PARAM_STR);
        $consulta->bindValue(':datos_resultado_operacion', $datos_resultado_operacion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function ConstructorLogs($id, $id_usuario, $usuario, $rol, $entidad, $operacion, $datos_operacion, $datos_resultado_operacion) {
        $logs = new Log();
        $logs->id = $id;
        $logs->id_usuario = $id_usuario;
        $logs->usuario = $usuario;
        $logs->rol = $rol;
        $logs->entidad = $entidad;
        $logs->operacion = $operacion;
        $logs->datos_operacion = $datos_operacion;
        $logs->datos_resultado_operacion = $datos_resultado_operacion;
        return $logs;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logs");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }

    public static function filtroLogsEmpleados($filtro, $opcion){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query;

        switch($filtro){
            case 'a':
                $query="SELECT * FROM logs WHERE entidad = 'LOGIN'";
                $consulta = $objAccesoDatos->prepararConsulta($query);
                break;
            case 'b':
                $query="SELECT * FROM logs WHERE rol = :rol AND entidad != 'LOGIN'";
                $consulta = $objAccesoDatos->prepararConsulta($query);
                $consulta->bindValue(':rol', $opcion, PDO::PARAM_STR);
                break;
            case 'c':
                $query="SELECT * FROM logs WHERE rol = :rol ORDER BY usuario";
                $consulta = $objAccesoDatos->prepararConsulta($query);
                $consulta->bindValue(':rol', $opcion, PDO::PARAM_STR);
                break;   
            case 'd':
                $query="SELECT * FROM logs WHERE usuario = :usuario";
                $consulta = $objAccesoDatos->prepararConsulta($query);
                $consulta->bindValue(':rol', $opcion, PDO::PARAM_STR);
                break;  
            default:
                $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM logs");
                break;
        }
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Log');
    }
}

?>


