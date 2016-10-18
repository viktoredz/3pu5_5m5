<?php
class Pbk extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
        require_once(APPPATH.'third_party/excel/oleread.inc');
        require_once(APPPATH.'third_party/excel/reader.php');

		$this->load->helper('html');
		$this->load->model('sms/pbk_model');
	}
	
	function index(){
		$this->authentication->verify('sms','show');
		$data['title_group'] = "Buku Telepon";
		$data['title_form'] = "Nomor Terdaftar";

		$this->session->unset_userdata('filter_id_sms_grup');

		$data['grupoption'] 	= $this->pbk_model->get_grupoption();
		$data['content'] = $this->parser->parse("sms/pbk/show",$data,true);

		$this->template->show($data,"home");
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
					$this->db->like("sms_pbk.created_on",$value);
				}
				elseif($field == 'nama') {
					$this->db->like("sms_pbk.nama",$value);
				}
				elseif($field == 'nama_grup') {
					$this->db->like("sms_grup.nama",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_id_sms_grup') != '') {
			$this->db->where('sms_pbk.id_sms_grup',$this->session->userdata('filter_id_sms_grup'));
		}
		$rows_all = $this->pbk_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'created_on') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("sms_pbk.created_on",$value);
				}
				elseif($field == 'nama') {
					$this->db->like("sms_pbk.nama",$value);
				}
				elseif($field == 'nama_grup') {
					$this->db->like("sms_grup.nama",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_id_sms_grup') != '') {
			$this->db->where('sms_pbk.id_sms_grup',$this->session->userdata('filter_id_sms_grup'));
		}
		$rows = $this->pbk_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		foreach($rows as $act) {
			$data[] = array(
				'no'		=> $no++,
				'id'		=> $act->nomor,
				'nomor'		=> '+62 - '.$act->nomor,
				'nama' 		=> $act->nama,
				'nama_grup'	=> $act->nama_grup,
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

	function filter(){
		if($_POST) {
			if($this->input->post('id_sms_grup') != '') {
				$this->session->set_userdata('filter_id_sms_grup',$this->input->post('id_sms_grup'));
			}else{
				$this->session->unset_userdata('filter_id_sms_grup');
			}
		}
	}

	function add(){
		$this->authentication->verify('sms','add');

        $this->form_validation->set_rules('nomor', 'Nomor', 'trim|required|callback_cekNomor');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('id_sms_grup', 'Grup', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "Buku Telepon";
			$data['title_form']="Tambah Nomor Telepon";
			$data['action']="add";
			$data['nomor']="";

			$data['grupoption'] 	= $this->pbk_model->get_grupoption();
		
			$data['content'] = $this->parser->parse("sms/pbk/form",$data,true);
		}elseif($id = $this->pbk_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'sms/pbk/');
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/pbk/add");
		}

		$this->template->show($data,"home");
	}

	function edit($nomor=""){
		$this->authentication->verify('sms','edit');

        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('id_sms_grup', 'Grup', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data 	= $this->pbk_model->get_data_row($nomor); 

			$data['title_group'] 	= "Buku Telepon";
			$data['title_form']		= "Ubah Nomor Telepon";
			$data['action']			= "edit";
			$data['nomor']			= $nomor;

			$data['grupoption'] 	= $this->pbk_model->get_grupoption();

			$data['content'] 	= $this->parser->parse("sms/pbk/form",$data,true);
		}elseif($this->pbk_model->update_entry($nomor)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."sms/pbk/edit/".$nomor);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."sms/pbk/edit/".$nomor);
		}

		$this->template->show($data,"home");
	}

	function dodel($kode=0,$code_cl_phc=""){
		$this->authentication->verify('sms','del');

		if($this->pbk_model->delete_entry($kode,$code_cl_phc)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}


	function cekNomor(){
		$nomor = $this->input->post('nomor');
		$this->db->where('nomor',$nomor);
		$pbk = $this->db->get('sms_pbk')->row();
		if(!empty($pbk)){
			$this->form_validation->set_message('cekNomor', 'Nomor '.$nomor.' sudah terdaftar.');
			return FALSE;
		}else{
			return TRUE;
		}
	}

	function export(){
		$this->authentication->verify('sms','show');
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'created_on') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->like("sms_pbk.created_on",$value);
				}
				elseif($field == 'nama') {
					$this->db->like("sms_pbk.nama",$value);
				}
				elseif($field == 'nama_grup') {
					$this->db->like("sms_grup.nama",$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_id_sms_grup') != '') {
			$this->db->where('sms_pbk.id_sms_grup',$this->session->userdata('filter_id_sms_grup'));
		}
		$rows = $this->pbk_model->get_data();

		$data = array();
		$no=1;

		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'		=> $no++,
				'id'		=> $act->nomor,
				'nomor'		=> '+62 - '.$act->nomor,
				'nama' 		=> $act->nama,
				'nama_grup'	=> $act->nama_grup,
				'created_on'=> $act->created_on
			);
		}


		$dir = getcwd().'/';
		$template = $dir.'public/files/template/sms/pbk.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		$TBS->MergeBlock('a', $data_tabel);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_pbk_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

    function download(){
        ini_set('zlib.output_compression','Off');
		header("Cache-Control: public");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Content-Description: File Transfer"); 
		header("Content-type: application/vnd.ms-excel");
		header("Content-type: application/x-msexcel");    
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-disposition: attachment; filename=pbk_template.xls");
		header("Content-Transfer-Encoding: binary"); 
		readfile("./public/files/template/sms/pbk.xls");
    }

    function import(){
        $data['title_form'] = "Import Excel Data";
        
        echo $this->parser->parse("sms/pbk/import",$data,true);
    }
    
    function doimport(){
        if(count($_FILES)){
            $path = "./public/files/hasil";
            if(!is_dir($path)){
                mkdir($path);
            }
            
            $path = "./public/files/hasil/".time(); 
            if(!is_dir($path)){
               mkdir($path); 
            }
            
            $config['upload_path'] = $path;
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $upload = $this->upload->do_upload('filename');
            
            if($upload==FALSE){
                echo $this->upload->display_errors()."<br>";
            }else{
                $OLERead	= new OLERead();
    			$data		= new Spreadsheet_Excel_Reader($OLERead);
    			$data->setOutputEncoding('CP1251');
    		    $data->read($path."/".$_FILES['filename']['name']);
    		    $data 		= $data->sheets[0];
                $sukses 	= 0;
                $gagal 		= 0;
                $duplicate 	= 0;

                for ($i = 2; $i <= $data['numRows']; $i++) {
                   
					$this->db->where('nomor',$data['cells'][$i][1]);
					$pbk = $this->db->get('sms_pbk')->row();
					if(!empty($pbk)){
                        $duplicate ++;
					}else{
	                	$dt = array();
	                	$dt['nomor'] 		= $data['cells'][$i][1];
	                	$dt['nama'] 		= $data['cells'][$i][2];
	                	$dt['id_sms_grup'] 	= $this->cekGrup($data['cells'][$i][3]);
	                	$dt['created_on'] 	= date("Y-m-d H:i:s");

                        $try = $this->db->insert('sms_pbk', $dt);
                        if($try) $sukses++; else $gagal++;  
					}
				}
                
                echo "<span style='color: green'>Successfully inserted $sukses data(s) item</span>";
                echo "</br>";
                echo "<span>$gagal data(s) item failed to insert</span>";
                echo "</br>";
                echo "<span>$duplicate data(s) item updated</span>";
                echo "</br>";
            }
        }else{
            echo "<span style='color: red'>Please select excel file</span>";
        }
    }

    function cekGrup($nama = ""){
		$this->db->where('nama',$nama);
		$grup = $this->db->get('sms_grup')->row();

		if(empty($grup)){
			$dt = array("nama" => $nama);
			if($this->db->insert('sms_grup',$dt)){
				return mysql_insert_id();
			}else{
				return 1;
			}
		}else{
			return $grup->id_grup;
		}

    }
}
