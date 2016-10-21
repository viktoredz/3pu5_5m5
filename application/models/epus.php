<?php
class Epus extends CI_Model {


    function __construct() {
        parent::__construct();
    }

    function get_config($request)
    {
    	$this->db->where('key','epuskesmas_server');
    	$server = $this->db->get('app_config')->row();

    	$this->db->where('key','epuskesmas_id');
    	$client_id = $this->db->get('app_config')->row();

    	$this->db->where('key','epuskesmas_token');
    	$token = $this->db->get('app_config')->row();

    	$data = array(
    			'server'			=> $server->value.$request,
    			'client_id'			=> $client_id->value,
    			'request_token'		=> $token->value,
		 		'limit'				=> 10,
				'request_output' 	=> "json",
				'request_time' 		=> time(),
    		);
        // print_r($data);
        //     die();
        return $data;
    }
}