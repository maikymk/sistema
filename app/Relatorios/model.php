<?php
require_once WEB_SERVICE . 'Relatorios' . DS . 'index.php';

class ModelRelatorios {
    private $webService;

    public function __construct() {
        $this->webService = new ControllerWebserviceRelatorios();
    }

    /**
     * Busca todos os dados de lancamento e monta um relatorio por categorias
     * 
     * @param String $tipo Tipo da receita a ser exibida
     * @return $array
     */
    public function relatoriosPorCategoria($tipo = null) {
        return $this->webService->relatoriosPorCategoria($tipo);
    }

    /**
     * Busca as receitas que estao visiveis
     * 
     * @return boolean|1
     */
    public function buscaTipos() {
        return $this->webService->buscaTipos();
    }
}