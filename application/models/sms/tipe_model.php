<?php
class Tipe_model extends CI_Model {

    var $tabel    = 'sms_tipe';
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
		$this->db->where("id_tipe",$id);
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
        $query = $this->db->get('sms_tipe',$limit,$start);
        return $query->result();
    }

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('id_tipe'=>$data));
    }

    function insert_entry()
    {
		$data['nama']	= $this->input->post('nama');
		$data['jenis']	= $this->input->post('jenis');

		if($this->db->insert($this->tabel, $data)){
		 	return true;
		}else{
			return mysql_error();
		}
    }

    function update_entry($id_tipe)
    {
		$data['nama']			= $this->input->post('nama');
		$data['jenis']	= $this->input->post('jenis');

		$this->db->where('id_tipe',$id_tipe);
		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id_tipe)
	{
		$this->db->where('id_tipe',$id_tipe);

		return $this->db->delete($this->tabel);
	}
}