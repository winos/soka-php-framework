<?php 
namespace com\lib\grid
{
	use com\soka as soka;
	class GridSoka extends soka\SokaModel
	{
		private $_hide_pk_col = true;
		private $hide_cols = array();
		private $table = '';
		private $_primary_key   = '';
		private $_headings = array();
		private $_tbl_fields = array();
		private $command_accion = array();
		private $_dataSource;
		private $_controller;
		
		public function __construct($table_name, array $data_source, $primary_key = 'id')
		{
			parent::__construct();
			if (!in_array($primary_key , (parent::columns($table_name))) ) {
				throw new \Exception("campo con primary key: '$primary_key' no se encuentra en la tabla '$table_name'");
			}
			$this->_primary_key = $primary_key;
			$this->table = $table_name;
			$this->_dataSource = $data_source;
		}

		public function setCmd($commands)
		{
			if(is_array($commands)) $this->command_accion = $commands;
			return  $this;
		}

		public function setHeadings(array $_headings){
   			$this->headings = array_merge($this->headings, $_headings);
		}

		public function ignoreFields(array $fields){
			foreach($fields as $f){
				if($f!=$this->pk_col)
			 		$this->hide_cols[] = $f;
			}
		}
		public function showPk($bool){
   			$this->_hide_pk_col = (bool)$bool;
   			return $this;
		}

		private function _selectFields() {
			foreach(parent::columns($this->table) as $key => $field){
				if ($key == $this->_primary_key && !$this->_hide_pk_col)  continue;
				$this->_headings[] = $field;
			}
			if(!empty($this->_headings))	
				$id['check_all'] = "<input type='checkbox' class='dg_check_toggler' name='check_all' >";
				array_unshift($this->_headings, $id['check_all']);
 
   		}

   		private function _generateHeaders()
   		{
			$headers = "<thead> \n";
			foreach ($this->_headings as $key => $value) 
				$headers .= '<th>' . $value . '</th>';
			$headers .= "</thead> \n";
	   		return $headers;
   		}
   		private function _generateTable()
   		{
   			self::_selectFields();
   			$table  = "<div id='grid'> <table> \n <tbody> \n ";
			$table .= self::_generateHeaders();
   			$rows = $this->_dataSource;
   			for($i = 0; $i < count($rows); $i++) {
   				$table .= "<tr> \n <td><input type='checkbox' class='dg_check_item' name='dg_item[]' value='".$rows[$i]['id']."'> </td>";
   				foreach ($this->_headings as $key => $value){
   					if($key != 'check_all' )
   						$table .= '<td>'. $rows[$i][$value] .'</td>';
   				}
   				$table .= "</tr> \n";
   			}		   			
   			$table .= "</tbody> \n </table> \n </div> \n";
   			$table .= "<div id='actions'>". self::_generateActionsGrid(). "</div> ";
   			//self::_generateActionsGrid();
   			return $table;
   		}

   		private function _generateActionsGrid()
   		{
   			$actions = array('del'=>'eliminar', 'add'=>'agregar', 'mod'=>'modificar', 'search'=>'buscar');
   			$action_grid = "\n <div> \n <ul id='actions_grid'>";
   			foreach ($this->command_accion as $key=>$value) 
   				if(array_key_exists($key, $actions) && ($value))
   					$action_grid .= '<li>' . self::createButton($key, strtoupper($actions[$key])) ."</li> \n";
   			$action_grid .= "</ul> \n</div> \n";
   			return (string) $action_grid;
   		}


   		public static function createButton($action_name, $label){
      		return (string)"<input type='submit' class='$action_name' name='dg_action[$action_name]' value='$label'/>";
   		}	



		public function gridView(){
			/*$this->_selectFields();
			$rows = $this->CI->db
			->from($this->tbl_name)
			->get()
			->result_array();
			foreach($rows as &$row){
				$id = $row[$this->pk_col];

				// prepend a checkbox to enable selection of items
				array_unshift($row, "<input class='dg_check_item' type='checkbox' name='dg_item[]' value='$id' />");

				// hide pk column?
				if($this->hide_pk_col){
					unset($row[$this->pk_col]);
				}
			}
			$this->CI->table->generate($rows);*/
			return print(self::_generateTable()); 
		}

		public function deletePostSelection(){
			return (!empty($_POST['dg_item'])) ? $this->id($_POST['dg_item'])->del(true) : false;
		}



	}
}