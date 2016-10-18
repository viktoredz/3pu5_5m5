<?php
class Admin_master_kota_model extends CI_Model {

    var $tabel    = 'mas_kota';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
	function json_kota(){
	   $query = "select * from mas_kota order by id_kota asc";   
       
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


 	function get_data_row($id_kota){
		$data = array();
		$options = array('id_kota' => $id_kota);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

   function insert_entry()
    {
		$data['id_kota']=$this->input->post('id_kota');
		$data['nama_kota']=$this->input->post('nama_kota');
        
		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id_kota)
    {
		$data['nama_kota']=$this->input->post('nama_kota');
		
		return $this->db->update($this->tabel, $data, array('id_kota' => $this->input->post('id_kota')));
    }
	

	function delete_entry($id_kota)
	{
		$this->db->where(array('id_kota' => $id_kota));

		return $this->db->delete($this->tabel);
	}

}