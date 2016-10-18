<?php
class Tes extends CI_Controller {

    public function __construct(){
		parent::__construct();
	}
	function index(){
		$query = $this->db->query("SELECT id_data_keluarga,id_pilihan_kelamin,COUNT(id_pilihan_kelamin) FROM data_keluarga_anggota  GROUP BY id_data_keluarga,id_pilihan_kelamin")->result_array();

		foreach ($query as $key) {
			print_r($key);
		}
	}
}
	
