<?php
class Sentitems extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/sentitems_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] = "Sent Items";
		$data['title_form'] = "SMS Dikirim";

		$data['content'] = $this->parser->parse("sms/sentitems/show",$data,true);
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

				if($field == 'SendingDateTime') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("sentitems.SendingDateTime",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows_all = $this->sentitems_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'SendingDateTime') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("sentitems.SendingDateTime",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$rows = $this->sentitems_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'				=> $no++,
				'ID'				=> $act->ID,
				'DestinationNumber'	=> $act->DestinationNumber,
				'TextDecoded'		=> $act->TextDecoded,
				'Status'			=> $act->Status,
				'SendingDateTime'	=> $act->SendingDateTime,
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

		if($this->sentitems_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	public function detail($id=0)
	{
		$data = $this->sentitems_model->get_data_ID($id);
		$data['title_form']	= "Detail SMS";
		$data['action']		= "detail";
		$data['id']			= $id;

		die($this->parser->parse('sms/sentitems/detail', $data));
	}

	public function resend($id=0)
	{
		$data = $this->sentitems_model->get_data_ID($id);
		$values = array(
			'CreatorID'			=> $this->session->userdata('username'),
			'DestinationNumber'	=> $data['DestinationNumber'],
			'TextDecoded' 		=> $this->input->post('TextDecoded')
		);

		if($this->db->insert('outbox', $values)){
			die("OK|");
		}else{
			die("Error|Proses data gagal");
		}
		
	}
}
