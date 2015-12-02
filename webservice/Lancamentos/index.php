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
    WEB_SERVICE . 'Lancamentos' . DS . 'dao' . DS => array(
        'interface.php', 
        strtolower(SALVA_DADOS) . '.php'));

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerWebserviceLancamentos extends AbstractWebserviceController {
    //instancia a classe que recebera os dados
    private $classe;

    public function __construct() {
        if ($class = $this->validaClasseDados()) {
            $this->classe = new $class();
        } else {
            echo 'Erro. verifique o nome da classe e tente novamente;';
        }
    }

    /**
     * Busca as categorias
     * 
     * @param int $id Id de uma categoria especifica, se vazio traz todas
     * @return array
     */
    public function visualizarLancamentos($tipo = null) {
        return $this->classe->visualizarLancamentos($tipo);
    }

    /**
     * Retorna as categorias com status de visivel
     * 
     * @return array|boolean|1
     */
    public function getCategorias() {
        return $this->classe->getCategorias();
    }

    /**
     * Retorna as receitas com status de visivel
     * 
     * @return array|boolean|1
     */
    public function getReceitas() {
        return $this->classe->getReceitas();
    }

    /**
     * Verifica se o usuario ja fez um lancamento com a mesma descricao nessa data
     * 
     * @param String $data Data a verificar a descricao
     * @param String $descricao Descricao a ser verificada juntamente com a data
     * @return boolean
     */
    public function verificaLancamentoUsuario($data, $descricao) {
        return $this->classe->verificaLancamentoUsuario($data, $descricao);
    }

    /**
     * Valida os dados enviados pelo formulario
     * Se nao tiver erro retorna um array com o valores validos
     * Se tiver erro retorna false
     * 
     * @param array $dados Dados para validar
     * @return bool|array
     */
    public function validaDados($dados) {
        return $this->classe->validaDados($dados);
    }

    /**
     * Retorna todos os erros
     * 
     * @return array
     */
    public function getErros() {
        return $this->classe->getErros();
    }

    /**
     * Cria um nova categorias no BD
     * 
     * @param int $idUsuario Id do usuario a ser salvo no BD
     * @param String $nome Nome da categoria a ser criada
     * @return array
     */
    public function adicionarLancamentos($dados) {
        return $this->classe->adicionarLancamentos($dados);
    }

    /**
     * Valida o nome da classe onde sera salvo os dados
     */
    private function validaClasseDados() {
        if ($class = parent::classeDados('Lancamentos')) {
            return $class;
        }
        return false;
    }
}