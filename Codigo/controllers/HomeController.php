<?php

require_once 'lib/Auth.php';
require_once 'lib/TCGApi.php';

class HomeController {
    public function index() {
        Auth::require();

        $cards = TCGApi::getRandomCards(20);

        require_once 'views/home.php';
    }
}