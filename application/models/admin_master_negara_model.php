<?php
class Admin_master_negara_model extends CI_Model {

    var $tabel    = 'mas_negara';

    function __construct() {
        parent::__construct();		
		$this->load->library('encrypt');
    }
    
	function get_list_data(){		
        $query=$this->db->get('mas_negara');
        return $query->result();
	}
	
	function json_negara(){
        $query = "SELECT * FROM mas_negara  ORDER BY id_negara ASC";
        
        return $this->crud->jqxGrid($query);
    }
    
    function get_data_row($id_negara){
		$data = array();
		$options = array('id_negara' => $id_negara);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	function insert_entry(){
		$data['nama_negara']=$this->input->post('nama_negara');
		
		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id_negara){
		$data['nama_negara']=$this->input->post('nama_negara');
        
		return $this->db->update($this->tabel, $data, array('id_negara' => $this->input->post('id_negara')));
    }
	
	function delete_entry($id_negara){
		$this->db->where(array('id_negara' => $id_negara));

		return $this->db->delete($this->tabel);
	}

}