<?php

class Event {
	
	var $table1 = 'competition';
	var $table2 = 'competition_participants';
			
	function Event()
	{
		$this->obj =& get_instance();
		
	}
	
	
	function get_data_event(){
		$id_theme= $this->obj->session->userdata('id_theme');
		$options = array('id_theme' => $id_theme,'status'=>'open');
		//$options = array('status'=>'open');
		
		$this->obj->db->where($options);
		//$this->obj->db->group_by(array("competition_name")); 
		$query = $this->obj->db->get($this->table1);
		$result = $query->row_array();
		   
		$data['event']=$query->result();
		//print_r($data['open_event']);
		return $data;
	}
	
	function get_data_participants(){
		$username= $this->obj->session->userdata('username');
		
		$this->obj->db->where('username',$username);
		$query = $this->obj->db->get($this->table2);
		$result = $query->row_array();
		   
		$data['participants']=$query->result();
		//print_r($data['open_event']);
		return $data;
	}
}	
