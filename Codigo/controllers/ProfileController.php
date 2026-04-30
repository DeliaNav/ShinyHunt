<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Collection.php';
require_once __DIR__ . '/../models/wishList.php';

class ProfileController {
    private User       $userModel;
    private Collection $collection;
    private WishList   $wishlist;

    public function __construct() {
        $this->userModel  = new User();
        $this->collection = new Collection();
        $this->wishlist   = new WishList();
    }

    /** GET /perfil — muestra el perfil */
    public function index() {
        Auth::require();
        $userId = Auth::userId();

        $user            = $this->userModel->getById($userId);
        $totalCollection = $this->collection->count($userId);
        $totalWishlist   = $this->wishlist->count($userId);
        $error           = null;
        $success         = null;

        $pageTitle = 'Mi Perfil · TCGMarket';
        $extraCss  = 'profile.css';
        require_once __DIR__ . '/../views/profile.php';
    }

    /** POST /perfil/update — actualiza datos personales */
    public function update() {
        Auth::require();
        $userId = Auth::userId();

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $bio      = trim($_POST['bio']      ?? '');
        $phone    = trim($_POST['phone']    ?? '');

        $user            = $this->userModel->getById($userId);
        $totalCollection = $this->collection->count($userId);
        $totalWishlist   = $this->wishlist->count($userId);
        $error   = null;
        $success = null;

        if (!$username || !$email) {
            $error = 'El nombre de usuario y el email son obligatorios.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif ($this->userModel->usernameExists($username, $userId)) {
            $error = 'Ese nombre de usuario ya está en uso.';
        } elseif ($this->userModel->emailExists($email, $userId)) {
            $error = 'Ese email ya está registrado.';
        } else {
            $ok = $this->userModel->update($userId, $username, $email, $bio, $phone);
            if ($ok) {
                $_SESSION['nombre'] = $username;
                $success = 'Perfil actualizado correctamente.';
                $user = $this->userModel->getById($userId);
            } else {
                $error = 'Error al actualizar el perfil.';
            }
        }

        $pageTitle = 'Mi Perfil · TCGMarket';
        $extraCss  = 'profile.css';
        require_once __DIR__ . '/../views/profile.php';
    }

    /** POST /perfil/password — cambia la contraseña */
    public function password() {
        Auth::require();
        $userId = Auth::userId();

        $current  = $_POST['current_password']  ?? '';
        $new      = $_POST['new_password']       ?? '';
        $confirm  = $_POST['confirm_password']   ?? '';

        $user            = $this->userModel->getById($userId);
        $totalCollection = $this->collection->count($userId);
        $totalWishlist   = $this->wishlist->count($userId);
        $error   = null;
        $success = null;

        if (!$current || !$new || !$confirm) {
            $error = 'Completa todos los campos de contraseña.';
        } elseif (!password_verify($current, $user['password'])) {
            $error = 'La contraseña actual no es correcta.';
        } elseif (strlen($new) < 8) {
            $error = 'La nueva contraseña debe tener al menos 8 caracteres.';
        } elseif ($new !== $confirm) {
            $error = 'Las contraseñas no coinciden.';
        } else {
            $ok = $this->userModel->updatePassword($userId, password_hash($new, PASSWORD_DEFAULT));
            $success = $ok ? 'Contraseña actualizada correctamente.' : 'Error al cambiar la contraseña.';
        }

        $pageTitle = 'Mi Perfil · TCGMarket';
        $extraCss  = 'profile.css';
        require_once __DIR__ . '/../views/profile.php';
    }

    /** POST /perfil/avatar — sube avatar */
    /** POST /perfil/avatar — sube o borra el avatar */
    public function avatar() {
        Auth::require();
        $userId = Auth::userId();

        $user            = $this->userModel->getById($userId);
        $totalCollection = $this->collection->count($userId);
        $totalWishlist   = $this->wishlist->count($userId);
        $error   = null;
        $success = null;

        // --- 1. LÓGICA PARA BORRAR AVATAR ---
        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $user = $this->userModel->getById($userId);
            if (!empty($user['avatar'])) {
                $destDir = __DIR__ . '/../public/img/avatars/';
                $oldFile = $destDir . basename($user['avatar']);
                if (file_exists($oldFile)) unlink($oldFile);
                $this->userModel->updateAvatar($userId, '');
            }
            // Redirigir para limpiar los datos de POST y evitar re-envíos
            header("Location: /TFG/Codigo/perfil?success=Foto eliminada");
            exit;
        }

        // --- 2. LÓGICA PARA SUBIR AVATAR (Original) ---
        if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $error = 'No se ha subido ningún archivo.';
        } else {
            $file     = $_FILES['avatar'];
            $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $maxSize  = 2 * 1024 * 1024; // 2 MB

            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowed)) {
                $error = 'Formato no permitido. Usa JPG, PNG, WEBP o GIF.';
            } elseif ($file['size'] > $maxSize) {
                $error = 'El archivo supera el límite de 2 MB.';
            } else {
                $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
                $destDir  = __DIR__ . '/../public/img/avatars/';

                if (!is_dir($destDir)) mkdir($destDir, 0755, true);

                if (move_uploaded_file($file['tmp_name'], $destDir . $filename)) {
                    // Borra avatar anterior si existe
                    if (!empty($user['avatar'])) {
                        $old = $destDir . basename($user['avatar']);
                        if (file_exists($old)) unlink($old);
                    }
                    $avatarUrl = '/TFG/Codigo/public/img/avatars/' . $filename;
                    $this->userModel->updateAvatar($userId, $avatarUrl);
                    $success = 'Avatar actualizado correctamente.';
                    $user    = $this->userModel->getById($userId);
                } else {
                    $error = 'Error al guardar el archivo.';
                }
            }
        }

        $pageTitle = 'Mi Perfil · TCGMarket';
        $extraCss  = 'profile.css';
        require_once __DIR__ . '/../views/profile.php';
    }
   
}
