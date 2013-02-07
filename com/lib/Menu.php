<?php 
use com\soka\SokaModel
class Menu extends SokaModel
{
	
	public function __construct($user)
	{
		
	}
	public static function get_menu_item(){
		$this->_db->query('sql');
		return $row;
	}
}