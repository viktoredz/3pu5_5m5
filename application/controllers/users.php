<?php

class Users extends CI_Controller {

	var $limit=10;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('users_model');
		$this->load->model('sendmail_model');
		$this->load->model('disbun_model');
		$this->load->helper('html');
        $this->load->library('email');
        $this->load->library('image_CRUD');
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
	}
	
	function baru(){
		$this->authentication->verify('user','show');
		$data['title_group']	="Dashboard";
		$data['title_form']		="Pendaftar Baru (menunggu approval)";

		$data['query'] = $this->users_model->get_data(0,9999999,array("status_aproved"=>"0")); 

		$data['content'] = $this->parser->parse("users/baru",$data,true);

		$this->template->show($data,"home");
	}

	function aktif(){
		$this->authentication->verify('user','show');
		$data['title_group']	="Dashboard";
		$data['title_form']		="Produsen Benih Aktif";

		$data['query'] = $this->users_model->get_data(0,9999999,array("status_aproved"=>"1")); 

		$data['content'] = $this->parser->parse("users/aktif",$data,true);

		$this->template->show($data,"home");
	}

	function trup(){
		$this->authentication->verify('user','show');
		$data['title_group']	="Dashboard";
		$data['title_form']		="Nomor Rekomendasi / TRUP";

		$data['query'] = $this->users_model->get_trup_list(); 

		$data['content'] = $this->parser->parse("users/trup",$data,true);

		$this->template->show($data,"home");
	}

	function trup_add($mode=""){
		$this->authentication->verify('user','show');

		$username = $this->session->userdata('username');

		$this->form_validation->set_rules('tgl_daftar', 'Tanggal Pendaftaran', 'trim|required');
		$this->form_validation->set_rules('jenis', 'Jenis TRUP', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('penanggungjawab', 'Penanggung Jawab', 'trim|required');
		$this->form_validation->set_rules('ktp', 'No KTP', 'trim|required');
		$this->form_validation->set_rules('pengalaman', 'Pengalaman jadi penangkar', 'trim|required');
		$this->form_validation->set_rules('modal_asal', 'Asal Modal Usaha', 'trim|required');
		$this->form_validation->set_rules('modal_nilai', 'Jumlah Modal Usaha', 'trim|required');
		$this->form_validation->set_rules('kerjasama', 'Kerjasama Kelompok', 'trim|required');
		$this->form_validation->set_rules('propinsi', 'Propinsi', 'trim|required');
		$this->form_validation->set_rules('kota', 'Kota', 'trim|required');
		$this->form_validation->set_rules('kecamatan', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('desa', 'Desa', 'trim|required');


		if($this->form_validation->run()== FALSE){
			$data = $this->disbun_model->get_profile($username); 
			$data['title_group']	="Dashboard";
			$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
			$data['nama_desa'] = $this->crud->get_desa_by_id($data['desa'])->nama_desa;
			$data['nama_kecamatan'] = $this->crud->get_kecamatan_by_id($data['kecamatan'])->nama_kecamatan;
			$data['nama_kota'] = $this->crud->get_kota_by_id($data['kota'])->nama_kota;
			
			
			if ($mode!="update")  {
				$propinsi = $data['propinsi'];
				$kota = $data['kota'];
				$kecamatan = $data['kecamatan'];
				$desa = $data['desa'];
			}
			
			if(!isset($tgl_daftar)){
              	$data['tgl_daftar']  = set_value('tgl_daftar');
            }
			if(!isset($jenis)){
              	$data['jenis']  = set_value('jenis');
            }
			if(!isset($nama)){
              	$data['nama']  = set_value('nama');
            }
			if(!isset($penanggungjawab)){
              	$data['penanggungjawab']  = set_value('penanggungjawab');
            }
			if(!isset($ktp)){
              	$data['ktp']  = set_value('ktp');
            }
			if(!isset($pengalaman)){
              	$data['pengalaman']  = set_value('pengalaman');
            }
			if(!isset($modal_asal)){
              	$data['modal_asal']  = set_value('modal_asal');
            }
            if(!isset($modal_nilai)){
              	$data['modal_nilai']  = set_value('modal_nilai');
            }
            if(!isset($kerjasama)){
              	$data['kerjasama']  = set_value('kerjasama');
            }
            if(!isset($kota)){
              	$data['kota']  = set_value('kota');
              	// $kota = $data['kota'];
            } else {
            	// $data['kota']  = set_value('kota');
            }
            if(!isset($kecamatan)){
            	// $kecamatan = $data['kecamatan'];
              	$data['kecamatan']  = set_value('kecamatan');
            } else {
            	// $data['kecamatan']  = set_value('kecamatan');
            }
            if(!isset($desa)){
            	// $desa = $data['desa'];
            	$data['desa']  = set_value('desa');
            } else {
              	// $data['desa']  = set_value('desa');
            }
            if(!isset($propinsi)){
            	// $propinsi = $data['propinsi'];
            	$data['propinsi']  = set_value('propinsi');
            } else {
              	// $data['propinsi']  = set_value('propinsi');
            }

            if ($data['tgl_daftar'] == "" || $data['tgl_daftar'] == null) {
            	$data['tgl_daftar'] = date('d/m/Y');
            }

			$data['provinsi_option']	= $this->crud->provinsi_option($data['propinsi']);
			// $data['kota_option']	= $this->crud->kota_option($data['propinsi'],$data['kota']);
			// $data['kecamatan_option']	= $this->crud->kecamatan_option($data['kota'],$data['kecamatan']);
			// $data['desa_option']	= $this->crud->desa_option($data['kecamatan'],$data['desa']);
			$pengalaman = "<option value=''>-</option>";
			for ($i=1; $i <= 50 ; $i++) { 
				$pengalaman .= '<option value="'.$i.'" ';
				if ($data['pengalaman'] == $i) {
					$pengalaman .= 'selected';
				}
				$pengalaman .= '>'.$i.'</option>';
			}
			$data['pengalaman_option'] = $pengalaman;
			$data['content'] = $this->parser->parse("users/trup_form",$data,true);
			$this->template->show($data,"home");
		}elseif($kode_trup=$this->users_model->trup_add()){
			$this->session->set_flashdata('alert_form', 'Penyimpanan data berhasil...');
			redirect(base_url()."users/trup_draft/".$kode_trup);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."users/trup_add");
		}

	}

	// function trup_draft($kode_trup="")
	// {
	// 	$this->authentication->verify('user','edit');
	// 	$username = $this->session->userdata('username');
	// 	$data = $this->disbun_model->get_profile($username); 
	// 	$trup = $this->users_model->get_trup_draft($kode_trup); 

	// 	foreach ($trup as $key => $value) {
	// 		$data[$key] = $value;
	// 	}

	// 	$data['title_group']	="Dashboard";
	// 	$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
	// 	$data['nama_desa'] = $this->crud->get_desa_by_id($data['desa'])->nama_desa;
	// 	$data['nama_kecamatan'] = $this->crud->get_kecamatan_by_id($data['kecamatan'])->nama_kecamatan;
	// 	$data['nama_kota'] = $this->crud->get_kota_by_id($data['kota'])->nama_kota;
	// 	$data['provinsi_option']	= $this->crud->provinsi_option($data['id_propinsi']);
	// 	$pengalaman = "<option value=''>-</option>";
	// 	for ($i=1; $i <= 50 ; $i++) { 
	// 		if ($i == $data['pengalaman']) {
	// 			$pengalaman .= '<option value="'.$i.'" selected>'.$i.'</option>';
	// 		} else {
	// 			$pengalaman .= '<option value="'.$i.'">'.$i.'</option>';
	// 		}
	// 	}
	// 	$data['pengalaman_option'] = $pengalaman;
	// 	$tmp = explode("-",$data['tgl_daftar']);
	// 	$data['tgl_daftar']	= $tmp[2]."/".$tmp[1]."/".$tmp[0];

	// 	$data['trup_rencana'] = $this->users_model->get_trup_rencana($kode_trup);
	// 	$data['trup_eksisting'] = $this->users_model->get_trup_eksisting($kode_trup);

	// 	$data['kode_trup'] = $kode_trup;
	// 	parse_str($_SERVER['QUERY_STRING'], $_GET);
	// 	$data['content'] = $this->parser->parse("users/trup_draft",$data,true);
	// 	$this->template->show($data,"home");
	// }

	function trup_draft($kode_trup="",$mode=""){
		$this->authentication->verify('user','edit');


		$this->form_validation->set_rules('tgl_daftar', 'Tanggal Pendaftaran', 'trim|required');
		$this->form_validation->set_rules('jenis', 'Jenis TRUP', 'trim|required');
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_rules('penanggungjawab', 'Penanggung Jawab', 'trim|required');
		$this->form_validation->set_rules('ktp', 'No KTP', 'trim|required');
		$this->form_validation->set_rules('pengalaman', 'Pengalaman jadi penangkar', 'trim|required');
		$this->form_validation->set_rules('modal_asal', 'Asal Modal Usaha', 'trim|required');
		$this->form_validation->set_rules('modal_nilai', 'Jumlah Modal Usaha', 'trim|required');
		$this->form_validation->set_rules('kerjasama', 'Kerjasama Kelompok', 'trim|required');
		$this->form_validation->set_rules('id_propinsi', 'Propinsi', 'trim|required');
		$this->form_validation->set_rules('id_kota', 'Kota', 'trim|required');
		$this->form_validation->set_rules('id_kecamatan', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('id_desa', 'Desa', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$username = $this->session->userdata('username');
			$data = $this->disbun_model->get_profile($username); 
			$data['title_group']	="Dashboard";
			$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
			$data['nama_desa'] = $this->crud->get_desa_by_id($data['desa'])->nama_desa;
			$data['nama_kecamatan'] = $this->crud->get_kecamatan_by_id($data['kecamatan'])->nama_kecamatan;
			$data['nama_kota'] = $this->crud->get_kota_by_id($data['kota'])->nama_kota;

			$trup = $this->users_model->get_trup_draft($kode_trup); 

			foreach ($trup as $key => $value) {
				$data[$key] = $value;
			}

			if ($mode!="update")  {
				$propinsi = $data['id_propinsi'];
				$kota = $data['id_kota'];
				$kecamatan = $data['id_kecamatan'];
				$desa = $data['id_desa'];
				$tgl_daftar = $data['tgl_daftar'];
				$jenis = $data['jenis'];
				$nama = $data['nama'];
				$penanggungjawab = $data['penanggungjawab'];
				$ktp = $data['ktp'];
				$pengalaman = $data['pengalaman'];
				$modal_asal = $data['modal_asal'];
				$modal_nilai = $data['modal_nilai'];
				$kerjasama = $data['kerjasama'];
			}

			if(!isset($tgl_daftar)){
              	$data['tgl_daftar']  = set_value('tgl_daftar');
            } else {
            	// $tgl_daftar = $data['tgl_daftar'];
            }
			if(!isset($jenis)){
              	$data['jenis']  = set_value('jenis');
            } else {
            	// $jenis = $data['jenis'];
            }
			if(!isset($nama)){
              	$data['nama']  = set_value('nama');
            } else {
            	// $nama = $data['nama'];
            }
			if(!isset($penanggungjawab)){
              	$data['penanggungjawab']  = set_value('penanggungjawab');
            } else {
            	// $penanggungjawab = $data['penanggungjawab'];
            }
			if(!isset($ktp)){
              	$data['ktp']  = set_value('ktp');
            } else {
            	// $ktp = $data['ktp'];
            }
			if(!isset($pengalaman)){
              	$data['pengalaman']  = set_value('pengalaman');
            } else {
            	// $pengalaman = $data['pengalaman'];
            }
			if(!isset($modal_asal)){
              	$data['modal_asal']  = set_value('modal_asal');
            } else {
            	// $modal_asal = $data['modal_asal'];
            }
            if(!isset($modal_nilai)){
              	$data['modal_nilai']  = set_value('modal_nilai');
            } else {
            	// $modal_nilai = $data['modal_nilai'];
            }
            if(!isset($kerjasama)){
              	$data['kerjasama']  = set_value('kerjasama');
            } else {
            	// $kerjasama = $data['kerjasama'];
            }
            if(!isset($kota)){
              	$data['id_kota']  = set_value('id_kota');
            } else {
            	// $kota = $data['kota'];
            }
            if(!isset($kecamatan)){
              	$data['id_kecamatan']  = set_value('id_kecamatan');
            } else {
            	// $kecamatan = $data['kecamatan'];
            }
            if(!isset($desa)){
              	$data['id_desa']  = set_value('id_desa');
            } else {
            	// $data['desa']  = set_value('desa');
            	// $propinsi = $data['propinsi'];
            }
            if(!isset($propinsi)){
              	$propinsi = $data['id_propinsi'];
            } else {
            	// $propinsi = $data['propinsi'];
            }

			
			$data['provinsi_option']	= $this->crud->provinsi_option($data['id_propinsi']);
			$pengalaman = "<option value=''>-</option>";
			for ($i=1; $i <= 50 ; $i++) { 
				$pengalaman .= '<option value="'.$i.'" ';
				if ($data['pengalaman'] == $i) {
					$pengalaman .= 'selected';
				}
				$pengalaman .= '>'.$i.'</option>';
			}
			$data['pengalaman_option'] = $pengalaman;
			
			if ($mode!="update") {
				$tmp = explode("-",$data['tgl_daftar']);
				$data['tgl_daftar']	= $tmp[2]."/".$tmp[1]."/".$tmp[0];
			}
			

			$data['trup_rencana'] = $this->users_model->get_trup_rencana($kode_trup);
			$data['trup_eksisting'] = $this->users_model->get_trup_eksisting($kode_trup);

			$data['kode_trup'] = $kode_trup;
			parse_str($_SERVER['QUERY_STRING'], $_GET);
			$data['content'] = $this->parser->parse("users/trup_draft",$data,true);
			$this->template->show($data,"home");

			// $this->session->set_flashdata('alert_form', validation_errors());
			// redirect(base_url()."users/trup_draft/".$kode_trup);

		}elseif($this->users_model->trup_update_draft($kode_trup)){
			$this->session->set_flashdata('alert_form', 'Penyimpanan data berhasil...');
			redirect(base_url()."users/trup_draft/".$kode_trup);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."users/trup_draft/".$kode_trup);
		}

	}

	function trup_add_rencana($kode_trup=""){
		$this->authentication->verify('user','show');
		$this->form_validation->set_rules('kode_trup', 'Kode Trup', 'trim|required');
		$this->form_validation->set_rules('komoditi', 'Komoditi', 'trim|required');
		$this->form_validation->set_rules('varietas', 'Varietas', 'trim|required|callback_check_varietas_komoditi_rencana');
		$this->form_validation->set_rules('satuan', 'Satuan', 'trim|required');
		$this->form_validation->set_rules('jml', 'Jumlah', 'trim|required');
		$this->form_validation->set_rules('asal', 'Asal Benih', 'trim|required');
		$this->form_validation->set_rules('umur', 'Umur Benih', 'trim|required');
		$this->form_validation->set_rules('penyaluran', 'Rencana Penyaluran', 'trim|required');

		if($this->form_validation->run()== FALSE){
			if(!isset($komoditi)){
              	$data['komoditi']  = set_value('komoditi');
            }
			if(!isset($varietas)){
              	$data['varietas']  = set_value('varietas');
            }
			if(!isset($satuan)){
              	$data['satuan']  = set_value('satuan');
            }
			if(!isset($jml)){
              	$data['jml']  = set_value('jml');
            }
			if(!isset($asal)){
              	$data['asal']  = set_value('asal');
            }
			if(!isset($umur)){
              	$data['umur']  = set_value('umur');
            }
			if(!isset($penyaluran)){
              	$data['penyaluran']  = set_value('penyaluran');
            }
            // if(isset($kode_trup)){
            //   	$this->session->set_flashdata('alert_form',validation_errors());
            // }


			$trup = $this->users_model->get_trup_draft($kode_trup);
			foreach ($trup as $key => $value) {
				$data[$key] = $value;
			}
			$data['title_group']	="Dashboard";
			$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
	 		
	 		$tmp = explode("-",$data['tgl_daftar']);
			$data['tgl_daftar']	= $tmp[2]."/".$tmp[1]."/".$tmp[0];
			
			$data['jenistanaman_option'] = $this->crud->jenistanaman_option($data['komoditi']);
			$data['satuan_option'] = $this->crud->satuan_option($data['satuan']);
			// $data['varietas_option']	= $this->crud->varietas_option_nonselected($this->users_model->get_varietas_rencana_selected($kode_trup));
			$data['varietas_option']	= $this->crud->varietas_option($data['varietas']);
			$data['kode_trup'] = $kode_trup;
			$data['content'] = $this->parser->parse("users/trup_add_rencana",$data,true);

			$this->template->show($data,"home");
		}elseif($kode_trup=$this->users_model->trup_add_rencana()){
			$this->session->set_flashdata('alert_form', 'Penyimpanan data berhasil...');
			redirect(base_url()."users/trup_draft/".$kode_trup."?tab=tab_2");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."users/trup_add_rencana/".$kode_trup);
		}
	}
	
	function trup_add_eksisting($kode_trup=""){
		$this->authentication->verify('user','show');
		$this->form_validation->set_rules('kode_trup', 'Kode Trup', 'trim|required');
		$this->form_validation->set_rules('komoditi', 'Komoditi', 'trim|required');
		$this->form_validation->set_rules('varietas', 'Varietas', 'trim|required|callback_check_varietas_komoditi_eksisting');
		$this->form_validation->set_rules('satuan', 'Satuan', 'trim|required');
		$this->form_validation->set_rules('jml', 'Jumlah', 'trim|required');
		$this->form_validation->set_rules('asal', 'Asal Benih', 'trim|required');
		$this->form_validation->set_rules('umur', 'Umur Benih', 'trim|required');
		$this->form_validation->set_rules('penyaluran', 'Rencana Penyaluran', 'trim|required');

		if($this->form_validation->run()== FALSE){
			if(!isset($komoditi)){
              	$data['komoditi']  = set_value('komoditi');
            }
			if(!isset($varietas)){
              	$data['varietas']  = set_value('varietas');
            }
			if(!isset($satuan)){
              	$data['satuan']  = set_value('satuan');
            }
			if(!isset($jml)){
              	$data['jml']  = set_value('jml');
            }
			if(!isset($asal)){
              	$data['asal']  = set_value('asal');
            }
			if(!isset($umur)){
              	$data['umur']  = set_value('umur');
            }
			if(!isset($penyaluran)){
              	$data['penyaluran']  = set_value('penyaluran');
            }


			$trup = $this->users_model->get_trup_draft($kode_trup);
			foreach ($trup as $key => $value) {
				$data[$key] = $value;
			}
			$data['title_group']	="Dashboard";
			$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
	 		
	 		$tmp = explode("-",$data['tgl_daftar']);
			$data['tgl_daftar']	= $tmp[2]."/".$tmp[1]."/".$tmp[0];
			
			$data['jenistanaman_option'] = $this->crud->jenistanaman_option($data['komoditi']);
			$data['satuan_option'] = $this->crud->satuan_option($data['satuan']);
			$data['varietas_option']	= $this->crud->varietas_option($data['varietas']);

			// $data['varietas_option']	= $this->crud->varietas_option_nonselected($this->users_model->get_varietas_eksisting_selected($kode_trup));
			
			$data['kode_trup'] = $kode_trup;
			$data['content'] = $this->parser->parse("users/trup_add_eksisting",$data,true);

			$this->template->show($data,"home");
		}elseif($kode_trup=$this->users_model->trup_add_eksisting()){
			$this->session->set_flashdata('alert_form', 'Penyimpanan data berhasil...');
			redirect(base_url()."users/trup_draft/".$kode_trup."?tab=tab_2");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."users/trup_add_eksisting/".$kode_trup);
		}

	}
	
	function trup_detail($kode_trup="")
	{
		$this->authentication->verify('user','edit');

		$data = $this->users_model->get_trup($kode_trup); 

		$data['title_group']	="Dashboard";
		$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
		$data['nama_desa'] = $this->crud->get_desa_by_id($data['desa'])->nama_desa;
		$data['nama_kecamatan'] = $this->crud->get_kecamatan_by_id($data['kecamatan'])->nama_kecamatan;
		$data['nama_kota'] = $this->crud->get_kota_by_id($data['kota'])->nama_kota;
		$data['desa'] = $this->crud->get_desa_by_id($data['id_desa'])->nama_desa;
		$data['kecamatan'] = $this->crud->get_kecamatan_by_id($data['id_kecamatan'])->nama_kecamatan;
		$data['kota'] = $this->crud->get_kota_by_id($data['id_kota'])->nama_kota;
		if($data['statustrup']==1 && $data['tgl_akhir']>date('Y-m-d')){
			$data['statusTRUP'] = "Berlaku";
		}else{
			$data['statusTRUP'] = "Tidak Aktif";
		}

		if($data['statustrup']==1){
			$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	
			$tgl_tmp = explode("-", $data['tgl_pemeriksaan']);
			$data['tgl_pemeriksaan'] = $tgl_tmp[2]." ".$BulanIndo[(int)$tgl_tmp[1]-1]." ".$tgl_tmp[0];
	
			$tgl_tmp = explode("-", $data['tgl_aktif']);
			$data['tgl_aktif'] = $tgl_tmp[2]." ".$BulanIndo[(int)$tgl_tmp[1]-1]." ".$tgl_tmp[0];
	
			$tgl_tmp = explode("-", $data['tgl_akhir']);
			$data['tgl_akhir'] = $tgl_tmp[2]." ".$BulanIndo[(int)$tgl_tmp[1]-1]." ".$tgl_tmp[0];
		}

		$data['trup_rencana'] = $this->users_model->get_trup_rencana($kode_trup);
		$data['trup_eksisting'] = $this->users_model->get_trup_eksisting($kode_trup);

		$data['content'] = $this->parser->parse("users/trup_detail",$data,true);
		$this->template->show($data,"home");
	}


	function komoditi_rencana_update($kode_trup,$kode_varietas,$kode_komoditi) {
		// $this->form_validation->set_rules('kode_trup', 'Kode Trup', 'trim|required');
		// $this->form_validation->set_rules('komoditi', 'Komoditi', 'trim|required');
		// $this->form_validation->set_rules('varietas', 'Varietas', 'trim|required');
		$this->form_validation->set_rules('satuan', 'Satuan', 'trim|required');
		$this->form_validation->set_rules('jml', 'Jumlah', 'trim|required');
		$this->form_validation->set_rules('asal', 'Asal Benih', 'trim|required');
		$this->form_validation->set_rules('umur', 'Umur Benih', 'trim|required');
		$this->form_validation->set_rules('penyaluran', 'Rencana Penyaluran', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}elseif($this->users_model->trup_update_rencana($kode_trup,$kode_varietas,$kode_komoditi)){
			echo "Data berhasil disimpan";
		}else{
			echo "Penyimpanan data gagal dilakukan";
		}
	}

	function trup_detail_rencana($kode_trup,$kode_varietas,$kode_komoditi) {
		$data['kode_trup'] = $kode_trup;
		$data['kode_varietas'] = $kode_varietas;
		$data['kode_komoditi'] = $kode_komoditi;

		$trup = $this->users_model->get_trup_draft($kode_trup);
			foreach ($trup as $key => $value) {
				$data[$key] = $value;
			}
			$trup_rencana = $this->users_model->get_trup_rencana_detail($kode_trup,$kode_varietas,$kode_komoditi);
			foreach ($trup_rencana as $key => $value) {
				$data[$key] = $value;
			}
			$data['title_group']	="Dashboard";
			$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
	 		
	 		$tmp = explode("-",$data['tgl_daftar']);
			$data['tgl_daftar']	= $tmp[2]."/".$tmp[1]."/".$tmp[0];
			
			$data['jenistanaman_option'] = $this->crud->jenistanaman_option($data['kode_komoditi']);
			$data['satuan_option'] = $this->crud->satuan_option($data['kode_satuan']);
			$data['varietas_option']	= $this->crud->varietas_option($data['kode_varietas']);
			

			$data['content'] = $this->parser->parse("users/trup_detail_rencana",$data,true);

			$this->template->show($data,"home");
	}

	function komoditi_eksisting_update($kode_trup,$kode_varietas,$kode_komoditi) {
		$this->form_validation->set_rules('satuan', 'Satuan', 'trim|required');
		$this->form_validation->set_rules('jml', 'Jumlah', 'trim|required');
		$this->form_validation->set_rules('asal', 'Asal Benih', 'trim|required');
		$this->form_validation->set_rules('umur', 'Umur Benih', 'trim|required');
		$this->form_validation->set_rules('penyaluran', 'Rencana Penyaluran', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}elseif($this->users_model->trup_update_eksisting($kode_trup,$kode_varietas,$kode_komoditi)){
			echo "Data berhasil disimpan";
		}else{
			echo "Penyimpanan data gagal dilakukan";
		}
	}

	function trup_detail_eksisting($kode_trup,$kode_varietas,$kode_komoditi) {
		$data['kode_trup'] = $kode_trup;
		$data['kode_varietas'] = $kode_varietas;
		$data['kode_komoditi'] = $kode_komoditi;

		$trup = $this->users_model->get_trup_draft($kode_trup);
			foreach ($trup as $key => $value) {
				$data[$key] = $value;
			}
			$trup_eksisting = $this->users_model->get_trup_eksisting_detail($kode_trup,$kode_varietas,$kode_komoditi);
			foreach ($trup_eksisting as $key => $value) {
				$data[$key] = $value;
			}
			$data['title_group']	="Dashboard";
			$data['title_form']		="Permohonan Nomor Rekomendasi TRUP";
	 		
	 		$tmp = explode("-",$data['tgl_daftar']);
			$data['tgl_daftar']	= $tmp[2]."/".$tmp[1]."/".$tmp[0];
			
			$data['jenistanaman_option'] = $this->crud->jenistanaman_option($data['kode_komoditi']);
			$data['satuan_option'] = $this->crud->satuan_option($data['kode_satuan']);
			$data['varietas_option']	= $this->crud->varietas_option($data['kode_varietas']);
			

			$data['content'] = $this->parser->parse("users/trup_detail_eksisting",$data,true);

			$this->template->show($data,"home");
	}

	function trup_delete(){
		$this->users_model->trup_delete();
		$this->session->set_flashdata('alert', 'Data berhasil dihapus.');
		redirect(base_url()."users/trup");
	}

	function trup_rencana_delete($kode_trup){
		$this->users_model->trup_rencana_delete();
		$this->session->set_flashdata('alert', 'Data berhasil dihapus.');
		
		redirect(base_url()."users/trup_draft/".$kode_trup."?tab=tab_2");
	}

	function trup_eksisting_delete($kode_trup){
		$this->users_model->trup_eksisting_delete();
		$this->session->set_flashdata('alert', 'Data berhasil dihapus.');

		redirect(base_url()."users/trup_draft/".$kode_trup."?tab=tab_2");	}

	function kirim_trup($id=0) {
		$this->users_model->kirim_trup($id);
		redirect(base_url()."users/trup");
	}

	function check_varietas_komoditi_rencana(){
			$kode_trup = $this->input->post('kode_trup');
			$kode_varietas = $this->input->post('varietas');
			$kode_komoditi = $this->input->post('komoditi');
			$check = $this->users_model->check_varietas_komoditi_rencana($kode_trup,$kode_varietas,$kode_komoditi);
			
			if(!$check){
				$this->form_validation->set_message('check_varietas_komoditi_rencana', 'Varietas dengan Komoditi yang dipilih tidak bisa digunakan atau tidak boleh sama dengan data sebelumnya.');
				return FALSE;
			}else{
				return TRUE;
			}
		
	}

	function check_varietas_komoditi_eksisting(){
			$kode_trup = $this->input->post('kode_trup');
			$kode_varietas = $this->input->post('varietas');
			$kode_komoditi = $this->input->post('komoditi');
			$check = $this->users_model->check_varietas_komoditi_eksisting($kode_trup,$kode_varietas,$kode_komoditi);
			
			if(!$check){
				$this->form_validation->set_message('check_varietas_komoditi_eksisting', 'Varietas dengan Komoditi yang dipilih tidak bisa digunakan atau tidak boleh sama dengan data sebelumnya.');
				return FALSE;
			}else{
				return TRUE;
			}
		
	}

	function approval($username="")
	{
		$this->authentication->verify('user','edit');

		$data = $this->users_model->get_data_row($username); 

		$data['provinsi_option']	= $this->crud->provinsi_option($data['propinsi']);
		$data['title_group']	="Dashboard";
		$data['title_form']		="Pendaftar Baru (detail profil)";
		$user['username']		= $username;

		$data['content'] = $this->parser->parse("users/approval",$data,true);
		$this->template->show($data,"home");
	}

	function profile($username="")
	{
		$this->authentication->verify('user','edit');

		$data = $this->users_model->get_data_row($username); 

		$data['provinsi_option']	= $this->crud->provinsi_option($data['propinsi']);
		$data['title_group']	="Dashboard";
		$data['title_form']		="Pendaftar Baru (detail profil)";
		$user['username']		= $username;

		$image_crud = new image_CRUD();
	
		$image_crud->unset_upload();
		$image_crud->unset_delete();

		$image_crud->set_primary_key_field('id');
		$image_crud->set_url_field('filename');
		$image_crud->set_table('app_users_gallery')
			->set_relation_field('username')
			->set_relation_value($username)
			->set_ordering_field('priority')
			->set_image_path('public/gallery')
			->set_draganddrop(false);
			
		$output = $image_crud->render();
		// print_r($output);
		$data['output'] =$output->output;



		$data['content'] = $this->parser->parse("users/profile",$data,true);
		$this->template->show($data,"home");
	}

	function detail_pelanggan($id=0)
	{
		$this->authentication->verify('user','edit');

		$data = $this->users_model->get_data_row($id); 
		$data['balai_option']	= $this->crud->get_balai($data['kode_balai'],"","disabled style='background:white;border:1px solid #CCCCCC'");
		$data['provinsi_kantor']= $this->crud->get_propinsi_span($data['kantor_kode_provinsi']);
		$data['kota_kantor']	= $this->crud->get_kota_span($data['kantor_kode_provinsi'],$data['kantor_kode_kota']);
		$data['provinsi_pabrik']= $this->crud->get_propinsi_span($data['pabrik_kode_provinsi']);
		$data['kota_pabrik']	= $this->crud->get_kota_span($data['pabrik_kode_provinsi'],$data['pabrik_kode_kota']);
		$data['title_form']	= "Data Pelanggan &raquo;";
		$user['id']			    = $id;
		$data['form_reject']	= $this->parser->parse("users/reject_detail",$user,true);
		echo $this->parser->parse("users/detail_pelanggan",$data,true);
	}
	
	function data_reject($id){

		die(json_encode($this->users_model->json_data_reject($id)));
		
	}

	function detail_pengguna($id=0)
	{
		$this->authentication->verify('user','edit');

		$data = $this->users_model->get_data_row($id); 
		$data['balai_option']	= $this->crud->get_balai($data['kode_balai'],"","disabled style='background:white;border:1px solid #CCCCCC'");
		$data['provinsi_kantor']= $this->crud->get_propinsi_span($data['kantor_kode_provinsi']);
		$data['kota_kantor']	= $this->crud->get_kota_span($data['kantor_kode_provinsi'],$data['kantor_kode_kota']);
		$data['provinsi_pabrik']= $this->crud->get_propinsi_span($data['pabrik_kode_provinsi']);
		$data['kota_pabrik']	= $this->crud->get_kota_span($data['pabrik_kode_provinsi'],$data['pabrik_kode_kota']);
		$data['title_form']	= "Data Pelanggan &raquo;";
		
		echo $this->parser->parse("users/detail_pengguna",$data,true);
	}

	function detail_update($id_log=0)
	{
		$this->authentication->verify('user','edit');
		$data = $this->users_model->get_edit_row($id_log); 		
		$data['title_form']	= "Data Update &raquo;";

		echo $this->parser->parse("users/detail_update",$data,true);
	}
	
	
	
	
	function dodownload($id=0){
		

		$data = $this->users_model->get_data_row($id); 	
		$path = './public/files/'.$data['id']."/".$data['file_bukti'];

		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Transfer-Encoding: binary");
		header("Content-disposition: attachment; filename=" . $data['file_bukti']);
		header("Content-type: application/octet-stream");
		readfile($path);
	}

	function edit($id_balai=0)
	{
		$this->authentication->verify('user','add');

		$data = $this->users_model->get_data_row($id_balai); 
		$data['title_form']="Balai &raquo; Ubah";
		$data['action']="edit";

		echo $this->parser->parse("users/form",$data,true);
	}

	function doedit($id_balai=0)
	{
		$this->authentication->verify('user','edit');

		$this->form_validation->set_rules('nama_balai', 'Nama Balai', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat Balai', 'trim|required');

		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}else{
			$this->users_model->update_entry($id_balai);
			echo "1";
		}
	}
	
	function doapprove($id=0,$status=0)
	{
		$this->authentication->verify('user','edit');

		if($this->users_model->doapprove($id,$status)){
			$user		= $this->users_model->get_data_row($id);
			$message	= "Terimakasih ".$user['nama']."<br><br>";
			
			if($status==1){
			 	//$aktifasi	= $this->users_model->create_aktifasi($id,$user['id'],$user['email']);
			 	$message	.= "Permintaan pendaftaran anda telah kami terima dan account anda telah aktif.<br><br>";
			}else{
			 	$message	.= "Permintaan pendaftaran anda telah kami terima dan account anda tidak dapat diaktifkan.<br><br>";
			}

			$sending = $this->sendmail_model->dosendmail($user['email'],"Registrasi Anggota ".($status==1? "Diterima":"Ditolak"),$message);
			//echo $sending;
			echo "1";
		}else{
			echo "0";	
		}
	}
	
	function calon_reject($id=0)
	{
		$this->authentication->verify('user','edit');
 		$data = $this->users_model->get_data_row($id); 		
		$data['title_form']	= "Data Update &raquo;";
		$data['oke']="dddddddd";		
		echo $this->parser->parse("users/update_reject", $data,true);
	}
	
	function doapprove2($id=0, $status=0)
	{
		
		$this->form_validation->set_rules('nip', 'NIP', 'trim|required');
		$this->form_validation->set_rules('catatan', 'Catatan', 'trim|required');
		if($this->form_validation->run()== FALSE){
			echo "ERROR_".validation_errors();
		}else{
		
			if($this->users_model->doapprove($id,$status)){
				$user		= $this->users_model->get_data_row($id);
				$message	= "Terimakasih ".$user['nama']."<br><br>";
				if($status==9){
					$aktifasi	= $this->users_model->create_reject($id);
					$message	.= ":<br>".base_url()."aktifasi/".$aktifasi."/".$user['email'];
			}else{
				$message	.= "Permintaan pendaftaran anda telah kami terima dan account anda tidak dapat diaktifkan.<br><br>";
			}
				//$sending = $this->sendmail_model->dosendmail($user['email'],"Registrasi Pelanggan ".($status==1? "Diterima":"Ditolak"),$message);
				//echo $sending;
				echo "1";
			}else{
				echo "0";	
			}
		}
	}
	
	
	function aktifasi($id=0,$email="")
	{
		if($userid = $this->users_model->doaktifasi($id,$email)){
			$user		= $this->users_model->get_data_row($userid);
			$message	= "Terimakasih ".$user['nama']."<br><br>";
 			$message	.= "Akun anda telah diaktifkan.<br><br>";

			//$sending = $this->sendmail_model->dosendmail($user['email'],"Registrasi Pelanggan Berhasil",$message);
		}else{
			$message	= "Akun anda tidak dapat diaktifkan, atau link yang anda buka tidak benar.<br><br>";
		}


		$data['title'] = "Registrasi Pelanggan";
		$data['message'] = $message;
		$data['content'] = $this->parser->parse("users/done",$data,true);

		$this->template->show($data,'home_guest',1);
	}
	
}
