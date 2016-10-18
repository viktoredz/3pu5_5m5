<?php
class Admin_menu_model extends CI_Model {

    var $tabel    = 'app_menus';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
    function get_position($id_theme)
    {
 		$this->db->select('DISTINCT(position)');
		$this->db->where(array('id_theme'=>$id_theme));
		$query = $this->db->get('app_menus');
		$data['0']= "- pilih posisi menu -";
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['position']]='Menu Position : '.$dt['position'];
		}
		$query->free_result();

		return $data;
    }

    function get_data($position=1,$sub_id=0)
    {
		$options['position'] = $position;
		$options['sub_id'] = $sub_id;
		$options['lang'] = $this->lang;

		$this->db->select('app_menus.*,app_files.filename,app_files.module');
		$this->db->join('app_files','app_files.id=app_menus.file_id');
		$this->db->where($options);
		$this->db->order_by("sort", "ASC"); 
		$query = $this->db->get($this->tabel);
        return $query->result_array();
    }

    function get_theme($blank=0)
    {
        $query = $this->db->get('app_theme');
		if($blank==1) $data[""]= "-";
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['id_theme']]=ucfirst($dt['name']);
		}
		$query->free_result();    
		return $data;
    }

    function get_files($id_theme,$position)
    {
 		$this->db->select("file_id");
 		$this->db->where("id_theme" , $id_theme);
 		$this->db->where("position" , $position);
		$query = $this->db->get('app_menus');
		$existed = $query->result_array();
		foreach($existed as $x=>$y){
			$not[] = $y['file_id'];
		}
		$query->free_result();    

		//$this->db->where("id_theme" , $id_theme);
		if(isset($not)) $this->db->where_not_in('id',$not);
 		$this->db->where("id_theme" , $id_theme);
        $query = $this->db->get('app_files');

        foreach($query->result_array() as $key=>$dt){
			$data[$dt['id']]=ucfirst($dt['filename']." | ".$dt['module']);
		}

		$query->free_result();    
		return $data;
    }

 	function check_child($position,$sub_id){
		$options['position'] = $position;
		$options['sub_id'] = $sub_id;
		$this->db->where($options);
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0){
			return true;
		}
		else return false;
    }

	function update_sub($position,$sub_id,$data)
    {
		$data = explode("|",$data);		
		foreach($data as $x=>$y){
			if($y!=""){
				$tmp = explode("__",$y);
				$dt['sort']=$tmp[1];
				$this->db->where('position', $position);
				$this->db->where('id', $tmp[3]);
				$this->db->where('sort', $tmp[2]);
			}
			if(is_array($dt)){
				$this->db->update($this->tabel, $dt); 
			}
			$dt = "";
		}

    }

	function get_last_id($position){
		$this->db->where('position', $position);
		$this->db->select_max('id');
		$query = $this->db->get($this->tabel);
		$data = $query->row_array();
		return $data['id']+1;
	}

	function get_last_sort($position,$sub_id){
		$this->db->where('position', $position);
		$this->db->where('sub_id', $sub_id);
		$this->db->select_max('sort');
		$query = $this->db->get($this->tabel);
		$data = $query->row_array();
		return $data['sort']+1;
	}


    function insert_entry($data)
    {
		if($data['position']==0 || $data['position']==99){
			$this->db->select_max('position');
			$query = $this->db->get($this->tabel);
			$post = $query->row_array();
			$data['position'] = $post['position']+1;
		}
		$data['file_id']=$this->input->post('file_id');
		$data['id']=$this->get_last_id($data['position']);
		$data['sort']=$this->get_last_sort($data['position'],$data['sub_id']);

        if($this->db->insert($this->tabel, $data)){
			return $data['position'];
		}else{
			return false;
		}
    }

	function delete_entry($position,$id)
	{
		$this->db->where('position', $position);
		$this->db->where('id', $id);

		return $this->db->delete($this->tabel);
	}
}