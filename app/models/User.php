<?php

class User{
    public $id;
    public $user;
    public $password;
    public $rol;   // BARTENDER - CERVECERO - COCINERO - MOZO - SOCIO
    public $status; // DISPONIBLE - LICENCIA - VACACIONES - DESPEDIDO
    public $hired;
    public $fired;

    public function ShowUser(){
        return "User: " . $this->user . " - Rol: " . $this->rol . " - status: " . $this->status;
    }

    public function InsertUser(){
        $dbObj = DataAccess::GetInstance();
        $query = $dbObj->PrepareQuery("INSERT INTO users (user,pass,rol,stat,hired,fired) values('$this->user','$this->password','$this->rol','$this->status','$this->hired','$this->fired')");
        $query->execute();
        return $dbObj->GetLastId();
    }





}

?>