<?php
namespace com\lib
{
	use com\soka as Soka;
	class user extends Soka\Model
	{
		protected $firstName;
		protected $address;
		protected $lastName;
		protected $age;
		protected $date;
		public function __construct()
		{
			return $this;
		}
	}
}
/**
* incorporarla en cualquiera de los Controladores de dicha forma
* @example 		
* $libUser = new Lb\User();
* $this->firsn = $libUser->firstName("German")->lastName('Varon');
*/