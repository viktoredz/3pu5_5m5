<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$autoload['packages'] = array(APPPATH.'third_party');

$autoload['libraries'] = array('user_agent','database','session','parser','form_validation','user','email','validation','authentication','menu','verifikasi_icon','template');

$autoload['helper'] = array('url');

$autoload['plugin'] = array();

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array('crud');

