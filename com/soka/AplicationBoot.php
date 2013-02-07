<?php
/**
 * permite cargar las determinadas peticiones del usuario capturadas en el objeto
 * Request arrancando el sistema y generando las instancia para el funcionamiento 
 * de este
 * Soka Framework PHP
 * @name  AplicationBoot
 * @access public
 * @author Dawin Ossa / dawinos@gmail.com
 * @package com\soka\
 * @version 1.0
 * @license 
 * @see com\soka\Request.php
 * @copyright 2012  Dawin Ossa
 */
namespace com\soka
{
	use app\controller as sokaCtl;
	final class AplicationBoot
	{
        /**
         * corre las diversas peticiones que se obtiene del objeto Request
         * creando asi la instancia de controlador y cacheando sus metodos
         * @param Request $rq objeto que captura info de la URI
         * @throws \Exception 
         * @return void
         */
		public static function _execute(Request $rq)
		{
			$controller = $rq->getController();
			if ( ! file_exists ( APPLICATION_PATH . '/app/controller/' . ucfirst($rq->getController())  . EXT))
				throw new \Exception("Controlador: <strong>{$rq->getController()} </strong> no encontrado :D");
			else 
				require_once (APPLICATION_PATH . '/app/controller/' . ucfirst($controller)  . EXT);

			$controller = new $controller();
			if(is_callable(array($controller, $rq->getMethod())))
				$metodo = $rq->getMethod();
			else
				$metodo = 'index';
			if(!is_callable(array($controller, $rq->getMethod())))
				throw new \Exception("Metodo: <strong>{$rq->getMethod()}</strong> No encontrado en el Controlador {$rq->getController()}");
			
			if( count($rq->getParam()) > 0 ) 
				call_user_func_array(array($controller, $metodo), $rq->getParam());
			else 
				call_user_func(array($controller, $metodo));
			
			if (View::$_renderAutomatic) {
				$render_view_automatic = str_replace('_Ctl', '', $rq->getController());
				if ( $render_view_automatic == 'Index' ) 
					View::show($render_view_automatic); 
				else 
					View::show($render_view_automatic. '/' .$metodo);
			}			
		}
	}
}