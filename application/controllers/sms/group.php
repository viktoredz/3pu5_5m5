<?php
class Group extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/grup_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] = "Grup Penerima";
		$data['title_form'] = "Grup Terdaftar";

		$data['content'] = $this->parser->parse("sms/group/show",$data,true);
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
					$this->db->like("sms_grup.created_on",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows_all = $this->grup_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'created_on') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("sms_grup.created_on",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->grup_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'		=> $no++,
				'id'		=> $act->id_grup,
				'nama' 		=> $act->nama,
				'anggota'	=> $act->anggota,
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

        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "Grup";
			$data['title_form']="Tambah Grup";
			$data['action']="add";
			$data['nomor']="";

			$data['grupoption'] 	= $this->grup_model->get_grupoption();
		
			$data['content'] = $this->parser->parse("sms/group/form",$data,true);
		}elseif($id = $this->grup_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'sms/group/');
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/group/add");
		}

		$this->template->show($data,"home");
	}

	function edit($id=""){
		$this->authentication->verify('sms','edit');

        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data 	= $this->grup_model->get_data_row($id); 

			$data['title_group'] 	= "Buku Telepon";
			$data['title_form']		= "Ubah Nomor Telepon";
			$data['action']			= "edit";
			$data['id']			= $id;

			$data['content'] 	= $this->parser->parse("sms/group/form",$data,true);
		}elseif($this->grup_model->update_entry($id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."sms/group/edit/".$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/group/edit/".$id);
		}

		$this->template->show($data,"home");
	}

	function dodel($id=""){
		$this->authentication->verify('sms','del');

		if($this->grup_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
}
