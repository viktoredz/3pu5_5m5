<?php
class Inbox_model extends CI_Model {

    var $tabel    = 'inbox';
	var $lang	  = 'ina';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
	
    }

 	function get_data_row($id){
		$data = array();
		$this->db->where("nomor",$id);
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
		$this->db->where("ID",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			$this->db->where("ID",$id);
			$this->db->update($this->tabel,array('Processed'=>'true'));

			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('nomor'=>$data));
    }

	function move($id)
	{
		$inbox = $this->get_data_ID($id);
		$data = array(
			'id_sms_tipe'	=> $this->input->post('id_sms_tipe'),
			'pesan'			=> $inbox['TextDecoded'],
			'nomor'			=> $inbox['SenderNumber'],
			'status'		=> 'baru'
		);
		if($this->db->insert('sms_opini',$data)){
			$this->db->where('ID',$id);
			return $this->db->delete($this->tabel);
		}else{
			return false;
		}
	}

	function delete_entry($id)
	{
		$this->db->where('ID',$id);

		return $this->db->delete($this->tabel);
	}
}