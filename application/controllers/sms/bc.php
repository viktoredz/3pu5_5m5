<?php
class Bc extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/opini_model');
		$this->load->model('sms/bc_model');
		$this->load->model('sms/pbk_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] = "SMS Masal";
		$data['title_form'] = "Daftar Jadwal SMS";

		$data['jenisoption'] 	= array("bulanan" => "Bulanan", "mingguan" => "Mingguan" ,"harian" => "Harian", "tidak" => "1x");
		$data['tipeoption'] 	= $this->opini_model->get_tipe('kirim');

		$this->session->unset_userdata('filter_tipe');
		$this->session->unset_userdata('filter_is_loop');

		$data['content'] = $this->parser->parse("sms/bc/show",$data,true);
		$this->template->show($data,'home');
	}

	function filter(){
		if($_POST) {
			if($this->input->post('tipe') != '') {
				$this->session->set_userdata('filter_tipe',$this->input->post('tipe'));
			}else{
				$this->session->unset_userdata('filter_tipe');
			}
			
			if($this->input->post('is_loop') != '') {
				$this->session->set_userdata('filter_is_loop',$this->input->post('is_loop'));
			}else{
				$this->session->unset_userdata('filter_is_loop');
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
					$this->db->where("sms_bc.tgl_mulai <=",$value);
				}
				elseif($field == 'tgl_akhir') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where("sms_bc.tgl_akhir >=",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_tipe') != '') {
			$this->db->where('sms_bc.id_sms_tipe',$this->session->userdata('filter_tipe'));
		}
		
		if($this->session->userdata('filter_is_loop') != '') {
			$this->db->where('sms_bc.is_loop',$this->session->userdata('filter_is_loop'));
		}

		$rows_all = $this->bc_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_mulai') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where("sms_bc.tgl_mulai <=",$value);
				}
				elseif($field == 'tgl_akhir') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where("sms_bc.tgl_akhir >=",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_tipe') != '') {
			$this->db->where('sms_bc.id_sms_tipe',$this->session->userdata('filter_tipe'));
		}
		
		if($this->session->userdata('filter_is_loop') != '') {
			$this->db->where('sms_bc.is_loop',$this->session->userdata('filter_is_loop'));
		}

		$this->db->order_by('id_bc','ASC');
		$rows = $this->bc_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'			=> $no++,
				'id_info'		=> $act->id_bc,
				'tgl_mulai'		=> $act->tgl_mulai,
				'tgl_akhir'		=> $act->tgl_akhir,
				'pesan'			=> $act->pesan,
				'id_sms_tipe'	=> $act->id_sms_tipe,
				'tipe'			=> $act->tipe,
				'penerima'		=> $act->jml,
				'status'		=> ucwords($act->status),
				'is_loop'		=> ucwords($act->is_loop),
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

	function json_penerima($id=""){
		$this->authentication->verify('sms','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like('sms_pbk.'.$field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows_all = $this->bc_model->get_penerima($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like('sms_pbk.'.$field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows = $this->bc_model->get_penerima($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'id'		=> $act->nomor,
				'nomor'		=> '+62 - '.$act->nomor,
				'nama' 		=> $act->nama,
				'grup' 		=> $act->grup
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function json_pbk($id=""){
		$this->authentication->verify('sms','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like('sms_pbk.'.$field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_id_sms_grup') != '') {
			$this->db->where('sms_pbk.id_sms_grup',$this->session->userdata('filter_id_sms_grup'));
		}
		$rows_all = $this->bc_model->get_pbk($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like('sms_pbk.'.$field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_id_sms_grup') != '') {
			$this->db->where('sms_pbk.id_sms_grup',$this->session->userdata('filter_id_sms_grup'));
		}
		$rows = $this->bc_model->get_pbk($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'id'		=> $act->nomor,
				'nomor'		=> '+62 - '.$act->nomor,
				'nama' 		=> $act->nama
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

        $this->form_validation->set_rules('pesan', 'Pesan', 'trim|required');
        $this->form_validation->set_rules('id_sms_tipe', 'id_sms_tipe', 'trim');
        $this->form_validation->set_rules('tgl_mulai', 'tgl_mulai', 'trim');
        $this->form_validation->set_rules('tgl_akhir', 'tgl_akhir', 'trim');
        $this->form_validation->set_rules('is_loop', 'is_loop', 'trim|required');
        $this->form_validation->set_rules('is_harian', 'is_harian', 'trim|required');
        $this->form_validation->set_rules('is_mingguan', 'is_mingguan', 'trim');
        $this->form_validation->set_rules('is_bulanan', 'is_bulanan', 'trim');
        $this->form_validation->set_rules('status', 'status', 'trim');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "SMS Masal";
			$data['title_form']="Tambah Pesan";
			$data['action']="add";

			$data['harioption'] 	= array("0" => "Minggu", "1" => "Senin", "2" => "Selasa", "3" => "Rabu" ,"4" => "Kamis","5" => "Jumat","6" => "Sabtu");
			$data['jenisoption'] 	= array("tidak" => "1x" ,"harian" => "Harian", "mingguan" => "Mingguan", "bulanan" => "Bulanan");
			$data['statusoption'] 	= array("draft" => "Draft", "aktif" => "Aktif");
			$data['tipeoption'] 	= $this->opini_model->get_tipe('kirim');

			$data['tgl_mulai'] 		= time();
			$data['tgl_akhir'] 		= mktime(0,0,0,date("m")+1,date("d"),(date("Y")));
			$data['is_harian'] 		= time();
			$data['is_mingguan'] 	= date("w");
			$data['is_bulanan'] 	= date("d");
			
			$data['content'] = $this->parser->parse("sms/bc/form",$data,true);
		}elseif($id = $this->bc_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'sms/bc/edit/'.$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/bc/add");
		}

		$this->template->show($data,"home");
	}

	function edit($id=""){
		$this->authentication->verify('sms','edit');

        $this->form_validation->set_rules('pesan', 'Pesan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data 	= $this->bc_model->get_data_row($id); 

			$data['title_group']= "SMS Masal";
			$data['title_form']	= "Detail Pesan";
			$data['action']		= "edit";
			$data['id']			= $id;
			$data['tgl_mulai'] 		= strtotime($data['tgl_mulai'] );
			$data['tgl_akhir'] 		= strtotime($data['tgl_akhir'] );
			$data['is_harian'] 		= strtotime($data['is_harian'] );
			$data['harioption'] 	= array("0" => "Minggu", "1" => "Senin", "2" => "Selasa", "3" => "Rabu" ,"4" => "Kamis","5" => "Jumat","6" => "Sabtu");
			$data['jenisoption'] 	= array("tidak" => "1x" ,"harian" => "Harian", "mingguan" => "Mingguan", "bulanan" => "Bulanan");
			$data['statusoption'] 	= array("draft" => "Draft", "aktif" => "Aktif");
			$data['tipeoption'] 	= $this->opini_model->get_tipe('kirim');
			$data['grupoption'] 	= $this->pbk_model->get_grupoption();

			$data['content'] 	= $this->parser->parse("sms/bc/form",$data,true);
		}elseif($this->bc_model->update_entry($id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."sms/bc/edit/".$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/bc/edit/".$id);
		}

		$this->template->show($data,"home");
	}

	function remove_number($id, $nomor){
		$this->bc_model->remove_number($id, $nomor);
	}

	function add_number($id, $nomor){
		$this->bc_model->add_number($id, $nomor);
	}

	function dodel($id=""){
		$this->authentication->verify('sms','del');

		if($this->bc_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

}
