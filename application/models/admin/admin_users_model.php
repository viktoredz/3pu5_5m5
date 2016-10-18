<?php
class Admin_users_model extends CI_Model {

    var $tabel    = 'app_users_list';
	var $tabel2    = 'app_users_profile';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
   }
    
    function get_count()
    {
        return $this->db->count_all($this->tabel);
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->where('app_users_list.code',$this->session->userdata('puskesmas'));
		$this->db->where('app_users_list.username <>','admin');
		foreach($options as $x=>$y){
			if($x=="username"){
				$this->db->like($x,$y);
			}else{
				$this->db->where($x,$y);
			}
		}
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

    function get_list()
    {
		$this->db->where('app_users_list.code',$this->session->userdata('puskesmas'));
		$this->db->where('app_users_list.username <>','admin');
		$this->db->order_by($this->tabel.'.username','ASC');
		$this->db->join($this->tabel2, $this->tabel.'.username='.$this->tabel2.'.username', 'inner');
		$this->db->join("mas_propinsi","mas_propinsi.id_propinsi=app_users_profile.propinsi","left");
		$this->db->join("mas_kota","mas_kota.id_kota=app_users_profile.kota","left");
		$this->db->join("mas_kecamatan","mas_kecamatan.id_kecamatan=app_users_profile.kecamatan","left");
		$this->db->join("mas_desa","mas_desa.id_desa=app_users_profile.desa","left");
		$this->db->select('mas_propinsi.nama_propinsi as propinsi, mas_kota.nama_kota as kota, mas_kecamatan.nama_kecamatan as kecamatan, mas_desa.nama_desa as desa, app_users_list.username, app_users_list.level, app_users_list.last_login, app_users_list.last_active, app_users_profile.jenis, app_users_profile.email, app_users_profile.nama, app_users_profile.address, app_users_profile.birthdate, app_users_profile.birthplace, app_users_profile.jabatan, app_users_profile.perusahaan, app_users_profile.phone_number');
		$query = $this->db->get($this->tabel);
        return $query->result_array();
    }

    function get_list2()
    {
		$this->db->where('app_users_list.code',$this->session->userdata('puskesmas'));
		$this->db->where('app_users_list.username <>','admin');
		$this->db->order_by($this->tabel.'.username','ASC');
		$this->db->join($this->tabel2, $this->tabel.'.username='.$this->tabel2.'.username', 'inner');
		$query = $this->db->get($this->tabel);
        return $query->result_array();
    }


   function get_data_all()
    {
		$this->db->where('app_users_list.code',$this->session->userdata('puskesmas'));
		$this->db->from($this->tabel);
		$this->db->order_by($this->tabel.'.username','DESC');
		$this->db->join($this->tabel2, $this->tabel.'.username='.$this->tabel2.'.username', 'left');
		$query = $this->db->get();
        return $query->result();
    }

 	function get_data_row($username){
		$this->db->where('app_users_list.code',$this->session->userdata('puskesmas'));
		$options = array('username' => $username);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	
	function get_data_prof(){
		$data = array();
		$username= $this->session->userdata('username');
		$options = array('username' => $username);
		
		$query = $this->db->get_where($this->tabel2,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

	function get_validasi_username($username){
		$data = array();
		$options = array('username' => $username);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result(); 
		return $data;
	}

	function get_data_pass($email,$new_pass){
		$data['password']=$this->encrypt->sha1($new_pass.$this->config->item('encryption_key'));
		return $this->db->update($this->tabel, $data, array('username' => $email));
	}

 	function get_data_profile_row($username){
		$data = array();
		$options = array($this->tabel.'.username' => $username);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$this->db->where($options);
		$this->db->select('app_users_list.username,app_users_list.level,app_users_list.status_active,app_users_list.online,app_users_list.last_login,app_users_profile.*');
		$this->db->from($this->tabel);
		$this->db->join('app_users_profile', 'app_users_list.username=app_users_profile.username', 'left');
		$this->db->group_by('app_users_list.username');
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		$query->free_result();    
		return $data;
	}
	
	function get_data_profile_email($email){
		$data = array();
		$options = array($this->tabel.'.username' => $email);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$this->db->where($options);
		$this->db->select('app_users_list.username,app_users_list.level,app_users_list.status_active,app_users_list.online,app_users_list.last_login,app_users_profile.*');
		$this->db->from($this->tabel);
		$this->db->join('app_users_profile', 'app_users_list.username=app_users_profile.username', 'left');
		$this->db->group_by('app_users_list.username');
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		$query->free_result();    
		return $data;
	}

   function insert_entry()
    {
        $data['username']=$this->input->post('username');
        $data['level']=$this->input->post('level');
        $data['code']=$this->input->post('code');
        $data['password']=$this->encrypt->sha1($this->input->post('password').$this->config->item('encryption_key'));
        $data['status_active']=1;
        $data['status_aproved']=0;
        $data['online']=0;
        $data['last_login']=0;
        $data['last_active']=0;
        $data['datereg']=time();	

		
		$this->db->select('*');
		$this->db->from('app_users_list');
		$this->db->where('username', "".$this->input->post('username'));
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
			$profile['email']=$this->input->post('email');
			$profile['nama']=$this->input->post('nama');
			$profile['code']=$this->input->post('code');
			$profile['phone_number']=$this->input->post('phone_number');
			if($this->db->update('app_users_profile',  $profile, array('username' => $this->input->post('username')))){
				return $this->input->post('username');
			}
		} else {
			if($this->db->insert("app_users_list", $data)) {
			
				$profile = array();
				//$profile['trup']=$this->createTRUP();
				$profile['username']=$this->input->post('username');
				$profile['email']=$this->input->post('email');
				$profile['nama']=$this->input->post('nama');
				$profile['code']=$this->input->post('code');
				$profile['phone_number']=$this->input->post('phone_number');
				if($this->db->insert("app_users_profile", $profile)){
					return $this->input->post('username');
				}
			} else {
				return false;
			}
		}
    }

    function update_entry($username)
    {
		$data['username']=$this->input->post('username');
		$data['level']=$this->input->post('level');
		if($this->input->post('password')!="password" && $this->input->post('password2')!="password"){
			$data['password']=$this->encrypt->sha1($this->input->post('password').$this->config->item('encryption_key'));
		}
		$data['status_active']=number_format($this->input->post('status_active'));
        
		return $this->db->update($this->tabel, $data, array('username' => $username));
    }
	
	function update_aktivasi($username,$mail)
    {
    	$this->db->where('username', $username);
    	$this->db->where('username', $mail);
    	$q = $this->db->get('app_users_list');
    	if($q->num_rows()==1){
    		$data['status_active']='1';	
    		if($this->db->update($this->tabel, $data, array('username' => $username))){
    			return true;
    		}else{
    			return false;
    		}
    	}else{  		
    		return false;
    	}
    }


	function delete_entry($username)
	{
		$this->db->where(array('username' => $username));
		$this->db->delete("app_users_profile");

		$this->db->where(array('username' => $username));
		return $this->db->delete($this->tabel);
	}

    function get_level($blank=0)
    {
        $query = $this->db->get('app_users_level');
		if($blank==1) $data[""]= "-";
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['level']]=ucfirst($dt['level']);
		}
		$query->free_result();    
		return $data;
    }
    
    function get_mail_config(){
		$this->db->like('key', 'mail');
		$query=$this->db->get('app_config');
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['key']]=$dt['value'];
		}

		return $data;    	
    }
    
    function get_user_id($username=0){
        $options = array('app_users_list.username'=>$username);
        $this->db->select("app_users_profile.*,app_users_list.level");
        $this->db->join($this->tabel,"app_users_list.username=app_users_profile.username","inner");
        $query = $this->db->get_where($this->tabel2,$options,1);
        if($query->num_rows() > 0){
            $data=$query->row_array();
        }
        return $query->free_result();
    }
  
     function get_user_profile($username=0){
       $query = $this->db->query("select * from app_users_profile where username = '$username'");
       return $query->row();
    }
    
    function get_all_position(){
        $this->db->order_by('nama_jabatan','asc');
        $query = $this->db->get('mas_jabatan');
        return $query->result();
    }
    
    function get_all_grade(){
        $query = $this->db->get('mas_grade');
        return $query->result();
    }
    
    function get_user_list($username){
        $query = $this->db->query("select * from app_users_list where username = '$username'");
        return $query->row();
    }
    
    function get_user_level(){
        $query=$this->db->get('app_users_level');
        return $query->result();
    }
    
    function update_profile($username) {
    	$profile['email']=$this->input->post('email');
    	$profile['nama']=$this->input->post('nama');
    	$profile['phone_number']=$this->input->post('phone_number');
    	$profile['kota']=$this->input->post('kota');
    	$profile['code']=$this->input->post('code');


    	$check = $this->db->get_where('app_users_profile', array('username' => $username));
		$check = $check->num_rows();

    	if($check>0){
    		$this->db->where('username',$username);
	    	return $this->db->update('app_users_profile',  $profile);
	    } else {
	    	return 0;
	    }
    }
        
    function update_account($username){
        $val['username']=$this->input->post('username');
        $val['level']=$this->input->post('level');
        if($this->input->post('password')!="password" && $this->input->post('password2')!="password"){
            $val['password']=$this->encrypt->sha1($this->input->post('password').$this->config->item('encryption_key'));
        }
        //$val['status_active']=number_format($this->input->post('status_active'));
        
        return $this->db->update($this->tabel,$val,array('username'=>$username));
    }
 
    function get_all_gol(){
        $query = $this->db->get('mas_golongan');
        return $query->result();
    }
}