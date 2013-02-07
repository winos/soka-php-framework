<?php 
/**
 * interface para que realizen los modelos del aplicativo
 * @name IModelMapper 
 * @access public
 * @author Dawin Ossa / dawinos@gmail.com
 * @version 1.0
 * @license 
 * @copyright 2012  Dawin Ossa
 * @package com\soka\Interfaces\
 */
namespace com\soka\interfaces
{
	interface IModelMapper
	{
		public function getAll();
		public function add();
		public function getById();	
		public function del();	
	}	
}