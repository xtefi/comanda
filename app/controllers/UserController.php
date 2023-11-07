<?php
require_once './models/User.php';
require_once './interfaces/IApiUsable.php';

class UserController extends User implements IApiUsable
{
    public function GetOne($request, $response, $args){
        // Buscamos usuario por nombre
        $usr = $args['user'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
	public function GetAll($request, $response, $args){

    }
	
    public function NewUser($request, $response, $args)
    {
        $param = $request->getParsedBody();
        $user = $param['user'];
        $password = $param['password'];
        $rol = $param['rol'];
        $status = $param['status'];
        $hired = $param['hired'];
        $fired = $param['fired'];

        // Creamos el usuario
        $usr = new User();
        $usr->user = $user;
        $usr->password = $password;
        $usr->rol = $rol;
        $usr->status = $status;
        $usr->hired = $hired;
        $usr->fired = $fired;
        $usr->InsertUser();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
	public function DeleteOne($request, $response, $args){

    }
	public function ModifyOne($request, $response, $args){

    }
}