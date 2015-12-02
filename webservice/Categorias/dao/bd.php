<?php

/**
 * Faz o CRUD no arquivo BD para a Categoria
 * 
 * @author maikysilva
 *
 */

class DAOBdCategorias implements DAOInterfaceCategorias {

    /**
     * Busca as categorias no BD
     * 
     * @param int $id Id de uma categoria especifica, se vazio traz todas
     * @return array
     */
    public function visualizarCategorias($id = null) {
        $id = null;
        $sql = "SELECT * FROM categoria WHERE status=1";
        
        //se for passado um id acrescenta ele a consulta e traz os dados somente dessa categoria
        if (! empty($id)) {
            $sql .= " AND id = ?";
        }
        
        if ($dados = Query::sql($sql, $id)) {
            return $dados;
        }
        return array();
    }

    /**
     * Cria um nova categorias no BD
     * 
     * @param String $nome Nome da categoria a ser criada
     * @return array
     */
    public function adicionarCategoria($nome) {
        $sql = "INSERT INTO categoria(nome) VALUES(?)";
        if ($id = Query::sql($sql, $nome)) {
            return $id;
        }
        return 0;
    }

    /**
     * Edita uma categoria
     * 
     * @param int $id Id da categoria a ser alterada
     * @param String $nome Nome para ser setado na categoria
     * @return number|boolean
     */
    public function editarCategoria($nome, $id) {
        $sql = "UPDATE categoria SET nome=? WHERE id=?";
        return Query::sql($sql, array($nome, $id));
    }

    /**
     * Seta o status da categoria como 0
     * assim ela nao e mais exibida
     * 
     * @param int $id Id da categoria a ser alterada
     * @return number|boolean
     */
    public function removerCategoria($id) {
        $sql = "UPDATE categoria SET status=0 WHERE id=?";
        return Query::sql($sql, $id);
    }
}