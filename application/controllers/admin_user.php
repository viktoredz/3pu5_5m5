<?php

class Admin_user extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_users_model');
		$this->load->helper('captcha');
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
	}
	
	function index()
	{
		$this->authentication->verify('admin','edit');

		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "User List";
		$data['query'] = $this->admin_users_model->get_data(); 


		$data['content'] = $this->parser->parse("admin/users/show",$data,true);

		$this->template->show($data,'home');
	}

	function excel()
	{
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		$POST = array();
		
		$data['query'] = $this->admin_users_model->get_list(); 
		$userlist = $data['query'];

		for ($i=0; $i < sizeof($userlist) ; $i++) { 
			if ($userlist[$i]['birthdate'] != null) {
				$tgl_tmp = explode("/", $userlist[$i]['birthdate']);
				$userlist[$i]['birthdate'] = (int)$tgl_tmp[0]." ".$BulanIndo[(int)$tgl_tmp[1]-1]." ".$tgl_tmp[2];
			}
			$userlist[$i]['last_login'] = "".date("d-m-Y h:i:s",$userlist[$i]['last_login']);
			$userlist[$i]['last_active'] = "".date("d-m-Y h:i:s",$userlist[$i]['last_active']);
		}

		$TBS = new clsTinyButStrong;
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$TBS->ResetVarRef(false);
		$TBS->VarRef =  &$data;		
		$template = dirname(__FILE__).'/../../public/files/excel/list_user.xlsx';
		$TBS->LoadTemplate($template);

		$TBS->MergeBlock('a,b', $userlist);
		$output_file_name = 'list_user.xlsx';
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name);

	}

	function add()
	{
		$this->load->model('morganisasi_model');
		$this->authentication->verify('admin','add');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_check_username2');
        $this->form_validation->set_rules('email', 'Email Pendaftar', 'trim|required|callback_check_email2');
        $this->form_validation->set_rules('nama', 'Nama Pendaftar', 'trim|required');
        $this->form_validation->set_rules('code', 'code', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'No Telepon', 'trim');

		if($this->form_validation->run()== FALSE){

			$data = $this->morganisasi_model->get_profile();
			$data['title_group']	= "Admin Panel";
			$data['title_form']		= "Tambah User";


			$data['username']		= $this->session->userdata('username');
			$data['code']			= $this->session->userdata('puskesmas');
			$data['content'] = $this->parser->parse("admin/users/form",$data,true);
        	$this->template->show($data,"home");
		}elseif($this->admin_users_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."admin_user");
		}else{
			$this->session->set_flashdata('alert_form', 'Username telah digunakan...');
			redirect(base_url()."admin_user/add");
		}
	}
    
    function edit($username=0,$code=""){
		$this->load->model('morganisasi_model');
        $this->authentication->verify('admin','edit');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|matches[password]');
      
        $this->form_validation->set_rules('name','Full Name','trim|required');

        $data = $this->admin_users_model->get_user_id($username,$code);
        // var_dump($data);
        // exit();
        // $data = $this->admin_users_model->get_user_profile($username);
		$data['code']			= $code;
        $data['username']		= $username;
		$data['title_group']	= "Admin Panel";
		$data['title_form']		= "User Profile";
		$data['level']			= "";
		$data['level_option']	= "
		<option value='administrator' ".($data['level']=="administrator" ? "selected" : "").">Administrator</option>
		<option value='ketukpintu' ".($data['level']=="ketukpintu" ? "selected" : "").">Ketukpintu</option>
		<option value='guest' ".($data['level']=="guest" ? "selected" : "").">Guest</option>
		<option value='inventory' ".($data['level']=="inventory" ? "selected" : "").">Inventory</option>
		<option value='kepegawaian' ".($data['level']=="kepegewaian" ? "selected" : "").">Kepegawaian</option>
		<option value='keuangan' ".($data['level']=="keuangan" ? "selected" : "").">Keuangan</option>
		<option value='sms' ".($data['level']=="sms" ? "selected" : "").">SMS</option>
		";
        
        $data['content'] = $this->parser->parse("admin/users/user_profile",$data,true);
        $this->template->show($data,"home");
    }

    function update_profile($username){
    	$this->load->model('morganisasi_model');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|callback_check_email2');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Nama Pendaftar', 'trim');
	    $this->form_validation->set_rules('code', 'code', 'trim');

		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}elseif($username=$this->admin_users_model->update_profile($username)){
			echo "Data berhasil disimpan";
		}else{
			echo "Penyimpanan data gagal dilakukan";
		}
    }

    function update_password($username=""){

    	$this->load->model('morganisasi_model');
        $this->form_validation->set_rules('level', 'Level', 'trim|required');
        $this->form_validation->set_rules('password', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('npassword','Password Baru','trim|required|min_length[5]|matches[cpassword]|callback_check_pass2');
		$this->form_validation->set_rules('cpassword', 'Konfirmasi Password', 'trim|required');
        
		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}else{
			if($this->morganisasi_model->check_password()){
				if(!$this->admin_users_model->update_account($username)) {
					echo "Save data failed...";
				} elseif($username=$this->morganisasi_model->update_password($username))
				{
					echo "password berhasil diubah";
				}
			}else{
				echo "Password lama salah...";
			}
		}
    }
	
    function update_set_account($id=0){
        $this->authentication->verify('admin','edit');
        $this->session->set_flashdata($_POST);
        
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('captcha', 'Captcha', 'callback_valid_captcha');
		
        if($this->form_validation->run()==false){
            $this->session->set_flashdata('alert_form', validation_errors());
            $data = $this->admin_users_model->get_user_id($id);
            $data['id']= $id;
            $data['title_form'] = "Users &raquo; Ubah";
            $data['password']="password";
            $data['password2']="password";
           
            $vals = array (
    		'img_path' => './captcha/', //path image
    		'img_url' => base_url().'captcha/', //path url untk nampilin img
    		'font_path' => './system/fonts/BERNHC.TTF',
    		'img_width'	 => '200',
    		'img_height' => 60,
    		'expiration' => 60 // one hour
    		);
    		$cap = create_captcha($vals);
    		
    		$capdb = array(
    		'captcha_time' => $cap['time'],
    		'ip_address' => $this->input->ip_address(),
    		'word' => $cap['word']
    		);
    		
    		$query = $this->db->insert_string('captcha', $capdb);
    		$this->db->query($query);
    		
    		$data['cap'] = $cap;
            
            $data['content'] = $this->parser->parse("admin/users/user_account",$data,true);
			$this->template->show($data,"home");
        }elseif($this->form_validation->run()==true){
            $this->admin_users_model->update_account($id);
            $this->session->set_flashdata('alert_form', 'Save data successful...');
            redirect(base_url()."admin_user");
        }else{
            $this->session->set_flashdata('alert_form', 'Save data failed...');
            redirect(base_url()."update_password/$id");
        }
    }
    
    function detail_profile($id=0){
        $this->authentication->verify('admin','add');
        $data = $this->admin_users_model->get_user_id($id);
        $data['id'] = $id;
		$data['title_group']	= "Admin Panel";
		$data['title_form']		= "User Profile";
		$data['provinsi_option']	= $this->crud->provinsi_option($data['propinsi']);
		$data['level_option']		= "<option value='member' ".($data['level']=="member" ? "selected" : "").">Member</option><option value='administrator' ".($data['level']=="administrator" ? "selected" : "").">Administrator</option>";
        
        $data['content'] = $this->parser->parse("admin/users/user_profile",$data,true);
        $this->template->show($data,"home");
    }
    

	function valid_captcha($str){
		$expiration = time()-3600;
		$this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);
		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ?
		AND ip_address = ? AND captcha_time > ?";
		$binds = array($str, $this->input->ip_address(), $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();
		if ($row->count == 0)
		{
			$this->form_validation->set_message('valid_captcha', 'Captcha salah');
			return FALSE;
		}else{
			return TRUE;
		}
	}
	  	
	function reg_resend_email(){
		$prof = $this->admin_users_model->get_data_prof();
		if($prof['email']!=""){
			$email= $prof['email'];
			$subject='Pendaftaran Fellowship Plasaindigo 2010';
			$message= 'Account : '.$email.'<br>';
			$message.='Untuk melakukan aktivasi keanggotaa, silahkan anda klik link di bawah ini:<br><br>';
			$message.=anchor(base_url()."users/aktivasi/".$this->session->userdata('id')."/".$this->session->userdata('username'),"Lakukan aktivasi sekarang!");
			$this->send_smtp($email,$subject,$message); 
			
			$this->session->set_flashdata('alert', 'Email verifikasi berhasil dikirim ulang.<br>Silahan periksa email anda.');
		}else{
			$this->session->set_flashdata('alert', 'Email anda masih salah.<br>Silahkan perbaiki alamat email anda.');
		}
		redirect(base_url()."admin_user/profile");
	}
	
	function reg_ok() {
		$data['username'] = $this->session->userdata('username');

		$data['content'] = $this->parser->parse("admin/users/reg_ok",$data,true);
		$this->template->show($data);
	}

	
	function doedit($id=0){
		$this->authentication->verify('admin','add');

		$this->form_validation->set_rules('captcha', 'Captcha', 'callback_valid_captcha');
		
		if($this->input->post('password')!="password" && $this->input->post('password2')!="password"){
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|matches[password]|md5');
		}

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert', validation_errors());
			redirect(base_url()."admin_user/edit_account/".$id);
		}elseif($this->admin_users_model->update_entry($id)){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."admin_user/edit_account/".$id);
		}else{
			$this->session->set_flashdata('alert', 'Save data failed...');
			redirect(base_url()."admin_user/edit_account/".$id);
		}
	}

	function doeditprofile_member(){
		$this->authentication->verify('admin','add');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('captcha', 'Captcha', 'callback_valid_captcha');
		$id=$this->input->post('id');
		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert', validation_errors());
			redirect(base_url()."admin_user/profile");
		}elseif($this->admin_users_model->update_entry_profile($id)){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."admin_user/profile");
		}else{
			$this->session->set_flashdata('alert', 'Save data failed...');
			redirect(base_url()."admin_user/profile");
		}
	}

	
	
	function dodel($username,$puskesmas){
		$this->authentication->verify('admin','del');

		if($this->admin_users_model->delete_entry($username,$puskesmas)){
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."admin_user");
	}

	function dodel_multi(){
		$this->authentication->verify('admin','del');

		if(is_array($this->input->post('id'))){
			foreach($this->input->post('id') as $data){
				$this->admin_users_model->delete_entry($data);
			}
			$this->session->set_flashdata('alert', 'Delete ('.count($this->input->post('id')).') data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Nothing to delete.');
		}

		redirect(base_url()."admin_user");
	}
	
		
	function forgot_pass()
	{

		$data['title_form']="Member &raquo; Lupa Kata Kunci";
		$data['email']=$this->session->flashdata('email');
		
		$data['action']="dogetpas";

		$vals = array (
		'img_path' => './captcha/', //path image
		'img_url' => base_url().'captcha/', //path url untk nampilin img
		'font_path' => './system/fonts/BERNHC.TTF',
		'img_width'	 => '200',
		'img_height' => 60,
		'expiration' => 60 // one hour
		);
		$cap = create_captcha($vals);
		
		$capdb = array(
		'captcha_time' => $cap['time'],
		'ip_address' => $this->input->ip_address(),
		'word' => $cap['word']
		);
		
		$query = $this->db->insert_string('captcha', $capdb);
		$this->db->query($query);
		
		$data['cap'] = $cap;


		$data['content'] = $this->parser->parse("admin/users/get_pas",$data,true);
		$this->template->show($data,"home");
	}
	
	function dogetpas(){
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('captcha', 'Captcha', 'callback_valid_captcha');
		$this->load->helper('string');
		$new_pass=random_string('alnum', 10);
		
		$email=$this->input->post('email');
		$subject='New Account Password';
		$message='Password Baru Anda : '.$new_pass;	

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert', validation_errors());
			redirect(base_url()."admin_user/forgot_pass/");
		}elseif($this->admin_users_model->get_data_pass($email,$new_pass)){
			$this->send_smtp($email,$subject,$message);
			$this->session->set_flashdata('alert', 'Perubahan password berhasil dilakukan.<br>Kami sudah mengirimkan password baru anda melalui email.<br>Silahkan periksa email anda');
			redirect(base_url()."admin_user/forgot_pass/");
		}else{
			$this->session->set_flashdata('alert', 'Maaf perubahan password gagal, anda salah memasukkan email.');
			redirect(base_url()."admin_user/forgot_pass/");
		}
		
		
	}

	function aktivation()
	{
		$data['title_form']="Aktivasi";
		
		$data['content'] = $this->parser->parse("admin/users/aktivasi",$data,true);
		$this->template->show($data);
	}
	
	function aktivatioff()
	{
		$data['title_form']="Aktivasi";
		
		$data['content'] = $this->parser->parse("admin/users/aktivasi_failed",$data,true);
		$this->template->show($data);
	}
	
	function aktivasi($id=0,$mail=""){		
		if($this->admin_users_model->update_aktivasi($id,$mail)){
			$this->session->set_flashdata('alert', 'Email anda berhasil di verivikasi.');
			redirect(base_url()."admin_user/aktivation/");
		}else{
			$this->session->set_flashdata('alert', 'Email anda gagal di verivikasi.');
			redirect(base_url()."admin_user/aktivatioff/");
		}
	}
	
	function send_smtp($email,$subject,$message)
	{
		$this->load->library('email');
		//error_reporting(0);
		$data=$this->admin_users_model->get_mail_config();
		$data['message'] = $message;
		$data['signature'] = $data['mail_signature'];
		
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $data['mail_server'];
		$config['smtp_port'] = $data['mail_port'];
		$config['smtp_user'] = $data['mail_user'];
		$config['smtp_pass'] = $data['mail_password'];
		$config['smtp_timeout'] = 5;
		$config['validate'] = TRUE;
		$config['priority'] = 1;
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['useragent'] = "plasaindigo";

		$this->email->initialize($config);

		$this->email->from($data['mail_user'], 'Admin Fellowship');
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($this->parser->parse('users/email',$data)); 
		$this->email->send();
		//echo $this->email->print_debugger();*/
	}
	
	function send_smtp2($email,$subject,$message)
	{
		$this->load->library('email');
		//error_reporting(0);
		$data=$this->admin_users_model->get_mail_config();
		$data['message'] = $message;
		$data['signature'] = $data['mail_signature'];
		
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $data['mail_server'];
		$config['smtp_port'] = $data['mail_port'];
		$config['smtp_user'] = $data['mail_user'];
		$config['smtp_pass'] = $data['mail_password'];
		$config['smtp_timeout'] = 5;
		$config['validate'] = TRUE;
		$config['priority'] = 1;
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['useragent'] = "plasaindigo";

		$this->email->initialize($config);

		$this->email->from('no-reply@fellowship.plasaindigo.com', 'Admin Fellowship');
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($this->parser->parse('users/email',$data)); 
		$this->email->send();
		echo $this->email->print_debugger();
	}
	
	function douploadimages($id){
		$module='users';
		$config['upload_path'] = 'media/images/'.$module.'/'.$id;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '100';
		$config['max_width']  = '800';
		$config['max_height']  = '500';

		$this->load->library('upload', $config);
		if(!file_exists($config['upload_path'])) {
			mkdir($config['upload_path']);
		}
	
		if (!$this->upload->do_upload('uploadfile'))
		{
			echo "failed|".$this->upload->display_errors();
		}	
		else
		{
			$data = array('upload_data' => $this->upload->data());
			echo "success|".$data['upload_data']['file_name'];
		}
		
	}

}
