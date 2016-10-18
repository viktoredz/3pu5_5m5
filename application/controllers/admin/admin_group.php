<?php

class Admin_group extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_group_model');

	}
	
	function index($page=1)
	{
		$this->authentication->verify('admin_group','show');

		$this->page		= !ctype_digit($page) ? 1 : intval($page);
		$this->start	= ($this->page-1) * $this->limit;


		$data['query'] = $this->admin_group_model->get_data($this->start,$this->limit); 
		$data['start'] = $this->start + 1; 
		$data['end'] = $this->start + count($data['query']); 
		$data['count'] = $this->admin_group_model->get_count(); 
		$data['page_count'] = ceil($this->admin_group_model->get_count()/$this->limit); 
		$data['page'] = $page; 

		$data['content'] = $this->parser->parse("admin/groups/show",$data,true);

		$this->template->show($data,"home");
	}

	function add()
	{
		$this->authentication->verify('admin_group','add');

		$data['title_form']="Groups &raquo; Tambah";
		$data['action']="doadd";
		$data['level']=$this->session->flashdata('level');

		$this->session->keep_flashdata('alert_form');
		foreach($data as $key=>$val){
			$this->session->keep_flashdata($key);
		}

		$data['content'] = $this->parser->parse("admin/groups/form",$data,true);
		$this->template->show($data,"home");
	}

	function doadd(){
		$this->authentication->verify('admin_group','add');
		$this->session->set_flashdata($_POST);
		
		$this->form_validation->set_rules('level', 'Level', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/admin_group/add");
		}elseif($this->admin_group_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."index.php/admin_group");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_group/add");
		}
	}


	function edit($id=0)
	{
		$this->authentication->verify('admin_group','edit');

		$this->form_validation->set_rules('level', 'Level', 'required');

		$data = $this->admin_group_model->get_data_row($id); 
		$data['title_form']="Groups &raquo; Ubah";
		$data['action']="doedit";

		$data['content'] = $this->parser->parse("admin/groups/form",$data,true);
		$this->template->show($data,"home");
	}

	function doedit($id=0){
		$this->authentication->verify('admin_group','edit');

		$this->form_validation->set_rules('level', 'Level', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/admin_group/edit/".$id);
		}elseif($this->admin_group_model->update_entry($id)){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."index.php/admin_group");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_group/edit/".$id);
		}
	}

	function dodel($id=0){
		$this->authentication->verify('admin_group','del');

		if($this->admin_group_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."index.php/admin_group");
	}

	function dodel_multi(){
		$this->authentication->verify('admin_group','del');

		if(is_array($this->input->post('id'))){
			foreach($this->input->post('id') as $data){
				$this->admin_group_model->delete_entry($data);
			}
			$this->session->set_flashdata('alert', 'Delete ('.count($this->input->post('id')).') data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Nothing to delete.');
		}

		redirect(base_url()."index.php/admin_group");
	}

}
