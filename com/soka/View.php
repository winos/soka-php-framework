<?php
/**
 * clase para la manipulacion de los contenidos que seran pasados de un
 * determinado controlador al usuario, controla los layouts de la aplicacion
 * cambio de css,js, menus etc.
 * Soka Framework PHP.
 * @name View
 * @author Dawin Ossa / dawinos@gmail.com
 * @version 1.0
 * @package soka\com
 * @copyright 2012 Dawin Ossa
 */
namespace com\soka
{
	class View 
	{
		private static $_vars;
		private static $_layout;
		private static $_js = array();
		private static $_css = array();
		public 	static  $_renderAutomatic = true;
        private static $_page = null;
        private static $_menu;

        public function __construct(){

        }

        /**
         * cambia el laout de la aplicacion
         * @param String $layout nombre del layout
         * @param Array $menu menu preconstruido para dicha vista 
         * @access public
         * @return void 
         */
		public static function setLayout ($layout = 'default',array $menu = null)
		{
			self::$_layout = $layout;
		}
                
        public static function content()
        {
            echo self::$_page;
        }
        
        public static function snippet($snipet_name)
        {
                if ( ! empty(self::$_vars)) {
                        extract(self::$_vars, EXTR_OVERWRITE);
                }

                if ( ! @ include_once ('app/snippets/' . $snipet_name . TPL) ) {
                        throw new \Exception('Snipet: ' . $snipet_name . ' no encontrado.');
                }
        }
        /**
         * @access public 
         * @param String $view_name nombre de la vista
         * @param boolean $render_automatic muestra automaticamente la vista
         * @param boolean $blank incluye vista sin un layout preestablecido 
         * @throws \Exception error al incluir determinada vista
         * @return void
         */
		public static function show( $view_name, $render_automatic = true, $blank = false)
		{
			
            if (ob_get_level()) {
                ob_end_clean();	
            }
            ob_start();
                        
			self::setJs(array(
				'menu', 'jquery-ui', 'crud', 'plugin/jquery.tablesorter.pager','plugin/jquery.tablesorter.min', 'plugin/jquery.timepicker', 'plugin/jquery.validate')
			);
			self::setCss(array(
				'bootstrap', 'jquery.tablesorter.pager','default',
				 'menu','reset', 'themes/blue/style','jq-ui/jquery-ui-1.8.19.custom'));

			self::$_renderAutomatic = $render_automatic;

			if ( !file_exists(APPLICATION_PATH . '/app/view/' . $view_name  . TPL)){
				throw new \Exception('Vista: <strong>' . $view_name . '</strong> no encontrada.');
			} else {
				self::$_vars = SokaController::$_vars;
				if ( ! empty(self::$_vars)) {
					extract(self::$_vars, EXTR_OVERWRITE);
				}
				$data_layout = self::dataOfLayout();
                include_once (APPLICATION_PATH . '/app/view/' . $view_name  . TPL);  
                self::$_page = ob_get_clean();             
                if ($blank) {
                     if ( ! @ include_once (APPLICATION_PATH .'/app/layouts/blank'  . TPL)) {
                        throw new \Exception('Layout: <strong> Blank </strong> no encontrado.');
                    }
                }
                else {
                	 //self::$_page = ob_get_clean();
                    if ( ! @ include_once (APPLICATION_PATH .'/app/layouts/' . self::$_layout . TPL)) {
                        throw new \Exception('Layout: <strong>' . self::$_layout. '</strong> no encontrado.');
                    }
				}
			}
		}

		/**
		 * asigna los nuevos archivos javascript de la aplicación
		 * @param  string $file
		 * @return void
		 */
		public static function setJs($file)
		{
			if(is_array($file)){
				foreach ($file as $value) {
					self::$_js[] = Config::$BASE_URL. 'public/javaScript/'. $value . JS;
				}
			} else {
				self::$_js[] = Config::$BASE_URL. 'public/javaScript/'. $file . JS;
			}		
		}

		/**
		 * asigna los nuevos archivos css de la aplicación
		 * @param  string $file
		 * @return void
		 */
		public static function setCss($file)
		{
			if(is_array($file)){
				foreach ($file as $value) {
					self::$_css[] =  base_url(). 'public/styleSheet/'. $value . ST;
				}
			} else {
				self::$_css[] =  base_url(). 'public/styleSheet/'. $file . ST;
			}	
			return;	
		}
                
        /**
         * establece las rutas de los archivos de la parte de diseño de
         * la aplicación
         * @access public
         * @return void 
         */		
		public static function dataOfLayout()
		{
			$js = array();
			if(isset(self::$_js)){
				$js = self::$_js;
			}
			$data = array(
					'js_default' => array( 
								'jquery'=> Config::$BASE_URL. 'public/javaScript/jquery.js',
								'ajax'=>Config::$BASE_URL. 'public/javaScript/ajax.js'),
					'js_include'=> self::$_js,
					'css_include' => self::$_css,
					'img' =>  base_url () . 'app/layouts/'.self::$_layout.'/images/',
					'menu' => self::showMenu()
					);
			return $data;			
		}
                
		public static function showMenu($menu = null)
		{
			/*
			if(!is_array($menu) && is_null($menu))
				$menu_layout = array(0=>array('name'=>'inicio', 'link'=>Config::$BASE_URL),
									 1=>array('name'=>'user', 'link'=>Config::$BASE_URL.'user/'),
									 2=>array('name'=>'post', 'link'=>Config::$BASE_URL.'post/'),
									 3=>array('name'=>'reporte', 'link'=>Config::$BASE_URL.'user/report/')
									);
			else 
				$menu_layout = $menu;*/

			$j = (isset($_SESSION['user']['user_rol_id'])) ? $_SESSION['user']['user_rol_id'] : 1;
			
			return \app\helpers\PewolMenu::getMenu($j);
			//return self::$_menu;
		}
	}
}