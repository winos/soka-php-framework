<?php
use \com\soka as sk;

defined('PS') || define('PS', PATH_SEPARATOR);
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

defined('APPLICATION_PATH') || define('APPLICATION_PATH',  str_replace('\\', '/',realpath('./'))); //ROOT
define('EXT', '.php');
define('EXT_EX', '.xlsx');

define('TPL_NOM', APPLICATION_PATH."/plantilla/nom_obrera.xlsx");
define('TPL_NOM_ADM', APPLICATION_PATH."/plantilla/nom_admin.xlsx");

define('TPL', '.soka');
define('JS', '.js');
defined('ST') || define('ST', '.css');

$paths = array( APPLICATION_PATH, get_include_path());
set_include_path(implode(PS, $paths));

function __autoload($class_name)
{
	if(file_exists($class_name.EXT))
    	require_once $class_name . EXT;

    if (file_exists(str_replace('_', '/', $class_name.EXT))) 
    	require_once str_replace('_', '/', $class_name.EXT);
    
}

$URI = str_replace(str_replace('\\', '/', DS.basename(dirname(__FILE__)).DS), '',$_SERVER['REQUEST_URI']);
defined('URI_PATH') || define('URI_PATH', htmlspecialchars(trim($URI, '/'), ENT_QUOTES, 'UTF-8'));
# funcion para obtener el raiz de mi aplicacion para la inclucion de archivos
if(!function_exists('base_url')) {
	function base_url () {
	    $base_url = sk\Config::$BASE_URL;
	    return $base_url;
	}
}

# ejecuta el arranque del core
try {
    $boot = sk\AplicationBoot::_execute(new sk\Request());
} catch (Exception $e) {
    echo "<h3 style='background:#333; color: #FFF; padding:1em'>". $e->getMessage(). "</h3>";	
}
exit;
?>