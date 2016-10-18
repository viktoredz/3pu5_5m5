<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "morganisasi";
$route['scaffolding_trigger'] = "";
$route['event/:any'] = 'event/index/file_id/$1';
$route['downloadread/:any'] = 'download/read/file_id/$1';
$route['downloaddo/:any'] = 'download/dodownload/file_id/$1';
$route['download/:any'] = 'download/index/file_id/$1';
$route['video/:any'] = 'video/index/file_id/$1';
$route['text/:any'] = 'text/index/file_id/$1';

$route['404_override'] = '';

