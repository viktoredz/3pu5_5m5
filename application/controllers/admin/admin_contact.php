<?php

class Admin_contact extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_contact_model');
	}
	
	function index()
	{
		$this->authentication->verify('admin_contact','show');

		$data = $this->admin_contact_model->get_data(); 
		$data['title_form'] = "Contact Information";
		$data['content'] = $this->parser->parse("admin/contact/form",$data,true);

		$this->template->show($data,"home");
	}

	function doupdate(){
		$this->authentication->verify('admin_contact','edit');

		$this->form_validation->set_rules('email1', 'Email 1', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/admin_contact");
		}elseif($this->admin_contact_model->update_entry()){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."index.php/admin_contact");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_contact");
		}
	}
}
