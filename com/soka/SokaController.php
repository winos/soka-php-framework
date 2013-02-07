<?php
namespace com\soka
{	
	abstract class SokaController
	{		
		public static $_models = array();
		public static $_vars = array();
                
		protected function _init(){}
                
		final public function __construct()
		{
			//View::setLayout('');
			return $this->_init();
		}

		protected function view($view_option)
		{
			View::$_renderAutomatic = (boolean)$view_option;
		}
		public function __set($key, $value)
		{
			if ( ! in_array($key, self::$_vars)) {
				self::$_vars[$key] = $value;
			}
		}

		final public function setLib($lib_name)
		{
			if ( !file_exists(APPLICATION_PATH . '/com/lib/' . $lib_name  . EXT)) {
				throw new \Exception('Libreria : <strong>' . $lib_name . '</strong> no encontrada.');
			}
			 else {				
				require_once (APPLICATION_PATH . '/com/lib/' . $lib_name . EXT);
			}
		}

		final public function setModel($model)
		{
			$model = ucfirst($model) . '_Mdl';
			if ( !file_exists(APPLICATION_PATH . '/app/model/' . $model   . EXT)) {
				throw new \Exception('Mdelo: <strong>' . $model . '</strong> no encontrada.');
			} else {				
				require_once (APPLICATION_PATH . '/app/model/' . $model . EXT);
				$model = new $model;
			}
			return $model;
		}
		protected function Clean($f = 'all') {
			$super =  function  ($string)	{	return strtoupper($string); };
			$html  =  function  ($string)	{ 	return htmlentities($string); };
			$low  =   function  ($string)	{ 	return strtolower($string); };
			$all   =  function  ($string)	{	return strtolower(trim(htmlentities($string))); };
			$null =   function  ($string)	{	return $string; };
			$data  =  array('u'=>$super, 'l'=> $low, 'html'=> $html, 'all'=> $all);
			return (array_key_exists($f, $data))? $data[$f] : $null; 
		}
                
		final protected function _routeTo ($location = '')
		{
			$path =  Config::$BASE_URL  . $location;

			if ( ! headers_sent()) {
				header('Location: ' . $path);
				exit;
			} else {
				self::_redirectTo($location);
			}
		}

		final protected function _redirectTo ($location = "", $seconds = 0)
		{
			$path = Config::$BASE_URL . $location;
			header('refresh: ' . $seconds . '; url=' . $path);
			exit;
		}
		abstract public function index();
	} 
}
?>