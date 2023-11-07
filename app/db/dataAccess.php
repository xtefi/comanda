<?php

class DataAccess{
    private static $dataAccessObj;
    private $pdo;

    private function __construct(){
        try{
            $this->pdo = new PDO('mysql:host=localhost;dbname=comanda;charset=utf8', 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            // new PDO($conStr, $user, $pw);
        } catch(PDOException $e){
            echo "Error: " . $e->getMessage() . "\n";
            die();
        }
    }

    public static function GetInstance()
    { 
        if (!isset(self::$dataAccessObj)) {          
            self::$dataAccessObj = new DataAccess(); 
        } 
        return self::$dataAccessObj;        
    }

    public function __clone(){ 
        trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR); 
    }

    public function GetLastId(){ 
        return $this->pdo->lastInsertId(); 
    }

    public function PrepareQuery($sql){ 
        return $this->pdo->prepare($sql); 
    }

}

?>