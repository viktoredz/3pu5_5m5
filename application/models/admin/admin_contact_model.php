<?php
class Admin_contact_model extends CI_Model {

    var $tabel    = 'app_contact';

    function __construct() {
        parent::__construct();
    }
    

    function get_data()
    {
        $query = $this->db->get($this->tabel);
		foreach($query->result_array() as $key=>$value){
			$data[$value['key']]=$value['value'];
		}
        return $data;
    }

	
    function update_entry()
    {
		$theme_default['value']=$this->input->post('ym1');
		$this->db->update($this->tabel, $theme_default, array('key' => 'ym1'));

		$title['value']=$this->input->post('ym2');
		$this->db->update($this->tabel, $title, array('key' => 'ym2'));

		$title['value']=$this->input->post('address');
		$this->db->update($this->tabel, $title, array('key' => 'address'));

		$title['value']=$this->input->post('phone');
		$this->db->update($this->tabel, $title, array('key' => 'phone'));

		$title['value']=$this->input->post('fax');
		$this->db->update($this->tabel, $title, array('key' => 'fax'));

		$title['value']=$this->input->post('email1');
		$this->db->update($this->tabel, $title, array('key' => 'email1'));

		$title['value']=$this->input->post('email2');
		$this->db->update($this->tabel, $title, array('key' => 'email2'));


		return true;
    }
	
}