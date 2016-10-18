<?php
class Verifikasi_icon{
	
	function Verifikasi_icon(){
		$this->obj =& get_instance();
	}
	
	function del_icon($contr,$link){
		
		$q=$this->obj->db->query("
			SELECT * FROM app_users_access AS a, app_files AS b 
				WHERE a.file_id=b.id AND b.module='".$contr."' 
				AND a.level_id='".$this->obj->session->userdata('level')."' AND dodel=1
			");
		
		if($contr=="menus"){
			$style='align="right" style="padding:4px"';
		}else{
			$style='align="center"';
		}
		
		if($this->obj->session->userdata('level')=="super administrator"){
			$data='<a href=# onclick="if(confirm(\'Hapus data ini?\'))document.location.href=\''.base_url().''.$link.'\'" title="Hapus"> 
					<img src='.base_url().'media/images/16_del.gif '.$style.'> 
					</a>';
		}else if($q->num_rows()>0){
			$data='<a href=# onclick="if(confirm(\'Hapus data ini?\'))document.location.href=\''.base_url().''.$link.'\'" title="Hapus"> 
					<img src='.base_url().'media/images/16_del.gif '.$style.'> 
					</a>';
		}else{
			$data='<img src='.base_url().'media/images/16_lock.gif '.$style.'>';
		}
		
		return $data;
	}
	
	function del_button($contr){
		
		$q=$this->obj->db->query("
			SELECT * FROM app_users_access AS a, app_files AS b 
				WHERE a.file_id=b.id AND b.module='".$contr."' 
				AND a.level_id='".$this->obj->session->userdata('level')."' AND dodel=1
			");
		if($this->obj->session->userdata('level')=="super administrator"){
			
			$data='<button type="submit" class=btn onclick="if(!confirm(\'Hapus semua data yang dipilih?\'))return false;">Hapus</button>';
		}else if($q->num_rows()>0){
			$data='<button type="submit" class=btn onclick="if(!confirm(\'Hapus semua data yang dipilih?\'))return false;">Hapus</button>';
		}else{
			$data='';
		}
		
		return $data;
	}
	
}