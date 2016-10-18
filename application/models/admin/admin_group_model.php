<?php
class Admin_group_model extends CI_Model {

    var $tabel    = 'app_users_level';

    function __construct() {
        parent::__construct();
    }
    
    function get_count()
    {
        return $this->db->count_all($this->tabel);
    }

    function get_data($start,$limit)
    {
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('level' => $id);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

   function insert_entry()
    {
		$data['level']=$this->input->post('level');

        return $this->db->insert($this->tabel, $data);
    }

    function update_entry($id)
    {
		$data['level']=$this->input->post('level');
        
		return $this->db->update($this->tabel, $data, array('level' => $id));
    }

	function delete_entry($id)
	{
		$this->db->where(array('level' => $id));

		return $this->db->delete($this->tabel);
	}
}