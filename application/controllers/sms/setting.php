<?php
class Setting extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/setting_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data = $this->setting_model->get_data(); 
		$data['title_group'] = "SMS";
		$data['title_form'] = "Setting";

		$data['content'] = $this->parser->parse("sms/setting/setting",$data,true);
		$this->template->show($data,'home');
	}

	function doupdate(){
		$this->authentication->verify('sms','edit');

		$this->form_validation->set_rules('com', 'COM', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."sms/setting");
		}elseif($this->setting_model->update_entry()){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."sms/setting");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/setting");
		}
	}

	function cleaninbox(){
		$this->authentication->verify('sms','edit');

		$this->db->query("TRUNCATE inbox");

		die("<br><br>Inbox sudah dibersihkan.");
	}	

	function cleansent(){
		$this->authentication->verify('sms','edit');

		$this->db->query("TRUNCATE sentitems");

		die("<br><br>Sent Items sudah dibersihkan.");
	}	

	function cleanoutbox(){
		$this->authentication->verify('sms','edit');

		$this->db->query("TRUNCATE outbox");

		die("<br><br>Outbox sudah dibersihkan.");
	}	

	function restart(){
		$this->authentication->verify('sms','edit');

		$hasil = NULL;

		exec("sudo service gammu-smsd restart",$hasil);

		foreach ($hasil as $row) {
			echo "<br>".$row;
		}
		
		die();
	}	

	function ussd(){
		$this->authentication->verify('sms','edit');

		$ussd = $this->input->post('ussd');

		$hasil = NULL;
		$stop = NULL;
	
		exec("sudo service gammu-smsd stop",$stop);
		
		exec("gammu getussd ".$ussd, $hasil);
		
		exec("sudo service gammu-smsd start");
		
		$i=0;
		foreach ($hasil as $row) {
			if($i>2) echo "<br>".$row;
			$i++;
		}
		
		die();
	}	

	function identify(){
		$this->authentication->verify('sms','edit');

		$hasil = NULL;
		$stop = NULL;

		exec("sudo service gammu-smsd stop",$stop);
		
		exec("sudo gammu --identify", $hasil);

		exec("sudo service gammu-smsd start");

		foreach ($hasil as $row) {
			echo "<br>".$row;
		}
		
		die();
	}	

}
