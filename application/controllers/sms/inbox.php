<?php
class Inbox extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/inbox_model');
		$this->load->model('sms/opini_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] = "Inbox";
		$data['title_form'] = "SMS Diterima";

		$data['content'] = $this->parser->parse("sms/inbox/show",$data,true);
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

				if($field == 'ReceivingDateTime') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("inbox.ReceivingDateTime",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows_all = $this->inbox_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'ReceivingDateTime') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("inbox.ReceivingDateTime",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows = $this->inbox_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'				=> $no++,
				'ID'				=> $act->ID,
				'SenderNumber'		=> ($act->Processed=="false" ? "<b>".$act->SenderNumber."</b>" : ucwords($act->SenderNumber)),
				'TextDecoded'		=> ($act->Processed=="false" ? "<b>".$act->TextDecoded."</b>" : ucwords($act->TextDecoded)),
				'Processed'			=> ($act->Processed=="false" ? "<b>".ucwords($act->Processed)."</b>" : ucwords($act->Processed)),
				'ReceivingDateTime'	=> ($act->Processed=="false" ? "<b>".$act->ReceivingDateTime."</b>" : ucwords($act->ReceivingDateTime)),
				'edit'				=> 1,
				'delete'			=> 1
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

		if($this->inbox_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}


	public function detail($id=0)
	{
		$data = $this->inbox_model->get_data_ID($id);
		$data['title_form']	= "Detail SMS";
		$data['action']		= "detail";
		$data['id']			= $id;

		die($this->parser->parse('sms/inbox/detail', $data));
	}


	public function reply($id=0)
	{
		$data = $this->inbox_model->get_data_ID($id);
		$data['title_form']	= "Balas SMS";
		$data['action']		= "detail";
		$data['id']			= $id;
        $this->form_validation->set_rules('TextDecoded', 'Pesan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse('sms/inbox/form', $data));
		}else{
			$values = array(
				'CreatorID'			=> $this->session->userdata('username'),
				'DestinationNumber'	=> $data['SenderNumber'],
				'TextDecoded' 		=> $this->input->post('TextDecoded')
			);

			if($this->db->insert('outbox', $values)){
				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}
		
	}

	public function move($id=0)
	{
		$data = $this->inbox_model->get_data_ID($id);
		$data['title_form']	= "Pindah SMS -&raquo; Opini Publik";
		$data['action']		= "move";
		$data['id']			= $id;

        $this->form_validation->set_rules('id_sms_tipe', 'Kategori Opini', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['tipeoption'] 	= $this->opini_model->get_tipe('terima');

			die($this->parser->parse('sms/inbox/move', $data));
		}else{
			if($this->inbox_model->move($id)){
				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}
		
	}}
