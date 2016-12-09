<?php

abstract class AbstractView {
    public $container;
    public $error = NULL;

    public function __construct() {}

    /**
     * Recebe o caminho de um arquivo e retorna o seu conteudo
     *
     * @param String $file Arquivo para buscar o conteudo
     * @return HTML Retorna o conteudo do arquivo
     */
    private function show($file) {
        ob_start();
        require_once $file;
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    /**
     * Busca o conteudo do arquivo passado
     *
     * @param String $file Caminho do arquivo a ser buscado o
     * conteudo para ser exibido na tela para o usuario
     */
    public function setContainer($file) {
        $this->container = $this->show($file);
    }
    
    /**
     * Seta os erros que serao exibidos na tela
     *
     * @param array $error
     */
    public function setErrorr($error) {
        if (!empty($error)) {
            $this->error = $error;
        }
    }

	public function getContainer() {
		return $this->container;
	}
}