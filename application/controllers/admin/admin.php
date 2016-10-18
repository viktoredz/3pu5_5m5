<?php
class Admin extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->helper('html');
	}
	
	function index(){
		$this->authentication->verify('admin','show');
		$this->obj =& get_instance();
		$_SESSION['lang'] = (!isset($_SESSION['lang']) || $_SESSION['lang']=="" ? $this->obj->config->slash_item('language') : $_SESSION['lang']) ;
		$_SEGS = $this->uri->ruri_to_assoc();

		$data['segments']='';
		foreach($_SEGS as $a=>$b){
			$data['segments'] .=$a.'/'.$b.'/';
		}

		$data['content'] = $this->parser->parse("admin/show",$data,true);
		$this->template->show($data,'home');
	}
	
	function login()
	{
		$this->form_validation->set_rules('email', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if($this->form_validation->run()){
			if($this->user->login("admin")){
				$this->session->set_flashdata('notification', 'Login successful...');			
				redirect(base_url()."admin");
			}else{
				$this->session->set_flashdata('notification', 'Login failed...');
			}
		}

		$data['title_form']="&raquo; Control Panel Login";
		$data['panel']= "";

		$data['content'] = $this->parser->parse("admin/login/login",$data,true);
		$this->template->show($data,'home');
	}

	function logout()
	{
		$this->user->logout("admin");
	}

}
