<?php
/**
 * Classe para fazer auto load dos arquivos
 * 
 * Recebe um array com as pastas e os arquivos que serao requeridos.
 * Pode ser setado somente o array com as pastas.
 * Se nao setado os arquivos, e necessario setar as extensoes dos arquivos que deseja, ex.: .php
 * 
 * @author Maiky Alves da Silva <maikymk@hotmail.com>
 */

//faz require no arquivo que faz verificacao de arquivos e diretorios
require_once HELPER . 'arquivosDiretorio.php';

class Autoload {
    //recebe o diretorio para buscar os arquivos dentro
    private $dirFiles = null;
    private $qtd = 0;
    //extensoes dos arquivos a serem buscados no diretorio
    private $exts = '';

    /**
     * Recebe diretorios e seus arquivos em forma de um array
     * 
     * @param array() $dados Diretorios e arquivos a serem buscados
     */
    public function setDirAndFiles($dados) {
        $i = 0;
        foreach ($dados as $key => $da) {
            $this->dirFiles[$i]['name'] = $key;
            if (is_array($da)) {
                foreach ($da as $d) {
                    $this->dirFiles[$i]['files'][] = $d;
                }
            }
            $i++ ;
        }
    }

    /**
     * Seta as extensoes dos arquivos a serem buscado 'require_once' no auto_load.
     * As extensoes sao sobreescritas pelos nomes de arquivos se estes forem passados. As extensoes precisam vir com o '.' antes do tipo. Ex: .php
     * 
     * @param String $ext Extensoes validas para os arquivos
     */
    public function setExtensions($ext) {
        $this->exts = $ext;
    }

    /**
     * Chama a funcao para fazer auto load dos arquivos
     */
    public function load() {
        spl_autoload_register([
            $this, 
            'go'
        ]);
    }

    /**
     * Metodo faz com que uma instancia dessa classe chame o autoload somente uma vez
     */
    private function singleton() {
        if ($this->qtd == 0) {
            $this->qtd++ ;
            return true;
        }
        return false;
    }

    /**
     * Verifica se foi setado diretorio e/ou arquivo e faz a chamada pra funcao exexutar o auto_load
     */
    private function go() {
        if ($this->singleton()) {
            if (is_array($this->dirFiles)) {
                $this->loadFilesDir();
            } else {
                echo 'Erro! verifique a forma que esta passando os diretorios e arquivos';
            }
        }
    }

    /**
     * Vai nos diretorios passados e busca os arquivos
     */
    private function loadFilesDir() {
        foreach ($this->dirFiles as $d) {
            if (isset($d['files'])) {
                $this->loadFiles($d['name'], $d['files']);
            } else {
                $files = $this->loadAllFilesDir($d['name']);
                $this->loadFiles($d['name'], $files);
            }
        }
    }

    /**
     * Busca todos os arquivos encontrados no diretorio
     * 
     * @return array() Todos os arquivos encontrados no diretorio
     */
    private function loadAllFilesDir($dir) {
        //passa o diretorio onde sera procurando os arquivos
        ArquivosDiretorio::setDir($dir);
        ArquivosDiretorio::setExtensions($this->exts);
        
        //retorna os arquivos encontrado
        return ArquivosDiretorio::getFiles();
    }

    /**
     * Faz os require_once 'auto_load' dos arquivos
     */
    private function loadFiles($dir, $files = null) {
        if ($files) {
            $this->requireFiles($dir, $files);
        } else {
            echo "<br><br>Nenhum arquivo encontrado no diretorio '" . $dir . "'. Verifique as extensoes e nomes de arquivo e tente novamente.<br><br>";
        }
    }

    /**
     * Faz os require_once 'auto_load' dos arquivos
     * 
     * @param array() $dir Diretorio a ser verificado o(s) arquivo(s)
     * @param array() $files Arquivo(s) a serem buscado(s)
     */
    private function requireFiles($dir, $files) {
        foreach ($files as $file) {
            $arq = $dir . $file;
            
            if (is_file($arq)) {
                require_once $arq;
            } else {
                echo '<br><br>Arquivo: ' . $file[0] . ' na pasta ' . $dir . ' nao foi carregado. Verifique o nome do arquivo e a extensao e tente novamente<br><br>';
            }
        }
    }
}