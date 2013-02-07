<?php 
/**
* clase para la manipulacion de formularios
* @package com\lib
*/
namespace com\lib
{
    class Form 
    {
        public $_field = array();
        public function __construct()
        {
            $this->_field = $_POST;
        }
        public function validate()
        {
            $data = new \ArrayIterator($_POST);
            while ($data->valid()){
                    if($data->current() == ''){
                            $_SESSION['errror'][] = "El campo ". $data->current() ." debe ser llenado."; 
                            self::$data->current();
                            $data->next();
                    }
            }
        }

        public function __get($key)
        {
            if(array_key_exists($key, $this->_field))
               return self::_clean($this->_field[$key]);
            else 
               return null;
        }

        public static function fieldForm()
        {
            foreach ($_POST as $key => $value){
                    if($key != 'submit') {
                            $_field[$key] = $value;
                    }
            }				
            return $_field;
        }

        private static function _clean($field)
        {
            return  htmlspecialchars(stripslashes(trim($field)), ENT_QUOTES, 'UTF-8');
        }
    }
}
