<?php
class Admin_role_model extends CI_Model {

    var $tabel    = 'app_users_level';
    var $tabel_access    = 'app_users_access';

    function __construct() {
        parent::__construct();
    }
    
    function get_count()
    {
        return $this->db->count_all($this->tabel);
    }

    function get_data_all()
    {
		$this->db->select('app_users_level.level,COUNT(app_users_access.level_id) as total,SUM(app_users_access.doshow) as total_show,SUM(app_users_access.doadd) as total_add,SUM(app_users_access.doedit) as total_edit,SUM(app_users_access.dodel) as total_del');
		$this->db->from($this->tabel);
		$this->db->join($this->tabel_access, "app_users_access.level_id=app_users_level.level GROUP BY app_users_level.level ", "left");
		$query = $this->db->get();

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
	
	function get_privilege($id){
		$data = array();
		$this->db->from('app_files');
		$this->db->join("app_users_access", "app_users_access.file_id=app_files.id AND app_users_access.level_id='".$id."' ", "left");
		$this->db->join("app_theme", "app_files.id_theme=app_theme.id_theme ", "left");
		$this->db->group_by('app_files.id');
		$query = $this->db->get();
        return $query->result();
	}

	function clear_privilege($id){
		$this->db->where(array('level_id' => $id));
		return $this->db->delete($this->tabel_access);
	}

    function save_privilege($id,$action,$file_id)
    {
		$options = array('level_id' => $id, 'file_id' => $file_id);
		$data_update = array('do'.$action => 1);
		$data['level_id'] = $id;
		$data['file_id'] = $file_id;
		$data['do'.$action] = 1;
		$query = $this->db->get_where($this->tabel_access,$options);
		if ($query->num_rows() > 0){
			$this->db->update($this->tabel_access, $data_update, $options);
		}else{
			$this->db->insert($this->tabel_access, $data);
		}
		$query->free_result();    
    }

}