<?php

class ViewFrame {
    private $erroLogin;
    private $erroNovaConta;
    public $container;

    public function __construct() {}

    /**
     * Adiciona a tela de login e retorna seu conteudo
     * 
     * @return HTML Retorna o conteudo do arquivo
     */
    public function telaLogin($erroLog = null) {
        $this->erroLogin = $erroLog;
        return $this->retornaTela(TELA_LOGIN);
    }

    /**
     * Adiciona a tela de nova conta e retorna seu conteudo
     * 
     * @param array $erro Se houver ao cadastra uma nova conta ele sera exibido
     * @return HTML Retorna o conteudo do arquivo
     */
    public function telaNovaConta($erro = array()) {
        $this->erroNovaConta = $erro;
        return $this->retornaTela(TELA_NOVA_CONTA);
    }

    /**
     * Adiciona a tela de erro
     * 
     * @param String $telaErro Caminho do arquivo a ser exibido
     * @return Html Retorna o conteudo do arquivo
     */
    public function telaErro($telaErro) {
        return $this->retornaTela($telaErro);
    }

    /**
     * Recebe o caminho de um arquivo e retorna o seu conteudo
     * 
     * @param String $arq Arquivo para pegar o conteudo
     * @return HTML Retorna o conteudo do arquivo
     */
    private function retornaTela($arq) {
        ob_start();
        require_once $arq;
        $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }

    /**
     * Coloca o conteudo que o usuario vera na tela na variavel $container
     * 
     * @param String $container Conteudo a ser exibido na tela para o usuario
     */
    public function setContainer($container) {
        $this->container = $container;
    }

    /**
     * Monta e presenta a tela para o usuario
     */
    public function montaTela() {
        require_once 'templates' . DS . 'template.php';
    }
}