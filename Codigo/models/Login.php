<?php

class Login {

    private $pdo;

    public function __construct() {
        try{
            $this->pdo = new PDO("mysql:host=localhost;dbname=shinny_hunt;charset=utf8mb4", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo "Error ". $e -> getmessage();
        }
    }

    public function login($username, $password) {
        try{
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        }catch(PDOException $e){
            die("Error en la base de datos: " . $e->getMessage());
        }
    }

    // Comprobacion de que no hay 2 personas con mismos datos
    public function usernameExists(string $username):bool{
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return (bool) $stmt->fetch();
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    public function registro($data) {
        try{
            $newpassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password, phone, adress, create_in) VALUES (?,?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                $data['username'],
                $data['email'],
                $newpassword,
                $data['phone'],
                $data['adress'],
                date("Y-m-d H:i:s")
            ]);
        }catch(PDOException $e){
            die("Error en la base de datos: " . $e->getMessage());
        }
    }
}
