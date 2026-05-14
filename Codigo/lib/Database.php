<?php

class Database{
    private static $instance = null;
    private $pdo;

    private function __construct(){
        try{
            $this->pdo = new PDO("mysql:host=localhost;dbname=shinny_hunt;charset=utf8mb4", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Error de conexión: " . $e->getMessage());
        }
    }

    //Singleton
    public static function getInstance():self{//par que devuelva un self
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo():PDO {
        return $this->pdo;
    }
}
