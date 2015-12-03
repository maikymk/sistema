<?php
/**
 * Faz o CRUD no arquivo json para os lancamentos
 *
 * @author maikysilva
 *
 */

require_once 'validaDados.php';

class DAOJsonLancamentos extends DAOAbstractJson implements DAOInterfaceLancamentos {
    public $validaDados;
    
    public function __construct() {
        $this->validaDados = new ValidaDadosLancamentos;
    }
    
    /**
     * Busca as categorias no json
     * 
     * @param int $id Id de uma categoria especifica, se vazio traz todas
     * @return array
     */
    public function visualizarLancamentos($tipo = null) {
        $this->abreArquivo();
        $this->leArquivo();
        
        $result = array();
        
        //verifica se existe categoria e lancamento
        if ($this->existeArrayCategorias() && $this->existeArrayLancamentos()) {
            if (! empty($tipo)) {
                foreach ($this->dadosArquivo['lancamentos'] as $key => $dados) {
                    if ($dados['tipo'] == $tipo) {
                        //busca todos os lancamentos validos
                        $result[$key] = $this->lancamentos($dados);
                    }
                }
            } else {
                foreach ($this->dadosArquivo['lancamentos'] as $key => $dados) {
                    if ($this->statusCatTipoUser($dados['categoria'], $dados['tipo'], $dados['usuario'])) {
                        //busca todos os lancamentos validos
                        $result[$key] = $this->lancamentos($dados);
                    }
                }
            }
        }
        
        $this->fechaArquivo();
        return $result;
    }

    /**
     * Retorna um array com os dados passados
     * 
     * @param array() $dados Dados a serem usados para montarem o array
     * @return array()
     */
    private function lancamentos($dados) {
        return array(
            'id' => $dados['id'], 
            'descricao' => $dados['descricao'], 
            'categoria' => $this->getNome('categoria', $dados['categoria']), 
            'usuario' => $this->getNome('usuario', $dados['usuario']), 
            'tipo' => $this->getNome('tipo_lancamento', $dados['tipo']), 
            'data' => date('d/m/Y', strtotime($dados['data'])), 
            'valor' => number_format($dados['valor'], 2, ",", ".")
        );
    }

    /**
     * Retorna o o valor da key nome do array passado usando o id passado pra comparacao
     * 
     * @param String $arrayNome Nome do array a fazer a busca
     * @param int $id Id a comparar para pegar o retorno
     * @return String
     */
    private function getNome($arrayNome, $id) {
        foreach ($this->dadosArquivo[$arrayNome] as $dado) {
            if ($dado['id'] == $id) {
                return $dado['nome'];
            }
        }
    }

    /**
     * Valida status dos arrays de Categorias, Tipo de lancamento e Usuarios
     * 
     * @param int $cat Id da categoria a ser validado
     * @param int $tipo Id do tipo de lancamento a ser validado
     * @param int $user Id do usuario a ser validado
     * @return boolean
     */
    private function statusCatTipoUser($cat, $tipo, $user) {
        $ok = 0;
        if ($this->validaStatus('categoria', $cat)) {
            $ok++ ;
        }
        if ($this->validaStatus('tipo_lancamento', $tipo)) {
            $ok++ ;
        }
        if ($this->validaStatus('usuario', $user)) {
            $ok++ ;
        }
        
        return (($ok == 3) ? 1 : '');
    }

    /**
     * Retorna o status do array passado usando o id pra comparar
     * 
     * @param String $arrayNome Nome do array a fazer a busca
     * @param int $id Id a comparar para pegar o retorno
     * @return bool
     */
    private function validaStatus($arrayNome, $id) {
        foreach ($this->dadosArquivo[$arrayNome] as $dado) {
            if ($dado['id'] == $id && $dado['status'] == 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verifica se existe o array de categorias no arquivo json
     * 
     * @return bool
     */
    private function existeArrayCategorias() {
        return $this->verificaExisteArray('categoria');
    }

    /**
     * Verifica se existe o array de lancamentos no arquivo json
     * 
     * @return bool
     */
    private function existeArrayLancamentos() {
        return $this->verificaExisteArray('lancamentos');
    }

    /**
     * Verifica se existe o array de usuarios no arquivo json
     * 
     * @return bool
     */
    private function existeArrayUsuario() {
        return $this->verificaExisteArray('usuario');
    }

    /**
     * Verifica se existe o array de tipos e lancamento no arquivo json
     * 
     * @return bool
     */
    private function existeArrayTipoLancamento() {
        return $this->verificaExisteArray('tipo_lancamento');
    }

    /**
     * Retorna as categorias com status de visivel
     * 
     * @return array|boolean
     */
    public function getCategorias() {
        $this->verificaArquivoAberto();
        if ($this->existeArrayCategorias()) {
            return $this->dadosArquivo['categoria'];
        }
        return false;
    }

    /**
     * Retorna as receitas com status de visivel
     * 
     * @return array|boolean
     */
    public function getReceitas() {
        $this->verificaArquivoAberto();
        if ($this->existeArrayTipoLancamento()) {
            return $this->dadosArquivo['tipo_lancamento'];
        }
        return false;
    }

    /**
     * Verifica se o usuario ja fez um lancamento com a mesma descricao nessa data
     * 
     * @param String $data Data a verificar a descricao
     * @param String $descricao Descricao a ser verificada juntamente com a data
     * @return boolean
     */
    public function verificaLancamentoUsuario($data, $descricao) {
        $this->abreArquivo();
        $this->leArquivo();
        
        $nok = 0;
        if ($this->existeArrayUsuario()) {
            $idUsuario = Usuario::getId();
            
            if ($this->existeArrayLancamentos()) {
                foreach ($this->dadosArquivo['lancamentos'] as $dados) {
                    if ($dados['usuario'] == $idUsuario && $dados['data'] == $data && strcmp($dados['descricao'], $descricao) == 0) {
                        $nok++ ;
                    }
                }
            }
        }
        
        if ($nok > 0) {
            $erro = "Erro! Voc&ecirc; n&atilde;o pode lan&ccedil;ar duas descri&ccedil;&otilde;es iguais no mesmo dia.";
            $this->validaDados->setErro($erro);
        }
        
        $this->fechaArquivo();
        return (($nok > 0) ? '' : 1);
    }

    /**
     * Cria um nova categoria
     *
     * @param array() $dados $dados a serem salvos
     * @return int
     */
    public function adicionarLancamentos($dados) {
        $this->abreArquivo();
        $this->leArquivo();
    
        $idUsuario = Usuario::getId();
    
        //inicia em 1 para caso nao exista o array, inicia nessa posicao
        $lastId = 1;
        $ok = 0;
        if ($this->existeArrayLancamentos()) {
            //busca o ultimo array dos lancamentos
            $lastArray = end($this->dadosArquivo['lancamentos']);
            //adiciona 1 ao valor encontrado para que esse seja o ultimo id
            $lastId = $lastArray['id'] + 1;
        }
    
        $valor = str_replace(array('.', ','), array('', '.'), $dados['valor']);
        $this->dadosArquivo['lancamentos'][] = array(
            'id' => $lastId,
            'descricao' => $dados['descricao'],
            'valor' => $valor,
            'data' => $dados['data'],
            'categoria' => $dados['categoria'],
            'tipo' => $dados['tipo'],
            'usuario' => $idUsuario
        );
    
        if ($this->salvaArquivo($this->dadosArquivo)) {
            $ok++ ;
        }
    
        $this->fechaArquivo();
        return (($ok > 0) ? $lastId : '');
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
        if ($result = $this->validaDados->validaDados($dados)) {
            if ($this->verificaLancamentoUsuario($result['data'], $result['descricao'])) {
                return $result;
            }
            return false;
        }
        return false;
    }

    /**
     * Retorna todos os erros
     * 
     * @return array
     */
    public function getErros() {
        return $this->validaDados->getErro();
    }
}