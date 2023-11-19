<?php
use Firebase\JWT\JWT;


class AuthJWT
{
    private static $pwSecreata = 'asd123';
    private static $tipoEncrypt = ['HS256'];

    public static function CrearToken($datos){
        $ahora = time();
        $payload = array(
            'iat' => $ahora, 
            'exp' => $ahora + (60000), 
            'aud' => self::Aud(),
            'data' => $datos,
            'app' => "Test JWT"
        );
        return JWT::encode($payload, self::$claveSecreta);
    }

    public static function VerificarToken($token){
        if(empty($token)){
            throw new Exception("Token vacio");
        }
        try{
            $decodificado = JWT::decode(
                $token,
                self::$claveSecreta,
                self::$tipoEncriptacion
            );
        }catch (Exception $e){
            throw $e;
        }
        if($decodificado->aud !== self::Aud()){
            throw new Exception("Usuario inválido");
        }
    }

    public static function ObtenerPayload($token){
        
    }
}