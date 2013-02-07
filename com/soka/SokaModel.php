<?php
/**
 * clase para obtener el acceso a datos, abstrayendo metodos comunes en los
 * aplicativos como el C.R.U.D provee una instancia de conexion de clase PDO:mysql
 * Soka Framework PHP
 * @name Model
 * @access public
 * @author Dawin Ossa / dawinos@gmail.com
 * @package com\soka\Model
 * @version 1.0
 * @see com\soka\DataBase.php
 * @see com\soka\interfaces\ImodelMapper.php
 * @copyright 2012  Dawin Ossa
 */
namespace com\soka
{
	use com\soka\Interfaces as iM;
	use com\soka\DataBase;
	abstract class SokaModel implements iM\IModelMapper
	{
		/**
		* contiene la instancia de la clase DataBase, la cual sera vigente
		* para todos los sucesores de esta clase
		* @see com\soka\Database.php
		* @var DataBase $_db
		*/
		protected $_db;

		/**
		 * @var jqGridRender $_dgrid objeto tipo jqGrid
		 */
		 protected $_dgrid;
		/**
		* inicializa el objeto, e instancia la base de datos y las 
		* clase que deriven de esta su constructor sera determinado por
		* _init()
		* @access public
		* @return _init 
		*/
		public function __construct()
		{
			$this->_db = new DataBase;
			return $this->_init();
		}
    /**
    * constructor predefinido para los sucesores de esta clase.
    * @return void 
    */
    protected function _init(){}
            /**
                * metodo magico para activar los set y get automaticamente, de
                * determinado Modelo de la aplicación
                * @param String $name
                * @param String, void, etc $arg
                * @return \com\soka\Model 
                * @example $this->modelo->age() :get ,$this->modelo->age(3) :set
        */
		public final function __call($name, $arg)
		{
			if(count($arg)> 0 )
				$this->$name = $arg[0];
			else 
				return $this->$name;
			return $this;
		}
                /**
                 * realiza cualquier consulta que se establesca
                 * @param String $sql consulta que sera enviada al motor
                 * @return boolean
                 */
		private function query($sql)
		{
			return $this->_db->query($sql);
		}
        /**
         * obtiene las columnas de determinada tabla, para mapear 
         * posteriormente estas como atributos de clase
         * @param String $table
         * @return array 
         */
		public function columns($table)
		{
			if ($rscResult = self::query("SHOW COLUMNS FROM {$table}")) {
				$arrFields = array();
				while ($col = $rscResult->fetch(\PDO::FETCH_ASSOC) ) 
					$arrFields[$col['Field']] = $col['Field'];				
				//mysql_free_result($rscResult); : liberar resultado Ojo ahorrar memoria
				return $arrFields;
			} else {
				return false;
			}
		}


		public function showlist($field, $id_selected = null, $enable= FALSE)
		{
			$readOnly = ($enable) ? 'TRUE' : 'FALSE';
			 $list = '<select name="list_'.self::classToTable($this).'" id="cbo_'. self::classToTable($this) .'">';
			 $list .= '<option value= "0"> Seleccionar </option>';
			foreach (self::getAll() as $key => $value)
				if (!is_null($id_selected) && $key == $id_selected)
					$list .= '<option value= "'.$key.'" selected> '. $value[$field].' </option>';
				else 
					$list .= '<option value= "'.$key.'" > '. $value[$field].' </option>';
			$list .= '</select>';
			return $list;
		}
		public function showlist_dos($num,$tipo,$field, $id_selected = null, $enable= FALSE)
		{
			$readOnly = ($enable) ? 'TRUE' : 'FALSE';
			 $list = '<select name="list_'.self::classToTable($this).''.$num.'" id="cbo_'. self::classToTable($this) .'">';
			 $list .= '<option value= "0"> Seleccionar </option>';
			foreach (self::getAlltres($tipo) as $key => $value)
				if (!is_null($id_selected) && $key == $id_selected)
					$list .= '<option value= "'.$key.'" selected> '. $value[$field].' </option>';
				else 
					$list .= '<option value= "'.$key.'" > '. $value[$field].' </option>';
			$list .= '</select>';
			return $list;
		}
                
		public function create ($strClass)
		{
			$obj = new $this; //\stdclass(); //$strClass();
			foreach (self::columns($strClass) as $key => $field) {
				$obj->$key = $field;
			}			
			return $obj;
		}
        /**
         * obtiene por id determinado registro que se encuentre en la BD,
         * establenciendo antes el atributo id a traer
         * @example $this->modelo->id(2)->getBydId();
         * @return \com\soka\Model|boolean 
         */
		public function getById()
		{
			$data_generic = self::getAll();
			if(array_key_exists($this->id, $data_generic)){
				foreach ($data_generic[$this->id] as $key => $value) 
					$this->$key = $value;				
				return $this;
			} else {
				return false;
			}			
		}
        /**
         * obtiene todos los registros del modelo en que se encuentre
         * @access public
         * @param array $fields conjunto de campos que requiero
         * @param array $conditions condiciones para traer los registros 
         * @return array 
         */
		public function getAll($model_independiente = null, $fields = null, $conditions = null) 
		{
			if(!is_null($model_independiente)) 
				$table = $model_independiente;
			else
				$table = self::classToTable($this);
				
			$sql_stm = '';
			$get_field = is_array($fields) ? implode($fields, ',') : '*';
			$sql_stm = "SELECT {$get_field} FROM {$table} ";
			if (is_array($conditions)){
				$sql_stm .= " WHERE ";
				foreach ($conditions as $key => $value) {
					$sql_stm .= $key . " LIKE '%". $value."%'";//"  AND ";
				}
			}
			$query = self::query($sql_stm);
			if($query)
				while($result = $query->fetch(\PDO::FETCH_ASSOC))
					$data[$result['id']] = $result;
			return $data;
		}
		public function getAlltres($tipo,$model_independiente = null, $fields = null, $conditions = null) 
		{
			if(!is_null($model_independiente)) 
				$table = $model_independiente;
			else
				$table = self::classToTable($this);
				
			$sql_stm = '';
			$get_field = is_array($fields) ? implode($fields, ',') : '*';
			$sql_stm = "SELECT {$get_field} FROM {$table} ";
			if (is_array($conditions)){
				$sql_stm .= " WHERE ";
				foreach ($conditions as $key => $value) {
					$sql_stm .= $key . " LIKE '%". $value."%'";//"  AND ";
				}
			}
			$sql_stm .= " WHERE tipo='".$tipo."'AND estado=1";
			$query = self::query($sql_stm);
			if($query)
				while($result = $query->fetch(\PDO::FETCH_ASSOC))
					$data[$result['id']] = $result;
			return $data;
		}
		/**		Carga solo los que estan activoz
		**/
		public function getAllDos($model_independiente = null, $fields = null, $conditions = null) 
		{
			if(!is_null($model_independiente)) 
				$table = $model_independiente;
			else
				$table = self::classToTable($this);
				
			$sql_stm = '';
			$get_field = is_array($fields) ? implode($fields, ',') : '*';
			$sql_stm = "SELECT {$get_field} FROM {$table} ";
			if (is_array($conditions)){
				$sql_stm .= " WHERE ";
				foreach ($conditions as $key => $value) {
					$sql_stm .= $key . " LIKE '%". $value."%'";//"  AND ";
				}
			}
			$sql_stm .= " WHERE estado=1 ";
			$query = self::query($sql_stm);
			if($query)
				while($result = $query->fetch(\PDO::FETCH_ASSOC))
					$data[$result['id']] = $result;
			return $data;
		}
        /**
         * convierte deerminado Modelo a el nombre de tabla 
         * @access private
         * @param type $class nombre de clase la cual sera mapeada
         * @return String 
         */
		private function classToTable($class)
		{
			return $table = str_replace('_mdl', '',  strtolower(get_class($class)));
		}
 
        /**
         * agrega y/o actualiza dinamicamente determinado registro, si 
         * se establece en los atributos de modelo la propiedad $dinamic
         * este obtendra los campos de formulario y los convertira a 
         * atributos del propio Modelo
         * @access public
         * @example update: $this->modelo->id(1)->nombre('soka')->add();
         * @example insert: $this->modelo->nombre('soka')->add();
         * @return \com\soka\Model
         */
		public function add()
		{
			$table = self::classToTable($this);
			$values = self::columns($table);
			foreach ($values as $key => $field) {
				if ($key != 'id') {
					$val = isset($this->$key) ? $this->$key : null;
					$vals[$key] = $val;
					$keys[] = "`" . $key . "`";
					$set [] = "`$key` = '$val'";
				}
			}
			if(!property_exists($this, 'dinamic')) {
				foreach ($vals as $key => $value) {
					$v[] = "'".strtolower($value)."'";
				}
			} else {
				$values = Form::fieldForm();
				foreach ($vals as $key => $value) {
						$this->$key = $values[$key];
						$v[] = "'".strtolower(
							$this->$key)."'";
				}				
			}
			$index = '';
			if (isset($this->id)){
				$index = 'update';
				$sql['update'] = "UPDATE `$table` SET " . implode($set, ", ") . " WHERE id='".$this->id."'";
                        } else {
				$index = 'insert';
				$sql['insert'] = "INSERT INTO `$table` (".implode($keys, ", ").") VALUES (".implode($v,", ") . ")";
			}
			return self::query($sql[$index]);
			//$this;
		}

		 /**
         * ejecuta la eliminacion de un registro
         * @access public
         * @return boolean 
         */
		public function del($active = true, $delete_direct = false)
		{
			return $this->_destroy($active, $delete_direct);
		}
        /**
         * elimina de acuerdo a un id establecido el registro de la BD
         * @access private 
         * @return boolean 
         */
		final private  function _destroy ($active, $direct)
		{
			$table = self::classToTable($this);
			if(is_array($this->id)) 
				$sql_in = "IN (". implode($this->id, ',') . ")";
			else 
				$sql_in = "= {$this->id}";		
			$estado = ($active) ? 1 : 0;
			$query = ($direct) ? "DELETE FROM {$table} WHERE id $sql_in":
					 "UPDATE {$table} SET `estado` = {$estado} WHERE id $sql_in";
			return self::query($query);
		}
		

		final public function useModel($model)
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

		public function __toString()
		{
			return  __CLASS__;	
		}		
	}
}