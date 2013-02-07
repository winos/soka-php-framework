<?php
/**
 * Model - clase para obtener el acceso a datos
 * Soka Framework PHP
 * @author Dawin Ossa / dawinos@gmail.com
 * @package com\soka\
 * @version 1.0
 * @copyright 2012  Dawin Ossa
 */
namespace com\soka 
{
	class DataBase extends \PDO
	{
		static private $db;
		public  function __construct()
        {
            try {                            
            parent::__construct('mysql:host='. Config::$HOST_DB.';dbname='. Config::$NAME_DB, Config::$USER_DB, Config::$PASS_DB);
            } catch (Exception $e) {
                $e->getMessagge();	
            }			
		}               
    }
}