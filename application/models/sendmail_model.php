<?php
class Sendmail_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function get_mail_config(){
		$this->db->like('key', 'mail');
		$query=$this->db->get('app_config');
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['key']]=$dt['value'];
		}

		return $data;    	
    }
    
    function dosendmail($email,$subject,$message,$debug=0){
        $data = $this->get_mail_config();
        
		$data['message']	= "BSPMB TP : ".$message;
		$data['signature']	= $data['mail_signature'];

		//$config['protocol'] = "smtp";
		$config['smtp_host'] = "";
		$config['smtp_port'] = "";
		$config['smtp_user'] = $data['mail_user'];
		$config['smtp_pass'] = $data['mail_password'];
		$config['smtp_timeout'] = 5;
		$config['wordwrap'] = TRUE;
		$config['wrapchars'] = 76;
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";
		$config['newline'] = "\r\n";
		$config['validate'] = FALSE;
		$config['priority'] = 3;
		$config['crlf'] = "\r\n";
		$config['bcc_batch_mode'] = FALSE;
		$config['bcc_batch_size'] = 200;

		$this->email->initialize($config);

		$this->email->from($data['mail_user'], 'BSPMB-TP');
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($this->parser->parse('sendmail/postman',$data)); 
		echo "1";
		die();
		/*if($send = $this->email->send()){
			echo "1";
			if($debug==0){
				die();
			}else{
				echo $this->email->print_debugger();
			}
		}else{
			echo $this->email->print_debugger();
		}*/
    }
}
?>