<?php

class Mesa{
    public $id;
    public $idUsuario;
    public $estado; // ESPERANDO - COMIENDO - PAGANDO - CERRADA
    public $nombreCliente;
    public $codigo;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (idUsuario, codigo, estado, nombreCliente) VALUES ('$this->idUsuario', '$this->codigo', '$this->estado', '$this->nombreCliente')");
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, codigo, estado, nombreCliente FROM mesas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, idUsuario, codigo, estado, nombreCliente FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $mesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($idUsuario, $estado, $nombreCliente, $id, $codigo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query="UPDATE mesas SET idUsuario = ?, estado = ?, nombreCliente = ?, codigo= ? WHERE id = ?";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $consulta->bindParam(1, $idUsuario);
        $consulta->bindParam(2, $estado);
        $consulta->bindParam(3, $nombreCliente);
        $consulta->bindParam(4, $codigo);
        $consulta->bindParam(5, $id);
        $consulta->execute();
    }

    public static function cerrarMesa($id, $estado)  // SOLO PARA ADMIN - ESTADO PAGANDO O CERRADA
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query="UPDATE mesas SET estado = ?, idUsuario = null, codigo = '', nombreCliente = '' WHERE id = ?";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $consulta->bindParam(1, $estado);
        $consulta->bindParam(2, $id);
        $consulta->execute();
    }

    public static function generarCodigoUnico() {
        $codigoUnico = uniqid('', true);
        $codigoUnico .= str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $codigoUnico = substr($codigoUnico, 7, 5);
    
        return $codigoUnico;
    }

    public static function guardarFoto($extensionFoto, $tamanoFoto, $controlAlta, $carpetaFoto, $fotoReserva, $ruta_destino)
    {
                // Validaciones de la foto - Valida tipo archivo - tamaño - directorio: si no existe lo crea
        if (!((strpos($extensionFoto, "png") || strpos($extensionFoto, "jpeg")) && ($tamanoFoto < 10000000))) {
            echo "La extensión o el tamaño de la foto no es correcta.\n";
        }else if($controlAlta === false){
            // si no se da el alta, no se carga la foto
        }else{
            if(!is_dir($carpetaFoto)){
                mkdir($carpetaFoto, 0777, true);
            }
            if (move_uploaded_file($fotoReserva,  $ruta_destino)){
                echo "\nLa foto del cliente ha sido cargada correctamente. \n";
            }else{
                echo "Ocurrió algún error al cargar la foto.\n";
            }
        }
    }
    

}


?>