<?php

    require 'models/buscador.php';

    class BuscadorController {
        private $modelEmpleado;

        public function __construct() {
            $this->modelEmpleado = new Buscador();
        }

        
    }


