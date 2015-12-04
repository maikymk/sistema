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
 * Define onde sera armazenado os dados - BD|JSON
 */
define('SALVA_DADOS', 'bd');

/**
 * BANCO DE DADOS
 */
define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', '');
define('BD', 'sistema');
define('PORT', 3306);

/**
 * Define a pasta do site
 */
define('SITE', DIR_RAIZ.'sistema'.DS);

/**
 * Define a url inicial do site
 */
define('BASE', 'http:'.DS.DS.'localhost'.DS.'sistema'.DS);

/**
 * Classe que faz conexao e consultas no BD
 */
define('QUERY', DIR_RAIZ.'SYSTEM'.DS.'query.php');

/**
 * CONFIGURACOES DE CACHE
 */

//define o tipo da sessao
define('CACHE_USER_TYPE', 'nocache');
//define o tempo para expirar a sessao
define('CACHE_USER_EXPIRES', 1);

/**
 * Tela de login
 */
define('TELA_LOGIN', DIR_RAIZ.'templates'.DS.'telaLogin.php');

/**
 * Pagina padrao de acesso
 */
define('PAGINA_PADRAO', 'Categorias');

/**
 * Tela de nova conta
 */
define('TELA_NOVA_CONTA', DIR_RAIZ.'templates'.DS.'telaNovaConta.php');
define('DATA_MINIMA_NOVA_CONTA', date('Y-m-d', strtotime('-18 years')));

/**
 * PASTA COM AS TELAS DE ERRO
 */
define('TELAS_ERRO', DIR_RAIZ.'templates'.DS.'telas_erro'.DS);

/**
 * ERRO PADRAO DE REQUISICAO
 */
define('ERRO_PADRAO', '500');

/**
 * PASTA DE IMAGENS
 */

//define a pasta padrao de imagens
define('IMAGES', DIR_RAIZ.'images'.DS);
//define a pasta padrao de imagens dos usuario
define('IMAGES_USERS', IMAGES.'usuarios'.DS);
//define a pasta padrao de imagens de ero
define('IMAGES_ERRO', IMAGES.'erro'.DS);

/**
 * PASTA DE CSS
 */
define('CSS', BASE.'css'.DS);

/**
 * PASTA DE JS
 */
define('JS', BASE.'js'.DS);

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

/**
 * PASTA DO WEBSERVICE
 */

//define a pasta do webservice
define('WEB_SERVICE', DIR_RAIZ.'webservice'.DS);

/**
 * ARQUIVO JSON
 */
define('JSON', DIR_RAIZ.'json'.DS.'bd.json');

/**
 * Tamanho maximo da descricao do lancamento
 */
define('TAM_MAX_DESC', 100);