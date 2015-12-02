<?php
require_once WEB_SERVICE . 'Categorias' . DS . 'index.php';

class ModelCategoria {
    private $webService;

    public function __construct() {
        $this->webService = new ControllerWebserviceCategorias();
    }

    /**
     * Busca as categorias no BD
     * 
     * @param int $id Id de uma categoria especifica, se vazio traz todas
     * @return array
     */
    public function visualizarCategorias($id = null) {
        return $this->webService->visualizarCategorias($id);
    }

    /**
     * Cria um nova categorias no BD
     * 
     * @param String $nome Nome da categoria a ser criada
     * @return array
     */
    public function adicionarCategoria($nome) {
        return $this->webService->adicionarCategoria($nome);
    }

    /**
     * Edita uma categoria
     * 
     * @param int $id Id da categoria a ser alterada
     * @param String $nome Nome para ser setado na categoria
     * @return number|boolean
     */
    public function editarCategoria($nome, $id) {
        return $this->webService->editarCategoria($nome, $id);
    }

    /**
     * Seta o status da categoria como 0, assim ela nao e mais exibida
     * 
     * @param int $id Id da categoria a ser alterada
     * @return number|boolean
     */
    public function removerCategoria($id) {
        return $this->webService->removerCategoria($id);
    }
}