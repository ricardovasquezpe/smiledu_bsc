<?php if (! defined('BASEPATH'))  exit('No direct script access allowed');

class m_pdf{

    function m_pdf(){
        $CI = & get_instance();
    }
/*
    function load($param = NULL){
        include_once APPPATH . 'third_party\mpdf57\mpdf.php';
        
        if ($params == NULL) {
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';
        }
        return new mPDF($param);
    }*/

    function load($mode = '', $format = '', $default_font_size = 0, $default_font = '', 
                  $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 16, $mgh = 9, $mgf = 9, $orientation = 'L'){
        include_once APPPATH . '/third_party/mpdf57/mpdf.php';
        return new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh, $mgf, $orientation);
    }
}