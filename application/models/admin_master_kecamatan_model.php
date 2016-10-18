<?php
class Admin_master_kecamatan_model extends CI_Model {

    var $tabel    = 'mas_kecamatan';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
	function json_kecamatan(){
	   $query = "select * from mas_kecamatan order by id_kecamatan asc";
       
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


 	function get_data_row($id_kecamatan){
		$data = array();
		$options = array('id_kecamatan' => $id_kecamatan);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

   function insert_entry(){
		$data['id_kecamatan']=$this->input->post('id_kecamatan');
        $data['nama_kecamatan']=$this->input->post('nama_kecamatan');
        
		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id_kecamatan)
    {
		$data['nama_kecamatan']=$this->input->post('nama_kecamatan');
		
		return $this->db->update($this->tabel, $data, array('id_kecamatan' => $this->input->post('id_kecamatan')));
    }
	

	function delete_entry($id_kecamatan)
	{
		$this->db->where(array('id_kecamatan' => $id_kecamatan));

		return $this->db->delete($this->tabel);
	}

}