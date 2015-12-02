<?php
/**
 * Faz autoload nas pasta passadas como key para o array,
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = array(
    WEB_SERVICE . 'Abstract' . DS => array(
        'controller.php', 
        'dao_json.php'), 
    WEB_SERVICE . 'Relatorios' . DS . 'dao' . DS => array(
        'interface.php', 
        strtolower(SALVA_DADOS) . '.php'));

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerWebserviceRelatorios extends AbstractWebserviceController {
    //Ira instanciar a classe que recebera os dados
    private $classe;

    public function __construct() {
        if ($class = $this->validaClasseDados()) {
            $this->classe = new $class();
        } else {
            echo 'Erro. verifique o nome da classe e tente novamente;';
        }
    }

    /**
     * Busca todos os dados de lancamento e 
     * monta um relatorio por categorias
     * 
     * @param String $tipo Tipo da receita a ser exibida
     * @return $array
     */
    public function relatoriosPorCategoria($tipo = null) {
        return $this->classe->relatoriosPorCategoria($tipo);
    }

    /**
     * Busca as receitas que estao visiveis
     * 
     * @return boolean|1
     */
    public function buscaTipos() {
        return $this->classe->buscaTipos();
    }

    /**
     * Valida o nome da classe onde sera salvo os dados
     * 
     * @return String|bool
     */
    private function validaClasseDados() {
        if ($class = parent::classeDados('Relatorios')) {
            return $class;
        }
        return false;
    }
}