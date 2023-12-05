<?php

class Log{
    public static function Add($id_usuario, $usuario, $rol, $entidad, $operacion, $datos_operacion, $datos_resultado_operacion){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO logs (id_usuario, usuario, entidad, operacion, datos_operacion, datos_resultado_operacion, fecha_hora) VALUES (:id_usuario, :usuario, :entidad, :operacion, :datos_operacion, :datos_resultado_operacion, :fecha_hora)");
        $consulta->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':entidad', $entidad, PDO::PARAM_STR);
        $consulta->bindValue(':operacion', $operacion, PDO::PARAM_STR);
        $consulta->bindValue(':datos_operacion', $datos_operacion, PDO::PARAM_STR);
        $consulta->bindValue(':datos_resultado_operacion', $datos_resultado_operacion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
}

?>


