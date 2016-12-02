<?php

abstract class AbstractView {
    public $container;
    public $erros = NULL;

    public function __construct() {}

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
     * Seta os erros que serao exibidos na tela
     *
     * @param array $erros
     */
    public function setErros($erros) {
        if (!empty($erros)) {
            $this->erros = $erros;
        }
    }

	public function getContainer() {
		return $this->container;
	}
}