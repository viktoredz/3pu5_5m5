<?php
class Admin_master_propinsi_model extends CI_Model {

    var $tabel    = 'mas_propinsi';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
    function json_propinsi(){
        $query = "select * from mas_propinsi order by id_propinsi asc";
        
		return $this->crud->jqxGrid($query);
	}
    
    function get_count($options=array()){
		$this->db->like($options);
        $query = $this->db->get($this->tabel);
		return count($query->result_array());
    }

    function get_data($start,$limit,$options=array()){
		$this->db->like($options);
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }


 	function get_data_row($id_propinsi){
		$data = array();
		$options = array('id_propinsi' => $id_propinsi);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

   function insert_entry()
    {
		$data['id_propinsi']=$this->input->post('id_propinsi');
		$data['nama_propinsi']=$this->input->post('nama_propinsi');
		
		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id_propinsi)
    {
		$data['nama_propinsi']=$this->input->post('nama_propinsi');
		
		return $this->db->update($this->tabel, $data, array('id_propinsi' => $this->input->post('id_propinsi')));
    }
	

	function delete_entry($id_propinsi)
	{
		$this->db->where(array('id_propinsi' => $id_propinsi));

		return $this->db->delete($this->tabel);
	}

}