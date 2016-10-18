<?php
class Admin_config_model extends CI_Model {

    var $tabel    = 'app_config';

    function __construct() {
        parent::__construct();
    }
    

    function get_data()
    {
        $query = $this->db->get($this->tabel);
		foreach($query->result_array() as $key=>$value){
			if($value['key']!='district') $data[$value['key']]=$value['value'];
		}
        return $data;
    }

    function get_theme()
    {
        $query = $this->db->get('app_theme');
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['id_theme']]=$dt['name']." :: ".$dt['folder'];
		}
		$query->free_result();    
		return $data;
    }
	
    function update_entry()
    {
		$theme_default['value']=$this->input->post('theme_default');
		$this->db->update($this->tabel, $theme_default, array('key' => 'theme_default'));

		$theme_offline['value']=$this->input->post('theme_offline');
		$this->db->update($this->tabel, $theme_offline, array('key' => 'theme_offline'));

		$title['value']=$this->input->post('title');
		$this->db->update($this->tabel, $title, array('key' => 'title'));

		if($this->input->post('online')){
			$online['value']=1;
		}else{
			$online['value']=0;
		}
		$this->db->update($this->tabel, $online, array('key' => 'online'));

		$description['value']=$this->input->post('description');
		$this->db->update($this->tabel, $description, array('key' => 'description'));

		$keywords['value']=$this->input->post('keywords');
		$this->db->update($this->tabel, $keywords, array('key' => 'keywords'));

		$epuskesmas_server['value']=$this->input->post('epuskesmas_server');
		$this->db->update($this->tabel, $epuskesmas_server, array('key' => 'epuskesmas_server'));

		$epuskesmas_user['value']=$this->input->post('epuskesmas_user');
		$this->db->update($this->tabel, $epuskesmas_user, array('key' => 'epuskesmas_user'));

		$epuskesmas_password['value']=$this->input->post('epuskesmas_password');
		$this->db->update($this->tabel, $epuskesmas_password, array('key' => 'epuskesmas_password'));

		return true;
    }
	
}