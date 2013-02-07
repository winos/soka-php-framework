<?php 
/**
* clase para la manipulacion de reportes
* @package com\lib
*/
namespace com\lib
{
    class Report
    {
    	const EXT_EX = '.xlsx';
    	private $_report_obj;
        private $_concepto;
        static $_nameFile;

        public function __construct($report_obj, $concepto_obj)
        {
        	$this->_report_obj = $report_obj;
            $this->_concepto = $concepto_obj;
            $this->changedConceptsTemplate();
        }

        public function getreport() {
        	return $this->_report_obj;
        }

        public static function pathreport(){
        	return APPLICATION_PATH.'/report/';
        }

        final static public function  makeName($name, $f_ini, $f_fin)
        {
            self::$_nameFile =  APPLICATION_PATH.'/report/nomina['.$name.'] '. $f_ini .' al '.$f_fin.EXT_EX;
        	return APPLICATION_PATH.'/report/nomina['.$name.'] '. $f_ini .' al '.$f_fin.EXT_EX; 
        }

        final static public function  getName()
        {
            self::$_nameFile = str_replace(APPLICATION_PATH, '', self::$_nameFile);

        }


        public function setVal($cell, $row = null, $val, $porcentaje= null ) {
        	$cell_row = (string) $cell . $row;
	        self::getreport()->setActiveSheetIndex(0)->setCellValue($cell_row, $val);
	        return $this;
        } 

        public function BuildInfo()
        {
            $this->getreport()->getProperties()->setCreator("Pewol Macro S.A.S")
                ->setLastModifiedBy("Pewol")
                ->setTitle("Nomina Empleados")
                ->setSubject("PDF Test Document")
                ->setDescription("este documento contiene informacion.")
                ->setKeywords("excel devengado nomina macro")
                ->setCategory("Finanzas");

             return $this;
        }

        public function buildHeader($proyecto= null)
        {
            $text_title = (is_null($proyecto)) ? 'Administrativa' : $proyecto;

            $this->getreport()->setActiveSheetIndex(0)
                ->setCellValue('C1', 'Construcciones Macro S.A.S Nomina '.$text_title)
                ->setCellValue('H1', 'DESDE ' . date('Y/m/d') .' HASTA ' .  date('Y/m/d') );
            
            return $this;
        }


        private static function makeListConcepts() {
            return array( 'deduccion'=>array('salud', 'pension'),
                          'apropiacion'=>array('salud', 'prima', 'vacaciones', 'pension', 'arp','interes_cesantia', 'cesantia'),
                          'parafiscal'=>array('sena', 'icbf', 'ccf'));
        }

        private function changedConceptsTemplate() 
        {
            foreach (self::makeListConcepts() as $key=>$tipo_concepto) {
                $k = $key;
                if($key == 'deduccion')
                    $key = 'D';
                elseif($key == 'apropiacion')
                    $key = 'A';
                else
                    $key = 'P';

                foreach($tipo_concepto as $concepto) {
                    $cpt[strtoupper($key.'_'.$concepto)] = $this->_concepto->value($k, $concepto);
                }
            }
            // self::getreport()->setActiveSheetIndex(1)->getCell('A1')->getCalculatedValue()
            foreach ($cpt as $key => $value) 
                self::getreport()->setActiveSheetIndex(1)->setCellValue($key, $value);

            return $cpt;
        }

        public function setConcepto(\Concepto_Mdl $concepto_obj)
        {
            $this->_concepto = $concepto_obj;
            return $this;
        }
    }
}