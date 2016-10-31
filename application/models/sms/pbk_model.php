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
	    $this->db->order_by("nama","asc");
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
	
    }

 	function get_data_row($id,$cl_phc){
		$data = array();
		$this->db->where("cl_pid",$id);
		$this->db->where("cl_phc",$cl_phc);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

    function get_puskesmas($limit=999999,$start=0,$cl_phc=""){
    	$this->db->order_by('code','asc');
    	if($cl_phc!="") $this->db->where('code',$cl_phc);
        $query = $this->db->get('cl_phc',$limit,$start);
        return $query->result();
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
		$data['cl_pid']			= $this->input->post('cl_pid');
		$data['cl_phc']			= $this->input->post('cl_phc');
		$data['nomor']			= $this->input->post('nomor');
		$data['bpjs']			= $this->input->post('bpjs');
		$data['nik']			= $this->input->post('nik');
		$data['nama']			= $this->input->post('nama');
		$data['alamat']			= $this->input->post('alamat');
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

    function update_entry($cl_pid,$cl_phc)
    {
		$data['nomor']			= $this->input->post('nomor');
		$data['bpjs']			= $this->input->post('bpjs');
		$data['nik']			= $this->input->post('nik');
		$data['nama']			= $this->input->post('nama');
		$data['alamat']			= $this->input->post('alamat');
		$data['id_sms_grup']	= $this->input->post('id_sms_grup');
		$data['modified_on']	= date("Y-m-d H:i:s");

		$this->db->where('cl_pid',$cl_pid);
		$this->db->where('cl_phc',$cl_phc);
		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($cl_pid, $cl_phc)
	{
		$this->db->where('cl_pid',$cl_pid);
		$this->db->where('cl_phc',$cl_phc);

		return $this->db->delete($this->tabel);
	}
}