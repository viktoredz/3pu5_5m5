<?php
class Admin_master_desa_model extends CI_Model {

    var $tabel    = 'mas_desa';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
    function json_desa(){
        $query = "select * from mas_desa order by id_desa asc";
        
        return $this->crud->jqxGrid($query);
    }
    
    function get_count($options=array())
    {
		$this->db->like($options);
        $query = $this->db->get($this->tabel);
		return count($query->result_array());
    }

    function get_data($start,$limit,$options=array())
    {
		$this->db->like($options);
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }


   function get_data_row($id_desa){
		$data = array();
		$options = array('id_desa' => $id_desa);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

   function insert_entry(){
		$data['id_desa']=$this->input->post('id_desa');
		$data['nama_desa']=$this->input->post('nama_desa');
        
		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id_desa)
    {
		$data['nama_desa']=$this->input->post('nama_desa');
		
		return $this->db->update($this->tabel, $data, array('id_desa' => $this->input->post('id_desa')));
    }
	

	function delete_entry($id_desa)
	{
		$this->db->where(array('id_desa' => $id_desa));

		return $this->db->delete($this->tabel);
	}

}