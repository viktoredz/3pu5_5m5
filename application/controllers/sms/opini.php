<?php
class Opini extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/opini_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] = "Opini Publik";
		$data['title_form'] = "SMS Diterima";

		$data['tipeoption'] 	= $this->opini_model->get_tipe();
		$data['statusoption'] 	= array(
									'terima'=>'Pesan Masuk',
									'terima-baru'=>' - Pesan Baru',
									'terima-baca'=>' - Sudah Dibaca',
									'balas'=>'Balasan'
								);
		$this->session->set_userdata('filter_status','terima-baru');
		$this->session->unset_userdata('filter_tipe');
		$data['statusoption_active'] = $this->session->userdata('filter_status');

		$data['content'] = $this->parser->parse("sms/opini/show",$data,true);
		$this->template->show($data,'home');
	}

	function filter(){
		if($_POST) {
			if($this->input->post('tipe') != '') {
				$this->session->set_userdata('filter_tipe',$this->input->post('tipe'));
			}else{
				$this->session->unset_userdata('filter_tipe');
			}
			
			if($this->input->post('status') != '') {
				$this->session->set_userdata('filter_status',$this->input->post('status'));
			}
		}
	}

	function get_tipe($status="balas"){
		if($status=="balas"){
			$tipe = $this->opini_model->get_tipe("kirim");

		}else{
			$tipe = $this->opini_model->get_tipe("terima");
		}

		$data['tipe'] = "<option value=''>-- Pilih Kategori --</option>";
		foreach($tipe as $x){
			$data['tipe'] .= "<option value='".$x->id_tipe."'>".$x->nama."</option>";
		}

		header('Content-type: text/X-JSON');
		echo json_encode($data);
		exit;
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
					$this->db->like("opini.created_on",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_tipe') != '') {
			$this->db->where('sms_opini.id_sms_tipe',$this->session->userdata('filter_tipe'));
		}

		if($this->session->userdata('filter_status') != '') {
			if($this->session->userdata('filter_status')=="balas"){
				$status = "('draft','kirim')";
			}
			elseif($this->session->userdata('filter_status')=="terima"){
				$status = "('balas','baru','baca')";
			}
			elseif($this->session->userdata('filter_status')=="terima-baca"){
				$status = "('balas','baca')";
			}else{
				$status = "('baru')";
			}

			$this->db->where('sms_opini.status IN '.$status);
		}

		$rows_all = $this->opini_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'created_on') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("opini.created_on",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_tipe') != '') {
			$this->db->where('sms_opini.id_sms_tipe',$this->session->userdata('filter_tipe'));
		}
		
		if($this->session->userdata('filter_status') != '') {
			if($this->session->userdata('filter_status')=="balas"){
				$status = "('draft','kirim')";
			}
			elseif($this->session->userdata('filter_status')=="terima"){
				$status = "('balas','baru','baca')";
			}
			elseif($this->session->userdata('filter_status')=="terima-baca"){
				$status = "('balas','baca')";
			}else{
				$status = "('baru')";
			}

			$this->db->where('sms_opini.status IN '.$status);
		}
		$rows = $this->opini_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'			=> $no++,
				'id_opini'		=> $act->id_opini,
				'nomor'			=> $act->nomor,
				'pesan'			=> $act->pesan,
				'status'		=> ucwords($act->status),
				'created_on'	=> $act->created_on,
				'reply'			=> ($act->status == "draft" || $act->status == "kirim" ? 0:1),
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

	function dodel($id=""){
		$this->authentication->verify('sms','del');

		if($this->opini_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}


	public function detail($id=0)
	{
		$data = $this->opini_model->get_data_ID($id);
		$data['title_form']	= "Detail SMS";
		$data['action']		= "detail";
		$data['id']			= $id;


		if($data['status']=="draft" || $data['status']=="kirim"){
			$this->reply($id);
		}else{
			die($this->parser->parse('sms/opini/detail', $data));
		}
	}

	public function reply($id=0)
	{
		$data = $this->opini_model->get_data_ID($id);
		$data['title_form']	= "Balas SMS";
		$data['action']		= "detail";
		$data['id']			= $id;
        $this->form_validation->set_rules('pesan', 'Pesan', 'trim|required');
        $this->form_validation->set_rules('id_sms_tipe', 'Kategori Opini', 'trim|required');

		if($this->form_validation->run()== FALSE){
			if($data['status'] != "kirim" && $data['status'] != "draft"){
				$data['pesan'] = "";
			}
			$data['tipeoption'] 	= $this->opini_model->get_tipe('kirim');

			die($this->parser->parse('sms/opini/form', $data));
		}else{
			
			if($this->input->post('status')=="kirim"){
				$values = array(
					'CreatorID'			=> $this->session->userdata('username'),
					'DestinationNumber'	=> $data['nomor'],
					'TextDecoded' 		=> $this->input->post('pesan')
				);
				$this->db->insert('outbox',$values);
			}


			if($data['status'] != "kirim" && $data['status'] != "draft"){

				$update = array("status"=>"balas");
				$this->db->where("id_opini",$id);
				$this->db->update('sms_opini',$update);

				$data = array(
					'id_sms_tipe'	=> $this->input->post('id_sms_tipe'),
					'pesan'			=> $this->input->post('pesan'),
					'nomor'			=> $data['nomor'],
					'status'		=> $this->input->post('status')
				);
				if($this->db->insert('sms_opini',$data)){
					die("OK|");
				}else{
					die("Error|Proses data gagal");
				}
			}else{
				$update = array(
					'id_sms_tipe'	=> $this->input->post('id_sms_tipe'),
					'pesan'			=> $this->input->post('pesan'),
					"status"		=>$this->input->post('status')
				);
				$this->db->where("id_opini",$id);
				if($this->db->update('sms_opini',$update)){
					die("OK|");
				}else{
					die("Error|Proses data gagal");
				}
			}

		}
		
	}

	public function move($id=0)
	{
		$data = $this->opini_model->get_data_ID($id);
		$data['title_form']	= "Pindah Kategori";
		$data['action']		= "move";
		$data['id']			= $id;

        $this->form_validation->set_rules('id_sms_tipe', 'Kategori Opini', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['tipeoption'] 	= $this->opini_model->get_tipe('terima');

			die($this->parser->parse('sms/opini/move', $data));
		}else{
			if($this->opini_model->move($id)){
				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}
		
	}
}
