<?php
class Cli extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('cli_model');
		$this->load->model('epus');
		$this->load->model('bpjs');
	}
	
	function index($args = ""){
		if($this->input->is_cli_request()) {
			ini_set('max_execution_time', 0);
			ini_set('max_input_time', -1);
			ini_set('html_errors', 'Off');
			ini_set('register_argc_argv', 'On');
			ini_set('output_buffering', 'Off');
			ini_set('implicit_flush', 'On');
			
			$cl_phc = array('P3171070101');
			foreach ($cl_phc as $code) {
				$this->pasien_search($code);
			}
			//$this->cli_model->reset_pasien_bpjs_nohp_null();
			$this->sync_bpjs();

		}else{
			die("Please access via cli");
		}
	}

    function sync_bpjs(){
    	sleep(1);
		$pasien = $this->cli_model->get_pasien_bpjs_nohp_null();

		if(!empty($pasien->bpjs)){
	      	$data = $this->bpjs->bpjs_search('bpjs',$pasien->bpjs);

	      	if(isset($data['metaData']) && $data['metaData']['code']==200){
		      	$res  = $data['response'];
				if($this->cli_model->update_pasien_bpjs_nohp_null($pasien->cl_pid,$res)){
					echo "\n";
					$this->sync_bpjs();
				}else{
					echo "\nerror database";
				}
	      	}else{
	      		print_r($data);
	      	}
		}else{
			echo "done";
		}

    }

	function pasien_search($cl_phc=""){
		$config 	= $this->epus->get_config("get_data_allPasien");
		$exclude 	= "('x')";

		$url 		= $config['server'];
		$qr 		= $this->input->post("qr");

		if ($cl_phc=='') {
			$puskesmas 	="P".$this->session->userdata('puskesmas');
		}else{
			$puskesmas 	= $cl_phc;//"P".$this->session->userdata('puskesmas');
		}

		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'kodepuskesmas' 	=> $puskesmas,
	        'exclude' 			=> $exclude,
	        'filterPasien' 	 	=> $qr,
	        'limit' 			=> 999999,
	        'request_output' 	=> $config['request_output'],
	        'request_time' 		=> $config['request_time'],
	        'request_token' 	=> $config['request_token']
	    );

		$curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,count($fields_string));
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
		curl_close($curl);

		$res = json_decode(($result), true);
		$pbk = array();
		if(is_array($res['content'])){
			$pasien = $res['content'];
			$count 	= 0;
			foreach ($pasien as $dt) {
				$register = $this->cli_model->register($dt);
				//$code = substr($puskesmas,1,10);
				//$next = $this->cli_model->register($dt, $code);
				echo "\n".$count++." -> ".$dt['id'].":".$register;
			}
		}else{
			echo "No data";
		}
	}

    function sync_data_pasien($puskesmas=""){
    	echo "\n".$puskesmas."\n";
		$config 	= $this->epus->get_config("form_get_pasienByDiagnosa");
		$exclude 	= $this->cli_model->exclude_cl_pid($puskesmas);

		$url 		= $config['server'];
		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'kodepuskesmas' 	=> $puskesmas,
	        'exclude' 			=> $exclude,
	        'filterDiagnosa' 	=> 'i10',
	        'limit' 			=> 100,
	        'request_output' 	=> $config['request_output'],
	        'request_time' 		=> $config['request_time'],
	        'request_token' 	=> $config['request_token']
	    );


		$curl = curl_init();

        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POST,count($fields_string));
		curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
		curl_close($curl);

		$res = json_decode(($result), true);
		if(is_array($res['content'])){
			$pasien = $res['content'];
			$count 	= 0;
			foreach ($pasien as $dt) {
				$code = substr($puskesmas,1,10);
				$next = $this->cli_model->register($dt, $code);
				$count++;
			}

			if($next==1) {
				echo date("d-m-Y H:i:s")." -> ".$count." Imported\n";
				$this->sync_data_pasien($puskesmas);
			}else{
				echo "No new data";
			}
		}else{
			echo "No new data";
		}
    }

}
