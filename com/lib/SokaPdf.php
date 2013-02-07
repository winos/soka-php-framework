<?php
namespace com\lib
{
require_once  'Fpdf.php';
use com\lib\FPDF;
    final class SokaPdf implements IReport
    {
        private $_file;
        private $_header;
        private $_footer;
        private $_content;
        private $_config = array();
        
        public function __construct() // IReport $rp 
        {
            $this->_file = new FPDF(); //\com\lib\pdf\
            $this->_file->AddPage();
            $this->_file->SetFont('helvetica','B',12);
            $this->_file->Cell(30,10,'Inform User',0,2,'C');
            $this->_file->Output();
            
        } 
        
        private function _config()
        {
            
        }
        
        public function view()
        {
            $this->_file->Output(get_class($this));
        }
        
        public function header()
        {
            return $this;
            
        }
     
        public function content($texto)
        {
            $pdf->Cell(40,10, $texto);
            return $this;
        }
        
        public function footer()
        {
            return $this;
        }

    }    
}
