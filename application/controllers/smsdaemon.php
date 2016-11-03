<?php
class Smsdaemon extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('sms/inbox_model');
		$this->load->model('sms/opini_model');
		$this->load->model('sms/autoreply_model');
		$this->load->model('sms/bc_model');
		$this->load->model('sms/pbk_model');
		$this->load->model('sms/setting_model');
		$this->load->model('epus');
	}
	
	function index($args = ""){
		if($this->input->is_cli_request()) {
			ini_set('max_execution_time', 0);
			ini_set('max_input_time', -1);
			ini_set('html_errors', 'Off');
			ini_set('register_argc_argv', 'On');
			ini_set('output_buffering', 'Off');
			ini_set('implicit_flush', 'On');
			
			for($i=1;$i<12;$i++){
				echo("\n".date("d-m-Y h:i:s") ." ".$i." ".$args." versi 1.0");
				
				$this->sms_reply($args);

				$this->sms_autoreply($args);

				$this->sms_opini($args);

				$this->sms_broadcast($args);
				
				$this->sms_daftar($args);
				sleep(5);
			}
		}else{
			die("access via cli");
		}

	}
	
	function sms_send($nomor = "", $pesan="" ,$ID=""){
		$data = array();
		$time = date("Y-m-d H:i:s");
		$data['CreatorID'] 			= "infokes";
		$data['InsertIntoDB'] 		= $time;
		$data['SendingDateTime'] 	= $time;
		$data['SendingTimeOut'] 	= $time;
		$data['DestinationNumber'] 	= $nomor;
		$data['TextDecoded'] 		= $pesan;

		if($this->db->insert('outbox',$data)){
			if($ID != ""){
				$this->db->where('ID',$ID);
				$this->db->update('inbox',array('Processed'=>"true"));
			}
		}
	}
	
	function sms_wrong($nomor = "", $pesan="" , $menu=""){
		$data = array();

		$pesan .= "\ngunakan kata kunci: ";
		if($menu==""){
			$info = $this->db->get("sms_info_menu")->result();
			$key = array();
			foreach ($info as $rows) {
				$key[]= $rows->code;
				$tmpt   = $rows->code;
			}
			$pesan .= "\n".implode(",", $key);

			$this->db->where("jenis", "terima");
			$opini = $this->db->get("sms_tipe")->result();
			$key = array();
			foreach ($opini as $rows) {
				$key[]= $rows->nama;
				$tmpt   = $rows->nama;
			}
			$pesan .= "\n\natau kirim opini dengan kata kunci: ";
			$pesan .= "\n".implode(",", $key);
			$pesan .= "\ncontoh:\n".$tmpt."<spasi>kalimat pesan";
		}else{
			$this->db->where("code_sms_menu", $menu);
			$this->db->where("tgl_mulai <= ", date("Y-m-d"));
			$this->db->where("tgl_akhir >= ", date("Y-m-d"));
			$info = $this->db->get("sms_info")->result();
			$tmpt = "";
			$key = array();
			foreach ($info as $rows) {
				$key[]= $rows->katakunci;
				$tmpt   = $rows->katakunci;
			}
			$pesan .= implode(",", $key)."\ncontoh:".$menu."<spasi>".$tmpt;
		}

		$time = date("Y-m-d H:i:s");
		$data['CreatorID'] 			= "infokes";
		$data['InsertIntoDB'] 		= $time;
		$data['SendingDateTime'] 	= $time;
		$data['SendingTimeOut'] 	= $time;
		$data['DestinationNumber'] = $nomor;
		$data['TextDecoded'] = $pesan;

		$this->db->insert('outbox',$data);
	}

	function sms_reply($args = ""){
		echo "\nsms.sms_reply ...\n";

		$operator = "'*123#','*111#','V-Tri','+3','TELKOMSEL','1818'";
		//$operator = "'*123#'";

		//jika sms blm di proses, bukan operator, kata pertama menu 
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.") AND CHAR_LENGTH(SenderNumber)>=10");
		$this->db->where("SUBSTRING_INDEX(TextDecoded,' ',1) NOT IN (SELECT `code` FROM `sms_info_menu`)");
		$this->db->where("SUBSTRING_INDEX(TextDecoded,' ',1) NOT IN (SELECT `nama` FROM `sms_tipe` WHERE jenis='terima')");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) NOT IN ("UMUM","Umum","umum","BYR","BPJS","Byr","Bpjs","byr","bpjs")');
		$inbox = $this->db->get("inbox")->result();
		foreach ($inbox as $rows) {

			$this->sms_wrong($rows->SenderNumber,"format sms salah");

			$update = array();
			$update['Processed'] = 'true';
			$this->db->where('ID',$rows->ID);
			$this->db->update('inbox',$update);
		}
	}
	

	function sms_autoreply($args = ""){
		echo "sms.autoteply ...\n";

		$operator = "'*123#','*111#','V-Tri','+3','TELKOMSEL','1818'";

		//jika sms blm di proses, bukan operator, kata pertama menu 
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.") AND CHAR_LENGTH(SenderNumber)>=10");
		$this->db->where("SUBSTRING_INDEX(TextDecoded,' ',1) IN (SELECT `code` FROM `sms_info_menu`)");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) NOT IN ("UMUM","Umum","umum","BYR","BPJS","Byr","Bpjs","byr","bpjs")');
		$inbox = $this->db->get("inbox")->result();
		foreach ($inbox as $rows) {
			$text = explode(" ",$rows->TextDecoded);

			if(isset($text[1])) {
				$this->db->where("katakunci",$text[1]);
				$errmsg = "katakunci tidak tersedia";
			}else {
				$this->db->where("katakunci","##");
				$errmsg = "silahkan ";
			}

			$this->db->where("code_sms_menu",$text[0]);
			$sms = $this->db->get("sms_info")->row();
			if(!empty($sms->pesan)){
				$this->sms_send($rows->SenderNumber,$sms->pesan);
			}else{
				$this->sms_wrong($rows->SenderNumber,$errmsg,$text[0]);
			}

			$update = array();
			$update['Processed'] = 'true';
			$this->db->where('ID',$rows->ID);
			$this->db->update('inbox',$update);
		}
	}
	
	function sms_opini($args = ""){
		echo "sms.opini ...\n";

		$operator = "'*123#','*111#','V-Tri','+3','TELKOMSEL','1818'";

		//jika sms blm di proses, bukan operator, kata pertama opini, 
		$this->db->select('ID, SUBSTRING_INDEX(`TextDecoded`," ",1) as `Kategori`,`SenderNumber`,`TextDecoded`,`sms_tipe`.`id_tipe`',false);
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.") AND CHAR_LENGTH(SenderNumber)>=10");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) IN (SELECT `nama` FROM `sms_tipe` WHERE jenis="terima")');
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) NOT IN ("UMUM","Umum","umum","BYR","BPJS","Byr","Bpjs","byr","bpjs")');
		$this->db->join('sms_tipe','sms_tipe.nama=SUBSTRING_INDEX(`TextDecoded`," ", 1)','inner');
		$inbox = $this->db->get("inbox")->result();
		foreach ($inbox as $rows) {
			$num_kategori = strlen($rows->Kategori)+1;

			$opini = array();
			$opini['id_sms_tipe'] = $rows->id_tipe;
			$opini['pesan'] = substr($rows->TextDecoded,$num_kategori);
			$opini['nomor'] = $rows->SenderNumber;
			if($this->db->insert("sms_opini",$opini)){
				$this->db->where('ID',$rows->ID);
				$this->db->delete('inbox');
			}
		}
	}
	
	function sms_daftar($args = ""){
		echo "sms.daftar ...\n";

		$operator = "'*123#','*111#','V-Tri','+3','TELKOMSEL','1818'";
		$format   = "\nKetik: BYR<spasi>NIK<spasi>KD POLI<spasi>KD PUSKESMAS<spasi>DD-MM-YYYY\natau Ketik:BPJS<spasi>NO BPJS<spasi>KD POLI<spasi>KD PUSKESMAS<spasi>DD-MM-YYYY";

		//jika sms blm di proses, bukan operator, BYR/BPJS daftar 
		$this->db->select('ID, SUBSTRING_INDEX(`TextDecoded`," ",1) as `keyword`,`SenderNumber`,`TextDecoded`',false);
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.") AND CHAR_LENGTH(SenderNumber)>=10");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) IN ("UMUM","Umum","umum","BYR","BPJS","Byr","Bpjs","byr","bpjs")');
		$inbox = $this->db->get("inbox")->result_array();
		foreach ($inbox as $rows) {
			$keyword = strtoupper($rows['keyword']);
            $text   = explode(" ",$rows['TextDecoded']);
            if(count($text)==5){
	            $poli 		= $text[2];
	            $puskesmas 	= $text[3];
	            $tgl 		= $text[4];

				if($keyword == "BYR" || $keyword == "UMUM"){
		            $nik 		= $text[1];

					$this->db->where("nik",$nik);
					$pbk = $this->db->get("sms_pbk")->row();
					if(!empty($pbk->cl_pid)){
			            $cl_pid	= $pbk->cl_pid;
					}else{
						$reply = "Maaf, NIK anda belum terdaftar".$format;
						$this->sms_send($rows['SenderNumber'],$reply,$rows['ID']);
						continue;
					}
				}else{
		            $bpjs 		= $text[1];

					$this->db->where("bpjs",$bpjs);
					$pbk = $this->db->get("sms_pbk")->row();
					if(!empty($pbk->cl_pid)){
			            $cl_pid	= $pbk->cl_pid;
					}else{
						$reply = "Maaf, No.BPJS anda belum terdaftar".$format;
						$this->sms_send($rows['SenderNumber'],$reply,$rows['ID']);
						continue;
					}
				}

				$this->db->where("keyword",$puskesmas);
				$puskesmas = $this->db->get("cl_phc")->row();
				if(!empty($puskesmas->code)){
		            $valid_puskesmas	= $puskesmas->code;
				}else{
					$kode = array();
					$keyword = $this->db->get("cl_phc")->result();
					foreach ($keyword as $val) {
						$kode[] = $val->keyword;
					}
					$keyword_puskesmas = implode("\n-", $kode);

					$reply = "Maaf, Kode puskesmas salah, gunakan: \n-".$keyword_puskesmas;
					$this->sms_send($rows['SenderNumber'],$reply,$rows['ID']);
					continue;
				}


				$this->db->where("keyword",$poli);
				$clinic = $this->db->get("cl_clinic")->row();
				if(!empty($clinic->kode)){
		            $valid_poli	= $clinic->kode;
				}else{
					$kode = array();
					$this->db->where("keyword <> ''");
					$keyword = $this->db->get("cl_clinic")->result();
					foreach ($keyword as $val) {
						$kode[] = $val->keyword;
					}
					$keyword_poli = implode("\n-", $kode);

					$reply = "Maaf, Kode poli salah, gunakan: \n-".$keyword_poli;
					$this->sms_send($rows['SenderNumber'],$reply,$rows['ID']);
					continue;
				}


            	$tgl_error = false;
                $daftar_tanggal = explode("-", $tgl);
                if (count($daftar_tanggal)!='3') {
                	$tgl_error = true;
                	$mana = 'tanggal';
                }else{
	                if (in_array($daftar_tanggal[0], array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'))==FALSE) {
	                	$tgl_error = true;
                		$mana = 'hari';
	                }

	                if (in_array($daftar_tanggal[1], array('01','02','03','04','05','06','07','08','09','10','11','12'))==FALSE) {
	                	$tgl_error = true;
                		$mana = 'bulan';
	                }

	                if (!isset($daftar_tanggal[2]) || $daftar_tanggal[2]!=date("Y") && $daftar_tanggal[2]!=(date("Y")+1)) {
	                	$tgl_error = true;
                		$mana = 'tahun';
	                }
                }
                

                if($tgl_error){
					$reply = $mana."-Maaf, format ".$mana." salah, gunakan: DD-MM-YYYY\nContoh: 01-02-2016 atau 31-12-2016";
					$this->sms_send($rows['SenderNumber'],$reply,$rows['ID']);
					continue;
                }else{
                	$valid_tgl = $tgl;
                }

				$api = $this->epus_pendaftaran($pbk->cl_pid, "REG ".$valid_tgl." ".$valid_poli, $valid_puskesmas);
				if(is_array($api) && intval($api['status_code']['code']) < 400){
					$reply = isset($api['content']['validation']) ? $api['content']['validation'] : "Maaf, ".$format;
				}else{
					$reply = $format;
				}

			}else{
				$reply = "Maaf, format SMS salah".$format;
			}

			$this->sms_send($rows['SenderNumber'],$reply,$rows['ID']);
			
			echo $reply;
		}
	}

	function epus_pendaftaran($cl_pid="", $sms="", $puskesmas){
		$config 	= $this->epus->get_config("daftar_kunjunganpid_epus");
		$url 		= $config['server'];

		$fields_string = array(
        	'client_id' 		=> $config['client_id'],
	        'kodepuskesmas' 	=> $puskesmas,
	        'cl_pid' 			=> $cl_pid,
	        'isi_sms' 	 		=> $sms,
	        'petugas' 	 		=> "puskesmas",
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
		return $res;
	}	
	
	function sms_broadcast($args = ""){
		echo "sms.broadcast ...\n";

		//sms_1x
		$this->db->where("status","aktif");
		$this->db->where("tgl_mulai <= ", date("Y-m-d"));
		$this->db->where("tgl_akhir >= ", date("Y-m-d"));
		$this->db->where("is_harian < ", date("H:i:s"));
		$this->db->where("is_loop", "tidak");
		$this->db->where("id_bc NOT IN (SELECT `id_bc` FROM `sms_bc_status` WHERE tgl='".date("Y-m-d")."')");
		$sms_1x = $this->db->get("sms_bc")->result();
		foreach ($sms_1x as $rows) {
			$this->db->where("id_sms_bc",$rows->id_bc);
			$tujuan = $this->db->get("sms_bc_tujuan")->result();
			foreach ($tujuan as $nmr) {
				$this->sms_send( "+62".$nmr->nomor, $rows->pesan);
			}

			$status = array();
			$status['id_bc'] = $rows->id_bc;
			$status['tgl'] = date("Y-m-d");
			$this->db->insert('sms_bc_status',$status);
		}


		//sms_harian
		$this->db->where("status","aktif");
		$this->db->where("tgl_mulai <= ", date("Y-m-d"));
		$this->db->where("tgl_akhir >= ", date("Y-m-d"));
		$this->db->where("is_harian < ", date("H:i:s"));
		$this->db->where("is_loop", "harian");
		$this->db->where("id_bc NOT IN (SELECT `id_bc` FROM `sms_bc_status` WHERE tgl='".date("Y-m-d")."')");
		$sms_harian = $this->db->get("sms_bc")->result();
		foreach ($sms_harian as $rows) {
			$this->db->where("id_sms_bc",$rows->id_bc);
			$tujuan = $this->db->get("sms_bc_tujuan")->result();
			foreach ($tujuan as $nmr) {
				$this->sms_send( "+62".$nmr->nomor, $rows->pesan);
			}

			$status = array();
			$status['id_bc'] = $rows->id_bc;
			$status['tgl'] = date("Y-m-d");
			$this->db->insert('sms_bc_status',$status);
		}


		//sms_mingguan
		$this->db->where("status","aktif");
		$this->db->where("tgl_mulai <= ", date("Y-m-d"));
		$this->db->where("tgl_akhir >= ", date("Y-m-d"));
		$this->db->where("is_harian < ", date("H:i:s"));
		$this->db->where("is_loop", "mingguan");
		$this->db->where("is_mingguan", date("w"));
		$this->db->where("id_bc NOT IN (SELECT `id_bc` FROM `sms_bc_status` WHERE tgl='".date("Y-m-d")."')");
		$sms_harian = $this->db->get("sms_bc")->result();
		foreach ($sms_harian as $rows) {
			$this->db->where("id_sms_bc",$rows->id_bc);
			$tujuan = $this->db->get("sms_bc_tujuan")->result();
			foreach ($tujuan as $nmr) {
				$this->sms_send( "+62".$nmr->nomor, $rows->pesan);
			}

			$status = array();
			$status['id_bc'] = $rows->id_bc;
			$status['tgl'] = date("Y-m-d");
			$this->db->insert('sms_bc_status',$status);
		}


		//sms_bulanan
		$this->db->where("status","aktif");
		$this->db->where("tgl_mulai <= ", date("Y-m-d"));
		$this->db->where("tgl_akhir >= ", date("Y-m-d"));
		$this->db->where("is_harian < ", date("H:i:s"));
		$this->db->where("is_loop", "bulanan");
		$this->db->where("is_bulanan", date("d"));
		$this->db->where("id_bc NOT IN (SELECT `id_bc` FROM `sms_bc_status` WHERE tgl='".date("Y-m-d")."')");
		$sms_harian = $this->db->get("sms_bc")->result();
		foreach ($sms_harian as $rows) {
			$this->db->where("id_sms_bc",$rows->id_bc);
			$tujuan = $this->db->get("sms_bc_tujuan")->result();
			foreach ($tujuan as $nmr) {
				$this->sms_send( "+62".$nmr->nomor, $rows->pesan);
			}

			$status = array();
			$status['id_bc'] = $rows->id_bc;
			$status['tgl'] = date("Y-m-d");
			$this->db->insert('sms_bc_status',$status);
		}
	}
}
