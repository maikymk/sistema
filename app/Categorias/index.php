<?php

/**
 * Faz autoload nas pasta passadas como key para o array, 
 * e busca os arquivos que estao como chave.
 * Passando vazio busca todos da pasta
 */
$array_autoLoad = array(
    INTERFACE_APP => array(), 
    ABSTRACT_APP => array(),
    APP . 'Categorias' . DS => array('model.php', 'view.php'));

$autoLoad = new Autoload();
$autoLoad->setDirAndFiles($array_autoLoad);
$autoLoad->load();

class ControllerCategorias extends AbstractAppController implements InterfaceController {
    //caminho para os templates desse componente
    private $templates = APP . 'Categorias' . DS . 'templates' . DS;
    //acoes que esse componente pode executar
    private $acoes = array('visualizar', 'adicionar', 'editar', 'remover');
    private $view;
    private $model;
    private $telaUser;
    private $idCategoria = null;
    private $acao;

    public function __construct() {
        $this->view = new ViewCategoria();
        $this->model = new ModelCategoria();
    }

    /**
     * Metodo implementado da interface 
     * define o comportamento do componente Categorias
     * 
     * {@inheritDoc}
     *
     * @see InterfaceController::handle()
     */
    public function handle() {
        // verifica se a requisicao veio por ajax
        if (isset($_GET['aj'])) {
            if ($this->validaGet()) {
                echo json_encode($this->verificaAcao());
            }
        } else {
            if ($this->validaGet()) {
                $this->verificaAcao();
            }
            
            $metodo = $this->verificaAcesso($this->acoes);
            $this->$metodo();
        }
    }

    /**
     * Verifica o GET que foi enviado
     * 
     * @return bool
     */
    private function validaGet() {
        if (isset($_GET['ac']) && in_array($_GET['ac'], $this->acoes)) {
            $this->acao = htmlentities($_GET['ac']);
            $this->idCategoria = (int) $_GET['id'];
            return true;
        }
        return false;
    }

    /**
     * Metodo implementado da interface
     * 
     * {@inheritDoc}
     *
     * @see InterfaceController::mostraTela()
     */
    public function mostraTela() {
        return $this->telaUser;
    }

    /**
     * Verifica a acao que o usuario esta tentando fazer, 
     * e ja aciona se existir e ja envia a requisicao para a model
     * 
     * @return bool|int
     */
    private function verificaAcao() {
        $return = 0;
        if ($this->acao == 'adicionar') {
            $return = $this->verificaAdicionar();
        } elseif ($this->acao == 'editar') {
            $return = $this->verificaEditar();
        } elseif ($this->acao == 'remover') {
            $return = $this->verificaRemover();
        }
        return $return;
    }

    /**
     * Metodo que exibe a pagina home do usuario
     */
    private function home() {
        // busca as categorias do bd
        $categorias = $this->model->visualizarCategorias();
        // seta as categorias para a view
        $this->view->setCategorias($categorias);
        // passa o arquivo home
        $file = $this->templates . 'home.php';
        
        // retorna o conteudo da tela home para o usuario
        $this->telaUser = $this->view->retornaTela($file);
    }

    private function adicionar() {}

    private function editar() {}

    private function remover() {}

    /**
     * Verifica se foi setado o post de adicionar uma categoria, 
     * se tiver sido ja envia os dados da adicao pra model
     * 
     * @return bool|int
     */
    private function verificaAdicionar() {
        if (isset($_POST['adicionarCat'])) {
            // remove qualquer insercao que o usuario tentou fazer e recupera o nome
            $nomeCat = htmlentities(strip_tags($_POST['nomeCat'], '<p><h1>'));
            
            if ($ultimoId = $this->model->adicionarCategoria($nomeCat)) {
                return array(
                    'id' => $ultimoId, 
                    'nome' => html_entity_decode($nomeCat));
            }
        }
        return 0;
    }

    /**
     * Verifica se foi setado o post de editar uma categoria, 
     * se tiver sido ja envia os dados da alteracao pra model
     * 
     * @return bool|int
     */
    private function verificaEditar() {
        if (isset($_POST['editarCat'])) {
            // remove qualquer insercao que o usuario tentou fazer e recupera o nome
            $nomeCat = htmlentities(strip_tags($_POST['nomeCat'], '<p><h1>'));
            
            if ($this->model->editarCategoria($nomeCat, $this->idCategoria)) {
                return array(
                    'id' => $this->idCategoria, 
                    'nome' => html_entity_decode($nomeCat));
            }
        }
        return 0;
    }

    /**
     * Verifica se foi setado o post de remover uma categoria, 
     * se tiver sido ja envia a exclusao pra model
     * 
     * @return bool|int
     */
    private function verificaRemover() {
        if (isset($_POST['removerCat'])) {
            return $this->model->removerCategoria($this->idCategoria);
        }
        return 0;
    }
}