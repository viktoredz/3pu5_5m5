<?php

class Admin_menus extends CI_Controller {

	var $limit=25;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_menus_model');
		$this->load->helper('html');
	}
	
	function index(){
		$this->authentication->verify('admin_menus','edit');

		$_SEGS = $this->uri->ruri_to_assoc();

		$this->page		= isset($_SEGS['page']) ? !ctype_digit($_SEGS['page']) ? 1 : intval($_SEGS['page']) : 1;
		$this->start	= ($this->page-1) * $this->limit;

		unset($_SEGS['page']);
		$data['segments']='';
		foreach($_SEGS as $a=>$b){
			$data['segments'] .=$a.'/'.$b.'/';
		}

		$data['query'] = $this->admin_menus_model->get_data($this->start,$this->limit,$_SEGS); 
		$data['start'] = $this->start + 1; 
		$data['end'] = $this->start + count($data['query']); 
		$data['count'] = $this->admin_menus_model->get_count($_SEGS); 
		$data['page_count'] = ceil($data['count']/$this->limit); 
		$data['page'] = $this->page; 

		$data['kode']=(!isset($_SEGS['kode'])) ? $this->session->flashdata('kode') : $_SEGS['kode'];
		$data['nama']=(!isset($_SEGS['nama'])) ? $this->session->flashdata('nama') : $_SEGS['nama'];
		$data['tipe']=(!isset($_SEGS['tipe'])) ? $this->session->flashdata('tipe') : $_SEGS['tipe'];
		$data['keterangan']=(!isset($_SEGS['keterangan'])) ? $this->session->flashdata('keterangan') : $_SEGS['keterangan'];
		$data['kode_category']=(!isset($_SEGS['kode_category'])) ? $this->session->flashdata('kode_category') : $_SEGS['kode_category'];
		$data['category'] = $this->admin_menus_model->get_data_category(); 
		$data['tipe_option'] = array(''=>'','food'=>'Food','beverage'=>'Beverage'); 
		$data['searchbox'] = $this->parser->parse("admin_cafe/admin_menus/search",$data,true);

		$data['content'] = $this->parser->parse("admin_cafe/admin_menus/show",$data,true);

		$this->template->show($data,"home");
	}

	function add(){
		$this->authentication->verify('admin_menus','add');

		$data['title_form']="Menus &raquo; Tambah";
		$data['action']="add";
		$data['category'] = $this->admin_menus_model->get_data_category(); 
		$data['tipe_option'] = array(''=>'','food'=>'Food','beverage'=>'Beverage'); 

		$this->form_validation->set_rules('kode', 'Kode', 'trim|required|callback_kode_check');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
		$this->form_validation->set_rules('cost', 'Biaya', 'trim');
		$this->form_validation->set_rules('price', 'Harga', 'trim');
		$this->form_validation->set_rules('kode_category', 'Kategori Menu', 'trim|required|callback_option_check');
		$this->form_validation->set_rules('tipe', 'Tipe', 'trim');
		if($this->form_validation->run()== FALSE){
			$this->session->keep_flashdata('alert_form');
			foreach($data as $key=>$val){
				$this->session->keep_flashdata($key);
			}
			$data['content'] = $this->parser->parse("admin_cafe/admin_menus/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->admin_menus_model->insert_entry()){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."index.php/admin_menus");
		}else{
			$this->session->keep_flashdata('alert_form');
			foreach($data as $key=>$val){
				$this->session->keep_flashdata($key);
			}

			$data['content'] = $this->parser->parse("admin_cafe/admin_menus/form",$data,true);
			$this->template->show($data,"home");
		}
	}
	
	function edit($kode=0)
	{
		$this->authentication->verify('admin_menus','edit');

		$data = $this->admin_menus_model->get_data_row($kode); 
		$data['category'] = $this->admin_menus_model->get_data_category(); 
		$data['tipe_option'] = array(''=>'','food'=>'Food','beverage'=>'Beverage'); 
		
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
		$this->form_validation->set_rules('cost', 'Biaya', 'trim');
		$this->form_validation->set_rules('price', 'Harga', 'trim');
		$this->form_validation->set_rules('kode_category', 'Kategori Menu', 'trim|required|callback_option_check');
		$this->form_validation->set_rules('img', 'Image', 'trim');
		$this->form_validation->set_rules('tipe', 'Tipe', 'trim');
		
		if($this->form_validation->run()== FALSE){
			$data['kode'] = $kode;
			$data['title_form']="Menu &raquo; Ubah";
			$data['action']="edit";
	
			$data['content'] = $this->parser->parse("admin_cafe/admin_menus/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->admin_menus_model->update_entry($kode)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."index.php/admin_menus");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."index.php/admin_menus/edit/".$kode);
		}
		
	}
	
	function kode_check($str){
		if(count($this->admin_menus_model->get_data_row($str))){
			$this->form_validation->set_message('kode_check', 'Duplicate Kode');
			return false;
		}else{
			return true;
		}
	}

	function option_check($str){
		if($str==0 || $str==""){
			$this->form_validation->set_message('option_check', 'Kategori Menu is required.');
			return false;
		}else{
			return true;
		}
	}

	function dodel($kode=0){
		$this->authentication->verify('admin_menus','del');

		if($this->admin_menus_model->delete_entry($kode)){
			$this->session->set_flashdata('alert_form', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert_form', 'Delete data failed...');
		}
		redirect(base_url()."index.php/admin_menus");
	}

	function dodel_multi(){
		$this->authentication->verify('admin_menus','del');

		if(is_array($this->input->post('kode'))){
			foreach($this->input->post('kode') as $data){
				$this->admin_menus_model->delete_entry($data);
			}
			$this->session->set_flashdata('alert_form', 'Delete ('.count($this->input->post('kode')).') data successful...');
		}else{
			$this->session->set_flashdata('alert_form', 'Nothing to delete.');
		}

		redirect(base_url()."index.php/admin_menus");
	}
	
	function douploadimages($kode){
		$module='menus';
		$config['upload_path'] = 'media/images/'.$module.'/'.$kode;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '200';
		$config['max_width']  = '200';
		$config['max_height']  = '200';

		$this->load->library('upload', $config);
		if(!file_exists($config['upload_path'])) {
			mkdir($config['upload_path']);
		}
	
		if (!$this->upload->do_upload('uploadfile'))
		{
			echo "failed|".$this->upload->display_errors();
		}	
		else
		{
			$data = array('upload_data' => $this->upload->data());
			echo "success|".$data['upload_data']['file_name'];
		}
		
	}

}
