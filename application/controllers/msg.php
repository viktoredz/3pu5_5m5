<?php

class Msg extends CI_Controller {

	var $limit=20;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('msg_model');
		$this->load->helper('html');
	}
	
	function index($act="compose")
	{
		$this->authentication->verify('msg','add');
		$data['act']	= $act;
		$data['location']	= "inbox";
		if($act=="compose"){
			$data['title']				= "Kirim Pesan Baru";
			$data['message-content']	= $this->parser->parse("msg/new",$data,true);
		}else{
			$data['title']		="Pilih Pesan / Subject";
			$data['message-content']	= $this->parser->parse("msg/content",$data,true);
		}

		$data['content'] = $this->parser->parse("msg/form",$data,true);
		$this->template->show($data,"home");
	}

	function compose()
	{
		$this->authentication->verify('msg','add');
		$data['act']	= "compose";
		$data['location']	= "inbox";
		echo $this->parser->parse("msg/new",$data,true);
	}

	function docompose()
	{
		$this->authentication->verify('msg','add');
		$this->form_validation->set_rules('mmessage', 'Pesan', 'trim|required');
		$this->form_validation->set_rules('msubject', 'Subjek', 'trim|required');
		$this->form_validation->set_rules('participant', 'Penerima', 'required');
		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}else{
			echo $this->msg_model->docompose();
		}
	}

	function doreply($mid)
	{
		$this->authentication->verify('msg','add');
		$this->form_validation->set_rules('mmessage', 'Pesan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}else{
			echo $this->msg_model->doreply($mid);
		}
	}

	function domove($mid,$location)
	{
		$this->authentication->verify('msg','edit');
		echo $this->msg_model->domove($mid,$location);
	}

	function dodel($mid)
	{
		$this->authentication->verify('msg','edit');
		echo $this->msg_model->dodel($mid);
	}

	function get_subject($mid)
	{
		$this->authentication->verify('msg','show');
		$title = $this->msg_model->get_subject($mid);

		echo $title;
	}

	function get_message($mid)
	{
		$this->authentication->verify('msg','show');
		$data = array();
		$data['mid'] = $mid;
		$data['msg'] = $this->msg_model->get_message($mid);

		echo  $this->parser->parse("msg/content",$data,true);
	}

	function get_message_recent($mid,$last_reply)
	{
		$this->authentication->verify('msg','show');
		$data = array();
		$data['mid']	= $mid;
		$data['reply']	= $last_reply;
		$data['msg']	= $this->msg_model->get_message_recent($mid,$last_reply);

		if(count($data['msg']>0)){
			echo  $this->parser->parse("msg/content_row_recent",$data,true);
		}else{
			echo "0";
		}
	}

	function get_message_row($mid,$reply)
	{
		$this->authentication->verify('msg','show');
		$data = array();
		$data['mid']	= $mid;
		$data['reply']	= $reply;
		$data['row']	= $this->msg_model->get_message_row($mid,$reply);

		echo  $this->parser->parse("msg/content_row",$data,true);
	}

	function get_message_list($location="inbox")
	{
		$this->authentication->verify('msg','show');
		$data = array();
		$data['list'] = $this->msg_model->get_message_list($location);

		echo $this->parser->parse("msg/list",$data,true);
	}

	function get_users_list($mid)
	{
		$this->authentication->verify('msg','show');
		$data = array();
		$data['users'] = $this->msg_model->get_users_list($mid);

		echo $this->parser->parse("msg/users_list",$data,true);
	}

	function get_users_count($mid)
	{
		$this->authentication->verify('msg','show');
		$data = array();
		$data = $this->msg_model->get_users_count($mid);

		echo intval($data['users'])." Users";
	}

	function get_userlist()
	{
		$this->authentication->verify('msg','show');

		header("Content-type: text/x-json");
		$data = $this->msg_model->get_userlist();

		echo json_encode($data);
	}

}
