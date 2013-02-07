<?php
namespace com\lib 
{
    class Session 
    {    
        public function __construct()
        {
            session_start();
        }
        public static function run()
        {
            //session_start();
        }       
        static public function acceso($level){
            if(!self::get('autenticado')){
                header('location: '. base_url() .'error/acceso');
                exit;
            }
            $i = 0;
            if(is_array($level)) {
                foreach ($level as $val) {
                    if(self::level($val) > self::get('level')) {
                        $i++;    
                    }
                }
                if($i > count($level)-1){
                    header('location: '. base_url() . 'error/acceso');
                    exit;
                }
            }
        }
        static public function accesView($level){
            if(!self::get('autenticado')){
                return false;
            }
            if(self::level($level) > self::get('level')){
                return false;
            }
            return true;
        }
        
         public static function level($level) {
            $rol = array('admin'=>3, 'secretaria'=>2, 'jefe'=>1 );
            if(!array_key_exists($level, $rol))
                throw new \Exception('error de acceso nivel no encontrado!');
            else
                return $rol[$level];
        }

        static public function get($key)
        {
            if(self::_verify($key)) 
                return $_SESSION[$key];
        }
        
        static public function set($key, $val)
        {
            if(!self::_verify($key)) {
                $_SESSION[$key] = $val;
            } else {
                $_SESSION[$key] = $val; 
            }
        }
        
        static public function destroy($key=false)
        {
            if($key) {
                if(is_array($key)){
                    foreach ($key as $value) {
                        if(self::_verify($value)){
                            self::_unset($value);
                        }
                    }
                } else {
                    if(self::_verify($key)){
                        self::_unset($key);
                    }
                }
            }   else {
                session_destroy();
            }         
        }
        
        private function _unset($key)
        {
            unset($_SESSION[$key]);             
        }
        
        private function _verify($key){
            return (isset($_SESSION[$key])) ? true : false;
        }
    }  
}
