<?php
/**
 * helper para la creacion de menu dinamico
 * @author dawin ossa <dawinos@gmail.com>
 */
use \com\soka as sk;
namespace app\helpers
{
	class HelpTest extends \com\soka\SokaController
	{
		public function _init(){
			session_start();
		}
		public function index(){}

		public static function getMenu($rol_assigment_pages) {
			$menu_model = parent::setModel('pages');
			return  self::_getMenu(0, $menu_model->get_menu_item($rol_assigment_pages));
		}

		private function _getMenu($parent, $menu, $sub= false) {
			$html ='';
			$html .= (!$sub) ? "<ul id='nav'>":"<ul class='submenu'>";
			foreach ($menu['parents'][$parent] as $itemId)
			{
				if(!isset($menu['parents'][$itemId]))
			    	$html .= "<li>\n  <a href='". base_url() . $menu['items'][$itemId]['link']."'>".$menu['items'][$itemId]['title']."</a>\n</li> \n";
			  	if(isset($menu['parents'][$itemId]))
			  	{
			  		$sub = true;
			     	$html .= "<li> <a href='".$menu['items'][$itemId]['link']."'>".$menu['items'][$itemId]['title']."</a> \n";
			     	$html .= self::_getMenu($itemId, $menu, $sub);
			     	$html .= "</li>";
			  	}
			}
			$html .= "</ul>";
			return $html;
		}
	}
}