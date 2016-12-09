<?php

/**
 *
 * Classe para fazer verificacao de arquivos e diretorios
 *
 * @author maikysilva
 *
 */
class FileAndDir {
    private static $dir;
    private static $exts;

    /**
     * Salva o diretorio atual para fazer busca dos arquivos nele
     *
     * @param String $dir Caminho do diretorio
     */
    public static function setDir($dir) {
        static::$dir = $dir;
    }

    /**
     * Seta as extensaoes aceitas na variavel $exts.
     * As extensoes precisam vir com o '.' antes do tipo. Ex: .php
     *
     * @param array() $exts Extensoes de arquivos aceitas
     */
    public static function setExtensions($exts) {
        if (! empty($exts)) {
            foreach ($exts as $ext) {
                static::$exts[] = strtolower($ext);
            }
        }
    }

    /**
     * Busca todos os arquivos que existem dentro desse diretorio
     */
    public static function getFiles() {
        //pega todos os arquivos e pastas do diret�rio
        $files = scandir(static::$dir);
        $allFiles = null;

        if (empty(static::$exts)) {
            foreach ($files as $key => $file) {
                //monta o caminho com o nome do arquivo
                $f = static::$dir . $file;

                if (static::validateFile($f)) {
                    $allFiles[] = $file;
                }
            }
        } else {
            foreach ($files as $key => $file) {
                //monta o caminho com o nome do arquivo
                $f = static::$dir . $file;

                if (static::validateFile($f) && $fil = static::validateExtension($file)) {
                    $allFiles[] = $fil;
                }
            }
        }

        return $allFiles;
    }

    /**
     * Verifica se o diretorio passado existe
     *
     * @param String $dir Nome do diretorio a ser verificado
     */
    public static function validateDir($dir) {
        if (is_dir($dir)) {
            return true;
        }
        return false;
    }

    /**
     * Valida se o arquivo existe e se ele nao e uma pasta oculta
     *
     * @param String $file Caminho completo do arquivo
     * @return boolean Arquivo valido ou invalido
     */
    public static function validateFile($file) {
        if (is_file($file) && ($file != '..') && ($file != '.')) {
            return true;
        }
        return false;
    }

    /**
     * Faz validacao se a extensao sendo passada
     * e compativel com a(s) definida(s) pelo usuario
     *
     * @param String $extFile Arquivo a ser verificada a extensao
     * @return array() Todos os arquivos com extensao valida
     */
    private static function validateExtension($file) {
        preg_match('@^[a-zA-Z]+\.([a-zA-z]+)@', $file, $extFile);

        if (! empty($extFile)) {
            $extFile = '.' . strtolower($extFile[1]);
        }

        if (in_array($extFile, static::$exts)) {
            return $file;
        } else {
            echo '<br><br>Extensao do arquivo incompativel com o aceito, arquivo: ' . $file . '<br><br>';
            return false;
        }
    }
}