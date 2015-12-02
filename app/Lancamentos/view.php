<?php

class ViewLancamentos implements InterfaceView {
    //lancamentos ja feitos e disponiveis
    public $lancamentos = array();
    //as categorias disponiveis para lancamento
    public $categorias = array();
    //as receitas desponiveis para lancamento
    public $receitas = array();
    //seta as msgs de erro
    public $erros = array();
    //seta as msgs de sucesso
    public $sucessos = array();

    public function __construct() {}

    /**
     * Seta os lancamentos que serao exibidas na tela para o usuario
     * 
     * @param array $lancamentos Lancamentos para setar na tela para o usuario
     */
    public function setLancamentos($lancamentos) {
        $this->lancamentos = $lancamentos;
    }

    /**
     * Funcao para setar as categorias que o usuario pode escolher para realizar um lancamento
     * 
     * @param array $categorias Categorias para setar na tela para o usuario
     */
    public function setCategorias($categorias) {
        $this->categorias = $categorias;
    }

    /**
     * Funcao para setar as receitas que o usuario pode escolher para realizar um lancamento
     * 
     * @param array $receitas Receitas para setar na tela para o usuario
     */
    public function setReceitas($receitas) {
        $this->receitas = $receitas;
    }

    /**
     * Funcao para setar os erros para o usuario
     * 
     * @param array $erros Erros passados pelo controller
     */
    public function setErros($erros = array()) {
        $this->erros = $erros;
    }

    /**
     * Funcao para setar os erros para o usuario
     * 
     * @param array $erros Erros passados pelo controller
     */
    public function setSucessos($sucessos = array()) {
        $this->sucessos = $sucessos;
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