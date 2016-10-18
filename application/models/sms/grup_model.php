<?php
class Grup_model extends CI_Model {

    var $tabel    = 'sms_grup';
	var $lang	  = 'ina';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->select("sms_grup.*, anggota");
    	$this->db->join("(SELECT COUNT(*) AS anggota,id_sms_grup FROM sms_pbk GROUP BY id_sms_grup) AS pbk","pbk.id_sms_grup=sms_grup.id_grup","LEFT");
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
	
    }

 	function get_data_row($id){
		$data = array();
		$this->db->where("id_grup",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

    function get_grupoption($limit=999999,$start=0){
    	$this->db->order_by('nama','asc');
        $query = $this->db->get('sms_grup',$limit,$start);
        return $query->result();
    }

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('id_grup'=>$data));
    }

    function insert_entry()
    {
		$data['nama']	= $this->input->post('nama');

		if($this->db->insert($this->tabel, $data)){
		 	return true;
		}else{
			return mysql_error();
		}
    }

    function update_entry($id_grup)
    {
		$data['nama']			= $this->input->post('nama');
		$data['modified_on']	= date("Y-m-d H:i:s");

		$this->db->where('id_grup',$id_grup);
		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id_grup)
	{
		$this->db->where('id_grup',$id_grup);

		return $this->db->delete($this->tabel);
	}
}