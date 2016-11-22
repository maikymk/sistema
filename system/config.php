<?php
/**
 ***************************************************************
 *********** Definicao de constantes de configuracao ***********
 ***************************************************************
 */

/**
 * Define o tipo da barra
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Define o diretorio raiz
 */
define('DIR_RAIZ', '.'.DS);

/**
 * BANCO DE DADOS
 */
define('HOST',     'localhost');
define('USER',     'root');
define('PASSWORD', '');
define('BD',       'sistema');
define('PORT',     3306);

/**
 * Define a url inicial do site
 */
define('BASE', 'http://localhost'.DS.'sistema'.DS);

/**
 * Define o controller padrão do site
 */
define('CONTROLLER_PADRAO', 'Home');

/**
 * Classe que faz conexao e consultas no BD
 */
define('QUERY', DIR_RAIZ.'SYSTEM'.DS.'query.php');

/**
 * CONFIGURACOES DE CACHE
 */
//define o tipo da sessao
define('CACHE_USER_TYPE', 'nocache');
//define o tempo para expirar a sessao, em minutos
define('CACHE_USER_EXPIRES', 15);

/**
 * PASTA PADRÃO DOS TEMPLATES
 */
define('TEMPLATES', DIR_RAIZ.'templates'.DS);

/**
 * Tela de login
 */
define('TELA_LOGIN', TEMPLATES.'telaLogin.php');

/**
 * Tela de nova conta
 */
define('TELA_NOVA_CONTA', TEMPLATES.'telaNovaConta.php');
define('IDADE_MINIMA_NOVA_CONTA', date('Y-m-d', strtotime('-18 years')));

/**
 * PASTA COM AS TELAS DE ERRO
 */
define('TELAS_ERRO', TEMPLATES.'telas_erro'.DS);

/**
 * ERRO PADRAO DE REQUISICAO
 */
define('ERRO_PADRAO', '500');

/**
 * PASTA DE IMAGENS
 */

//define a pasta padrao de imagens
define('IMAGES', DIR_RAIZ.'images'.DS);
//define a pasta padrao de imagens de ero
define('IMAGES_ERRO', IMAGES.'erro'.DS);

/**
 * PASTA DE CSS
 */
define('CSS', DIR_RAIZ.'css'.DS);

/**
 * PASTA DE JS
 */
define('JS', DIR_RAIZ.'js'.DS);

/**
 * PASTA DE COMPONENTES
 */

//Pasta onde ficam os componentes
define('APP', DIR_RAIZ.'app'.DS);
//interface dos componentes
define('INTERFACE_APP', APP.'Interface'.DS);
//abstract dos componentes
define('ABSTRACT_APP', APP.'Abstract'.DS);

/**
 * PASTA DE HELPERS
 */

//define a pasta dos helpers
define('HELPER', DIR_RAIZ.'helper'.DS);

/**
 * PASTA DE BIBLIOTECAS
 */

//define a pasta das bibliotecas
define('LIB', DIR_RAIZ.'lib'.DS);

/**
 * PASTA SYSTEM
 */

//define a pasta das bibliotecas
define('SYSTEM', DIR_RAIZ.'system'.DS);