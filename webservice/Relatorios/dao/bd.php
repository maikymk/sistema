<?php

/**
 * Faz a leitura no arquivo BD para alimentar os relatorios
 *
 * @author maikysilva
 *
 */

class DAOBdRelatorios implements DAOInterfaceRelatorios {

    /**
     * Busca todos os dados de lancamento
     * e monta um relatorio por categorias
     * 
     * @param String $tipo Tipo da receita a ser exibida
     * @return $array
     */
    public function relatoriosPorCategoria($tipo = null) {
        $sql = "SELECT l.id, l.descricao, l.valor, l.data, c.nome categoria, r.nome tipo, u.nome usuario
                FROM lancamento l, categoria c, usuario u, tipo_lancamento r
                WHERE c.status=1 AND l.categoria=c.id AND l.usuario=u.id AND l.tipo=r.id";
        if (! empty($tipo)) {
            $sql .= " AND l.tipo=" . $tipo;
        }
        
        /**
         * busca os dados com base no filtro que o usuario passou
         * se nao passar nenhum busca todos os dados validos
         * Dados validos sao quando estao com status=1
         */
        if ($dados = Query::sql($sql, $tipo)) {
            $result = array();
            foreach ($dados as $dado) {
                $result[$dado['categoria']][] = array(
                    'id' => $dado['id'], 
                    'descricao' => $dado['descricao'], 
                    'valor' => number_format($dado['valor'], 2, ",", "."), 
                    'data' => date('d/m/Y', strtotime($dado['data'])), 
                    'tipo' => $dado['tipo'], 
                    'usuario' => $dado['usuario']);
            }
            
            return $result;
        }
        return array();
    }

    /**
     * Busca as receitas que estao visiveis
     * 
     * @return boolean|1
     */
    public function buscaTipos() {
        $sql = "SELECT nome, id FROM tipo_lancamento WHERE status=1 ORDER BY nome ASC";
        return Query::sql($sql);
    }
}