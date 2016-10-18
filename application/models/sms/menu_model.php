<?php
class Menu_model extends CI_Model {

    var $tabel    = 'sms_info_menu';
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
		$this->db->where("code",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

    function get_grupoption($limit=999999,$start=0){
    	$this->db->order_by('code','asc');
        $query = $this->db->get('sms_info_menu',$limit,$start);
        return $query->result();
    }

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('code'=>$data));
    }

    function insert_entry()
    {
		$data['code']	= $this->input->post('code');

    	$this->db->where('code',$data['code']);
    	$menu = $this->db->get($this->tabel)->row();

		if(empty($menu)){
			return $this->db->insert($this->tabel, $data);
		}else{
			return mysql_error();
		}
    }

	function delete_entry($code)
	{
		$this->db->where('code',$code);

		return $this->db->delete($this->tabel);
	}
}