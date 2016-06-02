<?php

class ViewFrame {
    private $erroLogin;
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
     * Busca o conteudo do arquivo passado
     *
     * @param String $arquivo Caminho do arquivo a ser buscado o
     * conteudo para ser exibido na tela para o usuario
     */
    public function setContainer($arquivo) {
        $this->container = $this->retornaTela($arquivo);
    }

    /**
     * Monta e presenta a tela para o usuario
     */
    public function montaTela() {
        require_once 'templates' . DS . 'template.php';
    }
}