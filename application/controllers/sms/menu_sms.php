<?php
class Menu_sms extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/menu_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] = "Menu SMS";
		$data['title_form'] = "Daftar Menu";

		$data['content'] = $this->parser->parse("sms/menu/show",$data,true);
		$this->template->show($data,'home');
	}

	function json(){
		$this->authentication->verify('sms','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'created_on') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("sms_info_menu.created_on",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows_all = $this->menu_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'created_on') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("sms_info_menu.created_on",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->menu_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'		=> $no++,
				'code'		=> $act->code,
				'created_on'=> $act->created_on,
				'edit'		=> 1,
				'delete'	=> 1
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

        $this->form_validation->set_rules('code', 'code', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] 	= "Menu SMS";
			$data['title_form']		= "Tambah Menu";
			$data['action']="add";
			$data['nomor']="";

			$data['content'] = $this->parser->parse("sms/menu/form",$data,true);
		}elseif($id = $this->menu_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'sms/menu_sms/');
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/menu_sms/add");
		}

		$this->template->show($data,"home");
	}

	function dodel($id=""){
		$this->authentication->verify('sms','del');

		if($this->menu_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
}
