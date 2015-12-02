<?php
//Faz autoload nas pasta passadas como key para o array, e busca os arquivos que estao como chave, passando vazio busca todos da pasta
$array_autoLoad = array(
	WEB_SERVICE.'Abstract'.DS => array('controller.php', 'dao_json.php'),
	WEB_SERVICE.'Categorias'.DS.'dao'.DS => array('interface.php', strtolower(SALVA_DADOS).'.php')
);

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerWebserviceCategorias extends AbstractWebserviceController{
	//instancia a classe que recebera os dados
	private $classe;
	
	function __construct(){
		if( $class = $this->validaClasseDados() ){
			$this->classe = new $class;
		} else {
			echo 'Erro. verifique o nome da classe e tente novamente;';
		}
	}
	
	/**
	 * Retorna a categoria passada pelo id, se não tiver passado um id retorna todas
	 * 
	 * @param int $id Id da categoria a ser buscada
	 * @return array()
	 */
	function visualizarCategorias($id=null){
		return $this->classe->visualizarCategorias($id);
	}
	
	/**
	 * Cria um nova categorias
	 *
	 * @param String $nome Nome da categoria a ser criada
	 * @return array
	 */
	function adicionarCategoria($nome){
		return $this->classe->adicionarCategoria($nome);
	}
	
	/**
	 * Edita uma categoria
	 *
	 * @param int $id Id da categoria a ser alterada
	 * @param String $nome Nome para ser setado na categoria
	 * @return number|boolean
	 */
	function editarCategoria($nome, $id){
		return $this->classe->editarCategoria($nome, $id);
	}
	
	/**
	 * Seta o status da categoria como 0, assim ela nao e mais exibida
	 *
	 * @param int $id Id da categoria a ser alterada
	 * @return number|boolean
	 */
	function removerCategoria($id){
		return $this->classe->removerCategoria($id);
	}
	
	/**
	 * Valida o nome da classe onde sera salvo os dados
	 */
	private function validaClasseDados(){
		if( $class = parent::classeDados('Categorias') ){
			return $class;
		} 
		return false;
	}
}