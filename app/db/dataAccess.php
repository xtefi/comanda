<?php

class DataAccess{
    private static $dataAccessObj;
    private $pdo;

    private function __construct(){
        try{
            $conStr='mysql:host=localhost; dbname=comanda';
            $this->pdo = new PDO($conStr);
            // new PDO($conStr, $user, $pw);
        } catch(PDOException $e){
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

?>