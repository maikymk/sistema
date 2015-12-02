<?php

/**
 * Faz o CRUD no arquivo json para a Categoria
 * 
 * @author maikysilva
 *
 */

class DAOJsonCategorias extends DAOAbstractJson implements DAOInterfaceCategorias {

    /**
     * Busca as categorias no BD
     * 
     * @param int $id Id de uma categoria especifica se vazio traz todas
     * @return array
     */
    public function visualizarCategorias($id = null) {
        $this->abreArquivo();
        $this->leArquivo();
        
        $result = array();
        
        //verifica se existe o array de categorias no arquivo JSON
        if ($this->verificaExisteArray('categoria')) {
            foreach ($this->dadosArquivo['categoria'] as $key => $categoria) {
                if ($categoria['status'] == 1) {
                    $result[$key] = $this->dadosArquivo['categoria'][$key];
                }
            }
        }
        
        $this->fechaArquivo();
        return $result;
    }

    /**
     * Cria um nova categorias no json
     * 
     * @param String $nome Nome da categoria a ser criada
     * @return array
     */
    public function adicionarCategoria($nome) {
        $this->abreArquivo();
        $this->leArquivo();
        
        /**
         * Inicia em 1, se nao existir categoria retorna 1
         * para que comece a salvar na posicao 1 do array
         */ 
        $lastId = 1;
        $ok = 0;
        if ($this->verificaExisteArray('categoria')) {
            $lastArray = end($this->dadosArquivo['categoria']);
            $lastId = $lastArray['id'] + 1;
        }
        
        //salva os dados no passados no array de categoria 
        $this->dadosArquivo['categoria'][] = array(
            'id' => $lastId, 
            'nome' => $nome, 
            'status' => 1);
        
        //salva o array com os dados atualizados no arquivo
        if ($this->salvaArquivo($this->dadosArquivo)) {
            $ok++ ;
        }
        
        $this->fechaArquivo();
        return (($ok > 0) ? $lastId : '');
    }

    /**
     * Edita uma categoria
     * 
     * @param int $id Id da categoria a ser alterada
     * @param String $nome Nome para ser setado na categoria
     * @return number|boolean
     */
    public function editarCategoria($nome, $id) {
        $this->abreArquivo();
        $this->leArquivo();
        
        $ok1 = 0;
        $ok = 0;
        if ($this->verificaExisteArray('categoria')) {
            foreach ($this->dadosArquivo['categoria'] as $key => $categoria) {
                //verifica se o id e igual ao que foi enviado, se for altera o nome
                if ($categoria['id'] == $id) {
                    $this->dadosArquivo['categoria'][$key]['nome'] = $nome;
                    $ok1++ ;
                }
            }
            
            //se tiver encontrado o id passado pelo usuario, faz atualizacao nos dados do arquivo
            if ($ok1 > 0 && $this->salvaArquivo($this->dadosArquivo)) {
                $ok++ ;
            }
        }
        
        $this->fechaArquivo();
        return (($ok > 0) ? 1 : '');
    }

    /**
     * Seta o status da categoria como 0 
     * assim ela nao e mais exibida
     * 
     * @param int $id Id da categoria a ser alterada
     * @return number|boolean
     */
    public function removerCategoria($id) {
        $this->abreArquivo();
        $this->leArquivo();
        
        $ok1 = 0;
        $ok = 0;
        if ($this->verificaExisteArray('categoria')) {
            foreach ($this->dadosArquivo['categoria'] as $key => $categoria) {
                //verifica se o id e igual ao que foi enviado, se for altera o nome
                if ($categoria['id'] == $id) {
                    $this->dadosArquivo['categoria'][$key]['status'] = 0;
                    $ok1++ ;
                }
            }
            
            //se tiver encontrado o id passado pelo usuario, faz atualizacao nos dados do arquivo
            if ($ok1 > 0 && $this->salvaArquivo($this->dadosArquivo)) {
                $ok++ ;
            }
        }
        
        $this->fechaArquivo();
        return (($ok > 0) ? 1 : '');
    }
}