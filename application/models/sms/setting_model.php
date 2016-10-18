<?php
class Setting_model extends CI_Model {

    var $tabel    = 'sms_config';

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

		$cardnumber['value']=$this->input->post('cardnumber');
		$this->db->update($this->tabel, $cardnumber, array('key' => 'cardnumber'));

		$ussd['value']=$this->input->post('ussd');
		$this->db->update($this->tabel, $ussd, array('key' => 'ussd'));

		$com['value']=$this->input->post('com');
		$this->db->update($this->tabel, $com, array('key' => 'com'));

		$com['hostname']	= $this->db->hostname;
		$com['username']	= $this->db->username;
		$com['password']	= $this->db->password;
		$com['database']	= $this->db->database;

		$smsdrc = $this->parser->parse("sms/setting/smsdrc",$com);
		$fp = fopen($path['value'].'smsdrc', "w");
		fputs($fp,$smsdrc);

		return true;
    }
	
}