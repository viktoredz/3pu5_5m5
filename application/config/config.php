<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";
$_SERVER['SCRIPT_NAME'] = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : "";
$_SERVER['REMOTE_ADDR'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

$root = "http://".$_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$config['base_url']    = "$root";

$config['title'] = "ePuskesmas Manajemen Organisasi";
$config['index_page'] 			= "";
$config['uri_protocol']			= "AUTO";
$config['url_suffix'] 			= ".sik";
$config['language']				= "ina";
$config['charset'] 				= "UTF-8";
$config['enable_hooks'] 		= FALSE;
$config['subclass_prefix'] 		= 'MY_';
$config['permitted_uri_chars'] 	= 'a-z 0-9~%.:_\-@&=(),';

$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] 	= 'c';
$config['function_trigger'] 	= 'm';
$config['directory_trigger'] 	= 'd';

$config['log_threshold'] 		= 0;
$config['log_path'] 			= '';
$config['log_date_format'] 		= 'Y-m-d H:i:s';
$config['cache_path'] 			= '';
$config['encryption_key'] 		= "viktoredz";

$config['sess_cookie_name']		= 'sms_jaksel';
$config['sess_expiration']		= 0;
$config['sess_encrypt_cookie']	= TRUE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update'] 	= 300;

$config['cookie_prefix']		= "";
$config['cookie_domain']		= "";
$config['cookie_path']			= "/";

$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] 		= FALSE;
$config['csrf_token_name'] 		= 'csrf_test_name';
$config['csrf_cookie_name'] 	= 'csrf_cookie_name';
$config['csrf_expire'] 			= 7200;

$config['compress_output'] 		= FALSE;
$config['time_reference'] 		= 'local';
$config['rewrite_short_tags'] 	= FALSE;
$config['proxy_ips'] 			= '';
