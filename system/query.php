<?php

/**
 * Classe para trabalhar com banco de dados
 *
 * Faz toda a parte relacionada a banco de dados,
 * Conexao, consultas, etc
 *
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

class Query {
    //funcoes que podem ser executadas no bd
    private static $crud = array('EXPLAIN', 'INSERT', 'SELECT', 'UPDATE', 'DELETE');
    private static $tipoSql;
    //variavel que faz a conexao com o BD
    private static $con = null;

    /**
     * Construtor do tipo protegido previne que uma nova instancia da
     * Classe seja criada atravas do operador `new` de fora dessa classe.
     * Metodo usado para chamar o metodo que tenta fazer a conexao com o BD
     */
    protected function __construct() {}

    /**
     * Funcao para fazer conexao com o BD
     */
    private static function conectaBd() {
        if (! static::$con) {
            try {
                static::$con = new PDO('mysql:host=' . HOST . ';dbname=' . BD, USER, PASSWORD);
                static::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            } catch (PDOException $e) {
                echo 'Erro ao conectar com o BD.';
            }
        }
        return static::$con;
    }

    /**
     * Funcao que valida e envia as querys para o BD
     *
     * @param String $sql; instrucao sql a ser executada
     * @return array|bool|1
     */
    public static function sql($sql, $dados = null) {
        //tenta conectar ao BD
        if (static::conectaBd()) {
            try {
                //prepara a consulta para o BD
                $stmt = static::$con->prepare($sql);

                //faz o bindParam dos dados passado '$dados' passados no '$stmt'
                static::bindParam($dados, $stmt);

                try {
                    //verifica se o tipo de query que esta tentando fazer e aceita
                    static::validaQuery($sql);
                    /**
                     * inicia a transacao com o BD, a partir de agora as
                     * queris so terao efeito se for executado um commit,
                     * se ocorrer algum erro sera executado um
                     * rollback para desfazer as queries que forem executadas
                     */
                    static::$con->beginTransaction();
                    //executa a query
                    $linhasAfetadas = $stmt->execute();

                    return static::retornoConsulta($linhasAfetadas, $stmt);
                } catch (PDOExecption $e) {
                    //se ocorrer algum erro faz rollback e volta o bd ao estado anterior
                    static::$con->rollback();
                    echo 'Verifique sua consulta e tente novamente';
                }
            } catch (Exception $e) {
                //se ocorrer algum erro faz rollback e volta o bd ao estado anterior
                static::$con->rollBack();
                echo 'Erro! Tente novamente';
            }
        }
        return false;
    }

    /**
     * Faz o bind dos dados recebidos
     *
     * @param array() $dados Dados a fazer bindParam
     * @param resource $stmt Variavel responsavel por fazer o bind nos dados
     */
    private static function bindParam($dados, &$stmt) {
        //se tiver enviado algum dado
        if (!empty($dados)) {
            //se tiver enviado um array
            if (is_array($dados)) {
                foreach ($dados as $key => $dado) {
                    $stmt->bindParam($key + 1, $dados[$key]);
                }
            } else {
                $stmt->bindParam(1, $dados);
            }
        }
    }

    /**
     * Verifica o tipo da query, se tiver tudo certo,
     * faz o commit e retorno o resultado da query
     *
     * @param int $linhasAfetadas Linhas que forma afetadas na execucao da query
     * @param resource $stmt Variavel responsavel por fazer o commit na consulta se estiver tudo certo
     * @return int|array()
     */
    private static function retornoConsulta($linhasAfetadas, &$stmt) {
        $result;
        if (static::$tipoSql == 'SELECT' || static::$tipoSql == 'EXPLAIN') {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //comita a query, sinal que ocorreu tudo como planejado
            static::$con->commit();
        } elseif (static::$tipoSql == 'UPDATE' || static::$tipoSql == 'DELETE') {
            //comita a query, sinal que ocorreu tudo como planejado
            static::$con->commit();
            $result = $linhasAfetadas;
        } elseif (static::$tipoSql == 'INSERT') {
            //recupera o ultimo id inserido no bd
            $result = static::$con->lastInsertId();
            //comita a query, sinal que ocorreu tudo como planejado
            static::$con->commit();
        }
        return $result;
    }

    /**
     * Funcao que valida a sql antes de ela ser passada para o BD
     *
     * @param String $sql; instrucao sql a ser verificada
     * @return bool
     */
    private static function validaQuery($sql) {
        $crud = explode(' ', $sql);
        $crud = strtoupper($crud[0]);

        if (in_array($crud, static::$crud)) {
            static::$tipoSql = $crud;
            return true;
        }
        return false;
    }

    /**
     * retorna o ultimo id inserido no BD
     */
    public static function getUltimoId() {
        return static::$con->lastInsertId();
    }

    /**
     * Retorna a conexao com o BD
     */
    public static function getConexao() {
        return static::$con;
    }

    /**
     * Funcao que desconecta do BD
     */
    public static function desconectaBd() {
        mysqli_close(static::$con);
    }

    /**
     * Metodo clone do tipo privado previne
     * a clonagem dessa inst�ncia da classe
     */
    private function __clone() {}

    /**
     * Metodo unserialize do tipo privado para
     * prevenir a desserializa��o da inst�ncia dessa classe.
     */
    private function __wakeup() {}
}