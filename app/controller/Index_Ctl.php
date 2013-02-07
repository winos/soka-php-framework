<?php 
use com\soka as Soka;
class Index_Ctl extends Soka\SokaController
{
    public function _init()
    {
        session_start();
        com\lib\Session::acceso(array('admin', 'secretaria'));
    }
    public function index()	{}
}