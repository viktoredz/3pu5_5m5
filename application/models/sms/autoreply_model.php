<?php
class Autoreply_model extends CI_Model {

    var $tabel    = 'sms_info';
	var $lang	  = 'ina';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->select("sms_info.*,sms_tipe.nama as tipe");
	    $this->db->join('sms_tipe','sms_tipe.id_tipe=sms_info.id_sms_tipe','inner');
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
	
    }

 	function get_data_row($id){
		$data = array();
		$this->db->where("id_info",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

 	function get_data_ID($id){
		$data = array();
		$this->db->where("id_info",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			if($query['status']=="baru"){
				$update = array("status"=>"baca");
				$this->db->where("id_info",$id);
				$this->db->update($this->tabel,$update);
			}

			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

	function move($id)
	{
		$data = array(
			'id_sms_tipe'	=> $this->input->post('id_sms_tipe'),
		);

		$this->db->where('id_info',$id);
		if($this->db->update('sms_opini',$data)){
			return true;
		}else{
			return false;
		}
	}

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('nomor'=>$data));
    }

   function insert_entry()
    {
		$data['tgl_mulai']		= date("Y-m-d",strtotime($this->input->post('tgl_mulai')));
		$data['tgl_akhir']		= date("Y-m-d",strtotime($this->input->post('tgl_akhir')));
		$data['katakunci']		= $this->input->post('katakunci');
		$data['pesan']			= $this->input->post('pesan');
		$data['code_sms_menu']	= $this->input->post('code_sms_menu');
		$data['id_sms_tipe']	= $this->input->post('id_sms_tipe');

		if($this->db->insert($this->tabel, $data)){
			$id= mysql_insert_id();
			return $id;
		}else{
			return mysql_error();
		}
    }

    function update_entry($id_info)
    {
		$data['tgl_mulai']		= date("Y-m-d",strtotime($this->input->post('tgl_mulai')));
		$data['tgl_akhir']		= date("Y-m-d",strtotime($this->input->post('tgl_akhir')));
		$data['pesan']			= $this->input->post('pesan');
		$data['code_sms_menu']	= $this->input->post('code_sms_menu');
		$data['id_sms_tipe']	= $this->input->post('id_sms_tipe');

		if($this->db->update($this->tabel, $data, array("id_info"=>$id_info))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('id_info',$id);

		return $this->db->delete($this->tabel);
	}

    function get_menu()
    {
	    $query = $this->db->get('sms_info_menu');
    	return $query->result();
	
    }}