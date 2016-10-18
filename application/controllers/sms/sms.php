<?php
class Sms extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('sms/sms_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data = $this->sms_model->get_data(); 
		$data['title_group'] 	= "SMS";
		$data['title_form'] 	= "Dashboard";

		$data['sms_kategori'] = $this->sms_model->get_sms_kategori();
		$color 		= array('#f56954','#00a65a','#f39c12','#00c0ef','#8d16c5','#d2d6de','#3c8dbc','#69d856','#eb75e4');
		$BulanIndo 	= array("","Januari", "Februari", "Maret","April", "Mei", "Juni","Juli", "Agustus", "September","Oktober", "November", "Desember");
		$start 		= array();
		$diterima 	= array();

        for($i=6;$i>=0;$i--){
            $start[] = $BulanIndo[date('n', strtotime('-'.$i.' month'))];

        	$bln 		= date('Y-m', strtotime('-'.$i.' month'));
            $diterima[] = $this->sms_model->get_sms_diterima($bln);
            $dikirim[] 	= $this->sms_model->get_sms_dikirim($bln);
            $error[] 	= $this->sms_model->get_sms_error($bln);
        }

        $data['str_bln'] 		= implode("','", $start);
        $data['str_diterima'] 	= implode(",", $diterima);
        $data['str_dikirim'] 	= implode(",", $dikirim);
        $data['str_error'] 		= implode(",", $error);

		$data['color']	= $color;

		$data['content'] = $this->parser->parse("sms/show",$data,true);
		$this->template->show($data,'home');
	}

}
