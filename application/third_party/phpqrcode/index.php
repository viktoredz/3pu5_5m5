<?php    

    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 4;

    QRcode::png('aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code aku belajar QR Code 9', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
    echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';  
