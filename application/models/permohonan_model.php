<?php
class permohonan_model extends CI_Model {

    var $tabel    = 'app_users_list';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    

}