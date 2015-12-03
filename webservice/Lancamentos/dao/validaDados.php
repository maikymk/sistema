<?php

/**
 * Classe que valida os dados que o usuario enviou 
 * ao tentar cadastrar um novo lancamento
 * 
 * @author maikysilva
 *
 */
class ValidaDadosLancamentos{
    private $erros;
    
    /**
     * Valida os dados enviados pelo formulario
     * Se nao tiver erro retorna um array com o valores validos
     * Se tiver erro retorna false
     *
     * @param array $dados Dados para validar
     * @return bool
     */
    public function validaDados($dados) {
        $result = array();
    
        //validando o tamanho maximo do campo de descricao
        if ($this->validaDado($dados['descLancamento'], 'Erro no campo descri&ccedil;&aatilde;o.') && $this->validaDescricao($dados['descLancamento'])) {
            $result['descricao'] = $dados['descLancamento'];
        }
    
        $result['valor'] = $this->validaDado($dados['valorLancamento'], 'Erro no campo valor.');
        $result['categoria'] = $this->validaDado($dados['categLancamento'], 'Erro no campo categoria .');
        $result['tipo'] = $this->validaDado($dados['tipoLancamento'], 'Erro no campo tipo.');
    
        //validando a data
        if ($this->validaDado($dados['dataLancamento'], 'Erro na data. Preencha corretamente.')) {
            $result['data'] = $this->validaData($dados['dataLancamento']);
        }
    
        //se nao tiver nenhum erro retorna um array com os dados validados
        if (empty($this->getErro())) {
            return $result;
        }
        return false;
    }
    
    /**
     * Valida o tamanho maximo da descricao do lancamento
     *
     * @param String $desc
     * @return bool
     */
    private function validaDescricao($desc) {
        if (strlen($desc) > TAM_MAX_DESC) {
            $this->setErro('Erro no campo descri&ccedil;&atilde;o. O tamanho m&aacute;ximo &eacute; ' . TAM_MAX_DESC . ' caracteres.');
            return false;
        }
        return true;
    }
    
    /**
     * Recebe uma data e valida ela
     *
     * @param String $dado data a ser validada
     * @return String|bool
     */
    private function validaData($data) {
        $data1 = str_replace(array('-', '/', '\\'), '', $data);
    
        $dia = substr($data1, 0, 2);
        $mes = substr($data1, 2, 2);
        $ano = substr($data1, 4, 4);
    
        $data1 = $ano . '-' . $mes . '-' . $dia;
        $data2 = date('Y-m-d', strtotime($data1));
    
        if ($data1 == $data2 && date('Y-m-d', strtotime($data1))) {
            return htmlentities(strip_tags($data2));
        }
    
        $this->setErro('Erro no campo data. Preencha corretamente.');
        return false;
    }
    
    /**
     * Valida o dado enviado
     *
     * @param String|numeric $dado Dado do campo a ser validado
     * @param String $msgErro Mensagem caso ocorra erro na validacao
     * @return String|bool
     */
    private function validaDado($dado, $msgErro) {
        if (isset($dado) && ! empty(trim($dado))) {
            return htmlentities(strip_tags($dado));
        }
        $this->setErro($msgErro);
        return false;
    }
    
    /**
     * Seta o erro que ocorreu
     * 
     * @param string $erro Erro a ser setado
     */
    public function setErro($erro) {
        $this->erros[] = $erro;
    }
    
    /**
     * Seta o erro que ocorreuDevolve um array 
     * com todos os erros que ocorreram
     *
     * @return $array()
     */
    public function getErro() {
        return $this->erros;
    }
}