<?php

class Admin_config extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_config_model');
	}
	
	function index()
	{
		$this->authentication->verify('admin','show');

		$data = $this->admin_config_model->get_data(); 
		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Web Configuration";
		$data['theme_default_option'] = $this->admin_config_model->get_theme();
		$data['content'] = $this->parser->parse("admin/config/form",$data,true);

		$this->template->show($data,"home");
	}

	function doupdate(){
		$this->authentication->verify('admin','edit');

		$this->form_validation->set_rules('title', 'Web title', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/admin_config");
		}elseif($this->admin_config_model->update_entry()){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."index.php/admin_config");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_config");
		}
	}
}
