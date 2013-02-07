<?php
/**
 * Request - clase para obtener el acceso a la Url y realaizar un parseo de esta
 * en la que se encuentra el usuario
 * Soka Framework PHP
 * @author Dawin Ossa / dawinos@gmail.com
 * @package com\soka\
 * @version 1.0
 * @license 
 * @copyright 2012  Dawin Ossa
 */
namespace com\soka
{
	final class Request
	{
		public static $_controller;
		public static $_method;
		public static $_arguments;
		public static $_paths = array('mod'=> null,'ctr'=> null,'mtd'=> null,'arg'=>null);
		
		public function __construct()
		{
			self::assigmentUrlToPath();
		}
		/**
		 * Toma la url y la parsea.
		 * @param string $url
		 * @return void
		 */
		public final function parserUrl($uri)
		{
			$uri = explode("/", $uri);		
			$uri = array_filter($uri);
			array_walk($uri , 'self::low');
			return $uri;
		}
	
		public function getController()
		{
			return self::$_controller = (isset(self::$_paths['ctr'])) ? ucfirst(self::$_paths['ctr']) . '_Ctl' : 'Index_Ctl';
		}

		public function getMethod()
		{
			return self::$_method= (isset(self::$_paths['mtd'])) ? self::$_paths['mtd'] : 'index';
		}

		public function getParam()
		{
			return self::$_arguments= (isset(self::$_paths['arg'])) ? self::$_paths['arg'] : array();
		}

		private static function low(&$data)
		{
			return $data = strtolower($data);
		}
		private static function assigmentUrlToPath()
		{
			$info_uri = self::parserUrl(URI_PATH);
			self::$_paths['ctr'] = array_shift($info_uri);
			self::$_paths['mtd'] = array_shift($info_uri);
			self::$_paths['arg']=  $info_uri;
		}
	}
}