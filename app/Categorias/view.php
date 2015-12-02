<?php

class ViewCategoria implements InterfaceView {
    public $categorias = array();

    public function __construct() {}

    /**
     * Seta as categorias que serao exibidas na tela para o usuario
     * 
     * @param array $categorias
     */
    public function setCategorias($categorias) {
        $this->categorias = $categorias;
    }

    /**
     * Metodo implementado da interface
     * 
     * {@inheritDoc}
     *
     * @see InterfaceView::retornaTela()
     */
    public function retornaTela($arq) {
        ob_start();
        require_once $arq;
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }
}