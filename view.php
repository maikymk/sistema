<?php

class ViewFrame extends AbstractView {
    public function __construct() {}

    /**
     * Monta e apresenta a tela para o usuario
     */
    public function show() {
        require_once 'templates' . DS . 'template.php';
    }
}