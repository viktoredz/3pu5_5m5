<?php
class Autoreply extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/opini_model');
		$this->load->model('sms/autoreply_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] 	= "SMS Info";
		$data['title_form'] 	= "Daftar Kata Kunci";
		$data['tipeoption'] 	= $this->opini_model->get_tipe('kirim');
		$data['menuoption'] 	= $this->autoreply_model->get_menu();

		$this->session->unset_userdata('filter_tipe');
		$this->session->unset_userdata('filter_menu');

		$data['content'] = $this->parser->parse("sms/autoreply/show",$data,true);
		$this->template->show($data,'home');
	}

	function filter(){
		if($_POST) {
			if($this->input->post('tipe') != '') {
				$this->session->set_userdata('filter_tipe',$this->input->post('tipe'));
			}else{
				$this->session->unset_userdata('filter_tipe');
			}
			
			if($this->input->post('menu') != '') {
				$this->session->set_userdata('filter_menu',$this->input->post('menu'));
			}else{
				$this->session->unset_userdata('filter_menu');
			}
		}
	}

	function json(){
		$this->authentication->verify('sms','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_mulai') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where("sms_info.tgl_mulai <=",$value);
				}
				elseif($field == 'tgl_akhir') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where("sms_info.tgl_akhir >=",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_tipe') != '') {
			$this->db->where('sms_info.id_sms_tipe',$this->session->userdata('filter_tipe'));
		}
		
		if($this->session->userdata('filter_menu') != '') {
			$this->db->where('sms_info.code_sms_menu',$this->session->userdata('filter_menu'));
		}

		$rows_all = $this->autoreply_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_mulai') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where("sms_info.tgl_mulai <=",$value);
				}
				elseif($field == 'tgl_akhir') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where("sms_info.tgl_akhir >=",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_tipe') != '') {
			$this->db->where('sms_info.id_sms_tipe',$this->session->userdata('filter_tipe'));
		}
		
		if($this->session->userdata('filter_menu') != '') {
			$this->db->where('sms_info.code_sms_menu',$this->session->userdata('filter_menu'));
		}

		$this->db->order_by('code_sms_menu','ASC');
		$rows = $this->autoreply_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'			=> $no++,
				'id_info'		=> $act->id_info,
				'katakunci'		=> strtoupper($act->katakunci),
				'tgl_mulai'		=> $act->tgl_mulai,
				'tgl_akhir'		=> $act->tgl_akhir,
				'pesan'			=> $act->pesan,
				'code_sms_menu' => strtoupper($act->code_sms_menu),
				'id_sms_tipe'	=> $act->id_sms_tipe,
				'tipe'			=> $act->tipe,
				'edit'			=> 1,
				'delete'		=> 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function add(){
		$this->authentication->verify('sms','add');

        $this->form_validation->set_rules('katakunci', 'Kata Kunci', 'trim|required|callback_chekKeyword');
        $this->form_validation->set_rules('pesan', 'Pesan', 'trim|required');
        $this->form_validation->set_rules('code_sms_menu', 'code_sms_menu', 'trim');
        $this->form_validation->set_rules('id_sms_tipe', 'id_sms_tipe', 'trim');
        $this->form_validation->set_rules('tgl_mulai', 'tgl_mulai', 'trim');
        $this->form_validation->set_rules('tgl_akhir', 'tgl_akhir', 'trim');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "SMS Info";
			$data['title_form']="Tambah Kata Kunci";
			$data['action']="add";
			$data['tgl_mulai'] 		= time();
			$data['tgl_akhir'] 		= mktime(0,0,0,date("m")+1,date("d"),(date("Y")));

			$data['menuoption'] 	= $this->autoreply_model->get_menu();
			$data['tipeoption'] 	= $this->opini_model->get_tipe('kirim');
			
			$data['content'] = $this->parser->parse("sms/autoreply/form",$data,true);
		}elseif($id = $this->autoreply_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'sms/autoreply/');
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/autoreply/add");
		}

		$this->template->show($data,"home");
	}

	function edit($id=""){
		$this->authentication->verify('sms','edit');

        $this->form_validation->set_rules('pesan', 'Pesan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data 	= $this->autoreply_model->get_data_row($id); 

			$data['title_group']= "SMS Info";
			$data['title_form']	= "Ubah Kata Kunci";
			$data['action']		= "edit";
			$data['id']			= $id;
			$data['tgl_mulai'] 		= strtotime($data['tgl_mulai'] );
			$data['tgl_akhir'] 		= strtotime($data['tgl_akhir'] );

			$data['menuoption'] 	= $this->autoreply_model->get_menu();
			$data['tipeoption'] 	= $this->opini_model->get_tipe('kirim');

			$data['content'] 	= $this->parser->parse("sms/autoreply/form",$data,true);
		}elseif($this->autoreply_model->update_entry($id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."sms/autoreply/edit/".$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/autoreply/edit/".$id);
		}

		$this->template->show($data,"home");
	}

	function chekKeyword($str=""){
		$this->db->where("katakunci",$str);
		$keyword = $this->db->get("sms_info")->row();
		if(!empty($keyword->katakunci)){
      		$this->form_validation->set_message('chekKeyword', 'Kata Kunci sudah digunakan');
			return false;
		}else{
			return true;
		}
	}

	function dodel($id=""){
		$this->authentication->verify('sms','del');

		if($this->autoreply_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

}
