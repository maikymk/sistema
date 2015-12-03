<?php
/**
 * Faz o CRUD no BD para os lancamentos
 *
 * @author maikysilva
 *
 */

require_once 'validaDados.php';

class DAOBdLancamentos implements DAOInterfaceLancamentos {
    public $validaDados;
    
    public function __construct() {
        $this->validaDados = new ValidaDadosLancamentos;
    }
    
    /**
     * Busca as categorias no BD
     * 
     * @param int $id Id de uma categoria especifica, se vazio traz todas
     * @return array
     */
    public function visualizarLancamentos($tipo = null) {
        $sql = "SELECT l.id, l.descricao, l.valor, l.data, c.nome categoria, r.nome tipo, u.nome usuario FROM lancamento l, categoria c, usuario u, tipo_lancamento r WHERE c.status=1 AND l.categoria=c.id AND l.usuario=u.id AND l.tipo=r.id";
        if (! empty($tipo)) {
            $sql .= "AND l.tipo='" . $tipo . "'";
        }
        
        if ($dados = Query::sql($sql, $tipo)) {
            foreach ($dados as $key => $dado) {
                $dados[$key]['data'] = date('d/m/Y', strtotime($dado['data']));
                $dados[$key]['valor'] = number_format($dado['valor'], 2, ",", ".");
            }
            return $dados;
        }
        return array();
    }

    /**
     * Retorna as categorias com status de visivel
     * 
     * @return array|boolean|1
     */
    public function getCategorias() {
        $sql = "SELECT nome, id FROM categoria WHERE status=1 ORDER BY nome ASC";
        return Query::sql($sql);
    }

    /**
     * Retorna as receitas com status de visivel
     * 
     * @return array|boolean|1
     */
    public function getReceitas() {
        $sql = "SELECT nome, id FROM tipo_lancamento WHERE status=1 ORDER BY nome ASC";
        return Query::sql($sql);
    }

    /**
     * Verifica se o usuario ja fez um lancamento
     * com a mesma descricao nessa data
     * 
     * @param String $data Data a verificar a descricao
     * @param String $descricao Descricao a ser verificada juntamente com a data
     * @return boolean
     */
    public function verificaLancamentoUsuario($data, $descricao) {
        $idUsuario = Usuario::getId();
        $sql = "SELECT data FROM lancamento WHERE data=? AND descricao=? AND usuario=?";
        $result = Query::sql($sql, array($data, $descricao, $idUsuario));
        
        if (empty($result)) {
            return true;
        }
        
        $erro = "Erro! Voc&ecirc; n&atilde;o pode lan&ccedil;ar duas descri&ccedil;&otilde;es iguais no mesmo dia.";
        $this->validaDados->setErro($erro);
        
        return false;
    }
    
    /**
     * Cria um nova categoria
     *
     * @param array() $dados $dados a serem salvos
     * @return int
     */
    public function adicionarLancamentos($dados) {
        $idUsuario = Usuario::getId();
    
        $valor = str_replace(array('.', ','), array('', '.'), $dados['valor']);
        $values = array(
            $dados['descricao'],
            $valor,
            $dados['data'],
            $dados['categoria'],
            $dados['tipo'],
            $idUsuario
        );
    
        $sql = "INSERT INTO lancamento(descricao, valor, data, categoria, tipo, usuario) VALUES (?, ?, ?, ?, ?, ?)";
    
        if ($id = Query::sql($sql, $values)) {
            return $id;
        }
        return 0;
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