<?php

class Theme extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('theme_model');

	}
	
	function index($page=1)
	{
		$this->authentication->verify('theme','show');

		$this->page		= !ctype_digit($page) ? 1 : intval($page);
		$this->start	= ($this->page-1) * $this->limit;

		$data['query'] = $this->theme_model->get_data(); 
		$data['start'] = $this->start + 1; 
		$data['end'] = $this->start + count($data['query']); 
		$data['count'] = $this->theme_model->get_count(); 
		$data['page_count'] = ceil($this->theme_model->get_count()/$this->limit); 
		$data['page'] = $page; 

		$data['content'] = $this->parser->parse("theme/show",$data,true);

		$this->layout->show($data,"large");
	}


	function edit($id=0)
	{
		$this->authentication->verify('theme','edit');

		$this->form_validation->set_rules('level', 'Level', 'required');

		$data = $this->theme_model->get_data_row($id); 
		$data['title_form']="Theme &raquo; Ubah &raquo; ".$data['name'];
		$data['action']="doedit";

		$data['content'] = $this->parser->parse("theme/form",$data,true);
		$this->layout->show($data,"large");
	}

	function doedit($id=0){
		$this->authentication->verify('theme','edit');

		$this->form_validation->set_rules('name', 'Nama', 'trim|required');
		$this->form_validation->set_rules('folder', 'Folder', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/theme/edit/".$id);
		}elseif($this->theme_model->update_entry($id)){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."index.php/theme");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/theme/edit/".$id);
		}
	}

}
