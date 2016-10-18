<?php
class Msg_model extends CI_Model  {

    var $tabel    = 'app_users_list';
	var $tabel2    = 'app_users_profile';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
    function get_subject($mid){
        $this->db->select("msubject");
        $this->db->where("mid",$mid);
    	$query=$this->db->get('app_user_msgs');
    	if($data = $query->row_array()){
			return $data['msubject'];
		}else{
			return "No Subject";
		}
    }	
    
    function get_message($mid){
        $this->db->where("mid",$mid);
        $this->db->order_by("dtime","asc");
    	$query=$this->db->get('app_user_msgs_reply');
    	$data =  $query->result_array();
		foreach($data as $x){
			$option['mid']=$mid;
			$option['username']=$this->session->userdata('username');
			$option['dtime']=$x['dtime'];
			$option['dtime_seen']=0;
			$dt['dtime_seen']=time();
			$this->db->update('app_user_msgs_read',$dt,$option);
		}

		return $data;
    }	
    
    function get_message_recent($mid,$last_reply){
        $this->db->where("mid",$mid);
        $this->db->where("dtime > ",$last_reply);
        $this->db->order_by("dtime","asc");
    	$query=$this->db->get('app_user_msgs_reply');
    	$data =  $query->result_array();
		foreach($data as $x){
			$option['mid']=$mid;
			$option['username']=$this->session->userdata('username');
			$option['dtime']=$x['dtime'];
			$option['dtime_seen']=0;
			$dt['dtime_seen']=time();
			$this->db->update('app_user_msgs_read',$dt,$option);
		}

		return $data;
    }	
    
    function get_message_row($mid,$reply){
        $this->db->where("mid",$mid);
        $this->db->where("dtime",$reply);
    	$query=$this->db->get('app_user_msgs_reply');
    	return $query->row_array();
    }	
    
    function get_users_list($mid){
        $this->db->select("app_users_profile.username,app_users_profile.name_display,app_users_profile.nip,mas_balai.nama_balai");
        $this->db->where("mid",$mid);
		$this->db->join("app_users_profile","app_users_profile.username=app_user_msgs_participant.username","right");
		$this->db->join("mas_balai","mas_balai.id_balai=app_users_profile.balai","left");
    	$query=$this->db->get('app_user_msgs_participant');

    	return $query->result_array();
    }	
    
    function get_users_count($mid){
		$query = $this->db->query("SELECT COUNT(*) as users FROM app_user_msgs_participant WHERE mid='".$mid."' GROUP BY mid");

    	return $query->row_array();
    }	
    
    function get_message_list($location){
		$read = "(SELECT COUNT(*) as unread,mid,username FROM app_user_msgs_read WHERE username='".$this->session->userdata('username')."' AND dtime_seen=0 GROUP BY mid) AS read_status";
		$last = "(SELECT * FROM (SELECT MID,dtime,mmessage FROM app_user_msgs_reply  ORDER BY dtime DESC) AS last GROUP BY MID ) AS last_msg";
		$part = "(SELECT * FROM app_user_msgs_participant WHERE location='".$location."' AND username='".$this->session->userdata('username')."' ) AS part";
        
		$this->db->select("app_user_msgs.*,part.location,read_status.unread,last_msg.dtime,last_msg.mmessage");

		$this->db->join($read,"read_status.mid=app_user_msgs.mid AND read_status.username='".$this->session->userdata('username')."'","left");
		$this->db->join($last,"last_msg.mid=app_user_msgs.mid","right");
        $this->db->join($part,"part.mid=app_user_msgs.mid","right");

		
        $this->db->order_by("dtime","DESC");
    	$query=$this->db->get('app_user_msgs');
    	return $query->result_array();
    }	
    
    function get_userlist(){
        $this->db->select("app_users_profile.username,app_users_profile.name_display,app_users_profile.nip");
        $this->db->order_by("username","ASC");
    	$this->db->join('app_users_profile','app_users_profile.username=app_users_list.username','right');
    	$query=$this->db->get('app_users_list');
    	$dt = $query->result_array();
		foreach($dt as $row) {
			$data[] = array(
				'key' => $row['username'],
				'value'   => $row['name_display']
			);
		}
		return $data;
    }	

	function docompose(){
		$data['mid']=time();
		$data['msubject']=$this->input->post('msubject');

		if ($this->db->insert('app_user_msgs',$data)){
			$reply['mid']=$data['mid'];
			$reply['username']=$this->session->userdata('username');
			$reply['dtime']=$reply['mid'];
			$reply['mmessage']=$this->input->post('mmessage');
			if ($this->db->insert('app_user_msgs_reply',$reply)){
				$participant['mid']=$data['mid'];
				$participant['username']=$this->session->userdata('username');
				$participant['location']='inbox';
				$this->db->insert('app_user_msgs_participant',$participant);

				foreach($this->input->post('participant') as $par){
					$participant['mid']=$data['mid'];
					$participant['username']=$par;
					$participant['location']='inbox';
					if($par!=$this->session->userdata('username')) $this->db->insert('app_user_msgs_participant',$participant);
				}

				$read['mid']=$data['mid'];
				$read['username']=$this->session->userdata('username');
				$read['dtime']=$reply['dtime'];
				$read['dtime_seen']=time();
				$this->db->insert('app_user_msgs_read',$read);

				foreach($this->input->post('participant') as $par){
					$read['mid']=$data['mid'];
					$read['username']=$par;
					$read['dtime']=$reply['dtime'];
					$read['dtime_seen']=0;
					$this->db->insert('app_user_msgs_read',$read);
				}

				return $data['mid'];
			}else{
				return false;
			}

		}else{
			return false;
		}
	}

	function doreply($mid){
		$reply['mid']=$mid;
		$reply['username']=$this->session->userdata('username');
		$reply['dtime']=time();
		$reply['mmessage']=$this->input->post('mmessage');
		if ($this->db->insert('app_user_msgs_reply',$reply)){
			$this->db->where('mid',$mid);
			$query=$this->db->get('app_user_msgs_participant');
			$dt = $query->result_array();
			foreach($dt as $row) {
				$read = array();
				$read['mid']=$mid;
				$read['username']=$row['username'];
				$read['dtime']=$reply['dtime'];
				$read['dtime_seen']= $row['username']== $this->session->userdata('username') ? time() : 0;
				$this->db->insert('app_user_msgs_read',$read);
			}

			return $reply['dtime'];
		}else{
			return false;
		}
	}

	function domove($mid,$location){
		$option['mid']=$mid;
		$option['username']=$this->session->userdata('username');
		$data['location']=$location;
		if ($this->db->update('app_user_msgs_participant',$data,$option)){
			return "1";
		}else{
			return false;
		}
	}

	function dodel($mid){
		$option['mid']=$mid;
		$option['username']=$this->session->userdata('username');
		if ($this->db->delete('app_user_msgs_participant',$option)){
			$this->db->delete('app_user_msgs_read',$option);
			return "1";
		}else{
			return false;
		}
	}

    function get_location(){
        $this->db->order_by("nama","ASC");
    	$q=$this->db->get('prm_location');
    	return $q->result_array();
    }	
}