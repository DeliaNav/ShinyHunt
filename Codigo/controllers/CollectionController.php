<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Collection.php';

class CollectionController {
    private Collection $model;

    public function __construct(){
        $this->model = new Collection;
    }

    public function index(){
        //vista principal para la colecion
        Auth::require();//para el id user
        $userId = Auth::userId();
        $cards = $this->model->getByUser($userId);
        $total = $this->model->count($userId);

        $pageTitle = "Mi coleccion";
        $extraCss = 'collections.css';//esto es para el css que dudo que lo pueda poner en el html directamente

        require_once __DIR__ . '/../views/collection.php';//vista collection
    }

    public function add(){
        //anadir carta
        Auth::require();
        $userId = Auth::userId();
        $cardId   = trim($_POST['card_id']   ?? '');
        $cardName = trim($_POST['card_name'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');

        if($cardId && $cardName){
            $this->model->add($userId, $cardId, $cardName, $imageUrl);
        }

        //redireccion
        $redirect = $_POST['redirect'] ?? '/TFG/Codigo/coleccion';
        header("Location: {$redirect}");//mirar si funciona
        exit();
    }

    public function remove(){
        //eliminar carta
        Auth::require();
        $userId = Auth::userId();
        $cardId = tirm($_POST['card_id'] ?? '');

        if($cardId){
            $this->model->remove($userId, $cardId);
        }

        //redireccion
        $redirect = $_POST['redirect'] ?? '/TFG/Codigo/coleccion';
        header("Location: {$redirect}");//mirar si funciona
        exit();
    }
}