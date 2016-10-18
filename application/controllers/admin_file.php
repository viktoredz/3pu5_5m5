<?php

class Admin_file extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_file_model');

	}
	
	function index($page=1)
	{
		$this->authentication->verify('admin','show');

		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Files / Pages";
		$data['query'] = $this->admin_file_model->get_data(); 

		$data['content'] = $this->parser->parse("admin/files/show",$data,true);

		$this->template->show($data,"home");
	}

	function add()
	{
		$this->authentication->verify('admin','add');

		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Tambah Files / Pages";
		$data['action']="doadd";
		$data['id']="";
		$data['filename']=$this->session->flashdata('filename');
		$data['module']=$this->session->flashdata('module');
		$data['id_theme']=(!isset($_SEGS['id_theme'])) ? $this->session->userdata('id_theme') : $_SEGS['id_theme'];
		$data['theme_option']=$this->admin_file_model->get_theme();
		$data['lang'] = $this->admin_file_model->get_lang(); 

		$this->session->keep_flashdata('alert_form');

		$data['content'] = $this->parser->parse("admin/files/form",$data,true);
		$this->template->show($data,"home");
	}

	function doadd(){
		$this->authentication->verify('admin','add');
		$this->session->set_flashdata($_POST);
		
		$lang = $this->admin_file_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('filename_'.$row['kode'], 'Filename '.$row['lang'], 'trim|required');
		}
		$this->form_validation->set_rules('module', 'Module', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/admin_file/add");
		}elseif($this->admin_file_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."index.php/admin_file/index/id_theme/".$this->input->post('id_theme'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_file/add");
		}
	}


	function edit($id=0)
	{
		$this->authentication->verify('admin','edit');

		$this->form_validation->set_rules('filename', 'Filename', 'trim|required');
		$this->form_validation->set_rules('module', 'Module', 'trim|required');

		$data = $this->admin_file_model->get_data_row($id); 
		$data['lang'] = $this->admin_file_model->get_lang(); 
		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Ubah Files / Pages";
		$data['action']="doedit";
		$data['theme_option']=$this->admin_file_model->get_theme();

		$data['content'] = $this->parser->parse("admin/files/form",$data,true);
		$this->template->show($data,"home");
	}

	function doedit($id=0){
		$this->authentication->verify('admin','edit');

		$lang = $this->admin_file_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('filename_'.$row['kode'], 'Filename '.$row['lang'], 'trim|required');
		}
		$this->form_validation->set_rules('module', 'Module', 'required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/admin_file/edit/".$id);
		}elseif($this->admin_file_model->update_entry($id)){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."index.php/admin_file/index/id_theme/".$this->input->post('id_theme'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_file/edit/".$id);
		}
	}

	function dodel($id=0){
		$this->authentication->verify('admin','del');

		if($this->admin_file_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."index.php/admin_file/index/id_theme/".$this->uri->segment(5));
	}

	function dodel_multi(){
		$this->authentication->verify('admin','del');

		if(is_array($this->input->post('id'))){
			foreach($this->input->post('id') as $data){
				$this->admin_file_model->delete_entry($data);
			}
			$this->session->set_flashdata('alert', 'Delete ('.count($this->input->post('id')).') data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Nothing to delete.');
		}

		redirect(base_url()."index.php/admin_file");
	}

}
