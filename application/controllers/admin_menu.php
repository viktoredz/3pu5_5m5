<?php

class Admin_menu extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('admin/admin_menu_model');
	}
	
	function index()
	{
		$this->authentication->verify('admin','show');
		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Menu";

		$_SEGS = $this->uri->ruri_to_assoc();

		unset($_SEGS['page']);
		$data['segments']='';
		foreach($_SEGS as $a=>$b){
			$data['segments'] .=$a.'/'.$b.'/';
		}

		$data['id_theme']=(!isset($_SEGS['id_theme'])) ? $this->session->userdata('id_theme') : $_SEGS['id_theme'];
		$data['theme_option']=$this->admin_menu_model->get_theme();


		$data['position'] = (isset($_SEGS['position']) ? $_SEGS['position'] : 99);
		$data['position_option']=$this->admin_menu_model->get_position($data['id_theme']);

		#$data['menu_tree'] = $this->render_tabel($data['position']);

		$data['menu_data'] = $this->admin_menu_model->get_data_lv1($data['id_theme'], $data['position'] );
		$data['submenu_data'] = $this->admin_menu_model->get_data_lv2($data['id_theme'], $data['position']);
		
		$data['content'] = $this->parser->parse("admin/menus/show",$data,true);

		//$data['panel']= $this->parser->parse("admin/menu_lock",$data,true);
		$this->template->show($data,"home");
	}

	function render_tabel($position,$sub_id=0){
		$data['sub_id']=$sub_id;

		$html = $this->parser->parse("admin/menus/tabel_front",$data,true);

		$tmp = $this->admin_menu_model->get_data($position,$sub_id);
		foreach($tmp as $x=>$y){

			$html .= $this->parser->parse("admin/menus/tr_front",$tmp[$x],true);
	
			if($this->admin_menu_model->check_child($position,$y['id'])){
				$html .= $this->render_tabel($position,$y['id']);

			}

			$html .= $this->parser->parse("admin/menus/tr_end",$data,true);
		}

		$html .= $this->parser->parse("admin/menus/tabel_end",$data,true);

		return $html;
	}


	function add()
	{
		$this->authentication->verify('admin','add');

		$data = $this->uri->ruri_to_assoc();
		$data['title_form']="Menus &raquo; Tambah";
		$data['title_group']="Admin Panel";
		$data['action']="doadd";
		$data['file_id']="";
		if(!isset($data['sub_id'])) $data['sub_id']=0;
		$data['module']=$this->session->flashdata('module');
		$data['file_option']=$this->admin_menu_model->get_files($data['id_theme'],$data['position']);

		$this->session->keep_flashdata('alert_form');

		$data['content'] = $this->parser->parse("admin/menus/form",$data,true);
		$this->template->show($data,"home");
	}

	function doadd(){
		$this->authentication->verify('admin','add');
		$this->session->set_flashdata($_POST);
		$data = $this->uri->ruri_to_assoc();
		if(!isset($data['sub_id'])) $data['sub_id']=0;
		
		$this->form_validation->set_rules('file_id', 'File ID', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."index.php/admin_menu/add/id_theme/".$data['id_theme']."/position/".$data['position']);
		}elseif($position = $this->admin_menu_model->insert_entry($data)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."index.php/admin_menu/index/id_theme/".$data['id_theme']."/position/".$position);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_menu/add/id_theme/".$data['id_theme']."/position/".$data['position']);
		}
	}
	
	
	function dosort(){
		
		$this->authentication->verify('admin','add');		
				
		if(!empty($this->input->post('item'))){
			$sort = 0;
			foreach ($this->input->post('item') as $value) {
				//id#posisi
				$dataExplode = explode('#',$value);
				$this->admin_menu_model->update_sort($dataExplode[0], $dataExplode[1], $sort);																
				$sort++;
				$this->session->set_flashdata('alert_form', 'Save data successful...');
			}
		}	
		
	}
	
	
	
	function dodelete(){
		$this->authentication->verify('admin','del');	
		$data = $this->uri->ruri_to_assoc();	
		if($position = $this->admin_menu_model->delete_entry($data['position'],$data['delete_id'])){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."index.php/admin_menu/index/id_theme/".$data['id_theme']."/position/".$data['position']);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_menu/add/id_theme/".$data['id_theme']."/position/".$data['position']);
		}
	}

	function dodel(){
		$this->authentication->verify('admin','del');
		$data = $this->uri->ruri_to_assoc();

		if($this->admin_menu_model->delete_entry($data['position'],$data['id'])){
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."index.php/admin_menu/index/id_theme/".$data['id_theme']."/position/".$data['position']);
	}

	function doorder()
	{
		$this->authentication->verify('admin','edit');
		foreach($_POST['collector'] as $x=>$y){
			if($y!=""){
				$this->admin_menu_model->update_sub($_POST['position'],$x,$y);
			}
		}

		$this->session->set_flashdata('alert_form', 'Urutan menu berhasil disimpan...');
		redirect(base_url()."index.php/admin_menu/index/position/".$_POST['position']."/id_theme/".$this->uri->segment(4));
	}
}
