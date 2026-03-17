<?php

class Login {

    private $pdo;

    public function __construct() { // Corregido __construct
        try{
            $this->pdo = new PDO("mysql:host=localhost;dbname=tcg_market;charset=utf8mb4", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Error ". $e -> getmessage();
        }
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function registro($data) {
        $newpassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (nombre, email, password, phone, create_in) VALUES (?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $data['username'],
            $data['email'],
            $newpassword,
            $data['phone'],
            date("Y-m-d H:i:s")
        ]);
    }
}
