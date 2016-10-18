<?php
class Pbk_model extends CI_Model {

    var $tabel    = 'sms_pbk';
	var $lang	  = 'ina';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
	    $this->db->select("sms_pbk.*,sms_grup.nama AS nama_grup");
	    $this->db->join('sms_grup', 'sms_grup.id_grup = sms_pbk.id_sms_grup', 'left'); 
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

    function get_grupoption($limit=999999,$start=0){
    	$this->db->order_by('nama','asc');
        $query = $this->db->get('sms_grup',$limit,$start);
        return $query->result();
    }

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('nomor'=>$data));
    }

    function insert_entry()
    {
		$data['nomor']			= $this->input->post('nomor');
		$data['nama']			= $this->input->post('nama');
		$data['id_sms_grup']	= $this->input->post('id_sms_grup');

		if($this->getSelectedData($this->tabel, $data['nomor'])->num_rows() > 0) {
			return 0;
		}else{
			if($this->db->insert($this->tabel, $data)){
			 return 1;
			}else{
				return mysql_error();
			}
		}
    }

    function update_entry($nomor)
    {
		$data['nama']			= $this->input->post('nama');
		$data['id_sms_grup']	= $this->input->post('id_sms_grup');
		$data['modified_on']	= date("Y-m-d H:i:s");

		$this->db->where('nomor',$nomor);
		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($nomor)
	{
		$this->db->where('nomor',$nomor);

		return $this->db->delete($this->tabel);
	}
}