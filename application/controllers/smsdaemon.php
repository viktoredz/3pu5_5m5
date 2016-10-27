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
			
			for($i=1;$i<5;$i++){
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
	
	function sms_send($nomor = "", $pesan=""){
		$data = array();
		$time = date("Y-m-d H:i:s");
		$data['InsertIntoDB'] 		= $time;
		$data['SendingDateTime'] 	= $time;
		$data['SendingTimeOut'] 	= $time;
		$data['DestinationNumber'] 	= $nomor;
		$data['TextDecoded'] 		= $pesan;

		return $this->db->insert('outbox',$data);
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

		$data['DestinationNumber'] = $nomor;
		$data['TextDecoded'] = $pesan;

		$this->db->insert('outbox',$data);
	}

	function sms_reply($args = ""){
		echo "\nsms.sms_reply ...\n";

		$operator = "'*123#','*111#','V-Tri','+3'";
		//$operator = "'*123#'";

		//jika sms blm di proses, bukan operator, kata pertama menu 
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.")");
		$this->db->where("SUBSTRING_INDEX(TextDecoded,' ',1) NOT IN (SELECT `code` FROM `sms_info_menu`)");
		$this->db->where("SUBSTRING_INDEX(TextDecoded,' ',1) NOT IN (SELECT `nama` FROM `sms_tipe` WHERE jenis='terima')");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) NOT IN ("BYR","BPJS","Byr","Bpjs","byr","bpjs")');
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

		$operator = "'*123#','*111#','V-Tri','+3'";
		//$operator = "'*123#'";

		//jika sms blm di proses, bukan operator, kata pertama menu 
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.")");
		$this->db->where("SUBSTRING_INDEX(TextDecoded,' ',1) IN (SELECT `code` FROM `sms_info_menu`)");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) NOT IN ("BYR","BPJS","Byr","Bpjs","byr","bpjs")');
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

		$operator = "'*123#','*111#','V-Tri','+3','3'";

		//jika sms blm di proses, bukan operator, kata pertama opini, 
		$this->db->select('ID, SUBSTRING_INDEX(`TextDecoded`," ",1) as `Kategori`,`SenderNumber`,`TextDecoded`,`sms_tipe`.`id_tipe`',false);
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.")");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) IN (SELECT `nama` FROM `sms_tipe` WHERE jenis="terima")');
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) NOT IN ("BYR","BPJS","Byr","Bpjs","byr","bpjs")');
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

		$operator = "'*123#','*111#','V-Tri','+3','3'";
		$format   = "\nKetik: BYR<spasi>NIK<spasi>KD POLI<spasi>KD PUSKESMAS<spasi>DD-MM-YYYY\natau Ketik:BPJS<spasi>NO BPJS<spasi>KD POLI<spasi>KD PUSKESMAS<spasi>DD-MM-YYYY";

		//jika sms blm di proses, bukan operator, BYR/BPJS daftar 
		$this->db->select('ID, SUBSTRING_INDEX(`TextDecoded`," ",1) as `keyword`,`SenderNumber`,`TextDecoded`',false);
		$this->db->where("Processed","false");
		$this->db->where("REPLACE(SenderNumber,'+62','') NOT IN (".$operator.")");
		$this->db->where('SUBSTRING_INDEX(`TextDecoded`," ",1) IN ("BYR","BPJS","Byr","Bpjs","byr","bpjs")');
		$inbox = $this->db->get("inbox")->result_array();
		foreach ($inbox as $rows) {
			$keyword = strtoupper($rows['keyword']);
            $text   = explode(" ",$rows['TextDecoded']);
            if(count($text)==5){
				if($keyword == "BYR"){
		            $nik 	= $text[1];
					$this->db->where("nik",$nik);
					$pbk = $this->db->get("sms_pbk")->row();
					if(!empty($pbk->cl_pid)){
						echo "\nBYR ".$pbk->cl_pid.": ".$rows['TextDecoded'];
						$reply = $this->epus_pendaftaran($pbk->cl_pid, $rows['TextDecoded'], $args);
						if(isset($reply) && $reply['status_code']['code']=="200"){
							$reply = isset($reply['content'][0]) ? $reply['content'][0] : "Maaf, pendaftaran tidak berhasil".$format;
						}else{
							$reply = isset($reply['content']['validation']) ? $reply['content']['validation'] : "Maaf, pendaftaran tidak berhasil".$format;
						}
					}else{
						$reply = "Maaf, Nomor HP anda tidak terdaftar".$format;
					}
				}else{
		            $bpjs 	= $text[1];
					$this->db->where("bpjs",$bpjs);
					$pbk = $this->db->get("sms_pbk")->row();
					if(!empty($pbk->cl_pid)){
						if(isset($text[3])){
							$sms = "BYR ".$text[2]." ".$text[3];
							echo "\nBPJS ".$pbk->cl_pid.": ".$sms;
							$reply = $this->epus_pendaftaran($pbk->cl_pid, $sms, $args);
							if(isset($reply) && $reply['status_code']['code']=="200"){
								$reply = isset($reply['content'][0]) ? $reply['content'][0] : "Maaf, pendaftaran gagal".$format;
							}else{
								$reply = isset($reply['content']['validation']) ? $reply['content']['validation'] : "Maaf, pendaftaran gagal".$format;
							}
						}else{
							$reply = "Maaf, format SMS salah".$format;
						}
					}else{
						$reply = "Maaf, No.BPJS tidak terdaftar".$format;
					}
				}
			}else{
				$reply = "Maaf, format SMS salah\nKetik: BYR<spasi>NIK<spasi>KD.POLI<spasi>KD.PUSKESMAS<spasi>DD-MM-YYYY<spasi>\natau Ketik:BPJS<spasi>NO.BPJS<spasi>KD.POLI<spasi>KD.PUSKESMAS<spasi>DD-MM-YYYY";
			}

			if($send = $this->sms_send($rows['SenderNumber'],$reply)){
				$this->db->where('ID',$rows['ID']);
				$this->db->update('inbox',array('Processed'=>"true"));
			}
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
