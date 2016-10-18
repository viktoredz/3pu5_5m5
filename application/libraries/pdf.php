<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class pdf {
    
    function pdf()
    {
        $CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once APPPATH.'/third_party/MPDF/mpdf.php';
        if ($params == NULL)
        {
            $param = '"en-GB-x","LEGAL","9","arial"';          
        }
         
        return new mPDF($param);
    }
}