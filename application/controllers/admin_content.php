<?php

class Admin_content extends CI_Controller {

	var $limit=120;
	var $page=1;

    public function __construct(){
		parent::__construct();
		$this->load->model('admin_content_model');
	}
	
	function index($page=1)
	{
		$this->authentication->verify('admin','show');
		$data['title_group'] = "Admin Panel";
		$data['title_form'] = "Content";
		$data['query'] = $this->admin_content_model->get_data(array("id_theme"=>"2")); 

		$data['content'] = $this->parser->parse("admin/content/show",$data,true);

		$this->template->show($data,"home");
	}

	function edittext($file,$file_id=0)
	{
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		if(!file_exists('media/images/text/'.$_SEGS['file_id'])) {
			mkdir('media/images/text/'.$_SEGS['file_id']);
		}

		$file = $this->admin_content_model->get_data_row($file_id); 
		if(count($this->admin_content_model->text_get_data_row($file_id))>1){
			$content = $this->admin_content_model->text_get_data_row($file_id);
			$content['hits'] = number_format($content['hits'])." views";
			$content['dtime'] = ($content['dtime']=="" ? date("Y m d H:i:s",time()) : date("Y m d H:i:s",$content['dtime']));
			$content['linktmp'] = base_url()."media/images/folder-icon.jpg";
		}else{
			$content['author'] = $this->session->userdata('username');
			$content['title_content'] = "";
			$content['content'] = "";
			$content['headline'] = "";
			$content['published'] = 0;
			$content['hits'] = "-";
			$content['dtime'] = date("Y m d H:i:s",time());
			$content['linktmp'] = base_url()."media/images/folder-icon.jpg";
		}
		$data = array_merge($file,$content);
		$data['title_group'] = "Admin Content";
		$data['title_form'] = $file['filename'];
		$data['filelist_kartik'] = $this->filelist_kartik('media/images/text/'.$_SEGS['file_id'],"text",$_SEGS['file_id']);
		
		$data['lang'] = $this->admin_content_model->get_lang(); 
		$data['file_id'] = $file_id;
		$data['content'] = $this->parser->parse("admin/content/edittext",$data,true);
		$this->template->show($data,"home");
	}

	function doedittext($file_id=0){
		$this->authentication->verify('admin','edit');

		$lang = $this->admin_content_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('title_content_'.$row['kode'], 'Judul Halaman '.$row['lang'], 'trim|required');
		}

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."admin_content/edittext/file_id/".$file_id);
		}
		elseif(count($this->admin_content_model->text_get_data_row($file_id))>1){
			if($this->admin_content_model->text_update_entry($file_id)){
				$this->session->set_flashdata('alert_form', 'Update data successful...');
				redirect(base_url()."admin_content/edittext/file_id/".$file_id);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/edittext/file_id/".$file_id);
			}
		}
		elseif($this->admin_content_model->text_update_entry($file_id)){
			$this->session->set_flashdata('alert_form', 'Insert data successful...');
			redirect(base_url()."admin_content/edittext/file_id/".$file_id);
		}
		else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."admin_content/edittext/file_id/".$file_id);
		}
		
	}

	function editnews()
	{
		$this->authentication->verify('admin','show');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		$data['start'] = 1; 
		$data['query'] = $this->admin_content_model->news_get_data($_SEGS); 

		$data['title_group'] = "Admin Content";
		$data['title_form'] = $file['filename'];

		$data['content'] = $this->parser->parse("admin/content/shownews",$data,true);
		$data = array_merge($data,$file,$_SEGS);
		$this->template->show($data,"home");
	}


	function editnews_form()
	{
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 
		if(!file_exists('media/images/news/'.$_SEGS['file_id'])) {
			mkdir('media/images/news/'.$_SEGS['file_id']);
		}

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		if(isset($_SEGS['id']) && $_SEGS['id']>0){
			$content = $this->admin_content_model->news_get_data_row($_SEGS['file_id'],$_SEGS['id']);
			$content['hits'] = number_format($content['hits'])." views";
			$content['dtime'] = ($content['dtime']=="" ? date("Y m d H:i:s",time()) : date("Y m d H:i:s",$content['dtime']));
			$content['linktmp'] = base_url()."media/images/folder-icon.jpg";
			if(!isset($content['links']) || $content['links']==""){
				$content['links'] = $content['linktmp'];
				$content['links1'] = "";
			}else{
				$content['links1'] = $content['links'];
			}
		}else{
			$content['author'] = $this->session->userdata('username');
			$content['title_content'] = "";
			$content['content'] = "";
			$content['headline'] = "";
			$content['published'] = 0;
			$content['hits'] = "-";
			$content['dtime'] = date("Y m d H:i:s",time());
			$content['id'] = 0;
			$content['linktmp'] = base_url()."media/images/folder-icon.jpg";
			if(!isset($content['links']) || $content['links']==""){
				$content['links'] = $content['linktmp'];
				$content['links1'] = "";
			}else{
				$content['links1'] = $content['links'];
			}
		}

		$data = array_merge($file,$content,$_SEGS);
		
		$data['title_group'] = "Admin Content";
		$data['title_form'] = strtoupper($file['module']). " : ".$file['filename'];
		$data['lang'] = $this->admin_content_model->get_lang(); 
		$data['filelist_kartik_thumb'] = $this->filelist_kartik_thumb($content['links'],$_SEGS['file_id'],$content['id']);
		$data['filelist_kartik'] = $this->filelist_kartik('media/images/news/'.$_SEGS['file_id'],"news",$_SEGS['file_id']);
		$data['content'] = $this->parser->parse("admin/content/editnews",$data,true);
		$this->template->show($data,"home");
	}

	function doeditnews(){
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$lang = $this->admin_content_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('title_content_'.$row['kode'], 'Judul Halaman '.$row['lang'], 'trim|required');
		}

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."admin_content/editnews_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
		}
		elseif($_SEGS['id']>0){
			if($this->admin_content_model->news_update_entry($_SEGS['file_id'],$_SEGS['id'])){
				$this->session->set_flashdata('alert', 'Update data successful...');
				redirect(base_url()."admin_content/editnews/file_id/".$_SEGS['file_id']);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editnews_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
			}
		}
		else{
			if($this->admin_content_model->news_update_entry($_SEGS['file_id'])){
				$this->session->set_flashdata('alert', 'Insert data successful...');
				redirect(base_url()."admin_content/editnews/file_id/".$_SEGS['file_id']);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editnews_form/file_id/".$_SEGS['file_id']);
			}
		}
	}

	function dodelnews($file_id,$id){
		$this->authentication->verify('admin','del');

		if($this->admin_content_model->news_delete_entry($file_id,$id)){
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."admin_content/editnews/file_id/".$file_id);
	}

	function editgallery()
	{
		$this->authentication->verify('admin','show');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		$data['start'] = 1; 
		$data['query'] = $this->admin_content_model->gallery_get_data($_SEGS); 

		$data['title_group'] = "Admin Content";
		$data['title_form'] = $file['filename'];

		$data['content'] = $this->parser->parse("admin/content/showgallery",$data,true);
		$data = array_merge($data,$file,$_SEGS);
		$this->template->show($data,"home");
	}


	function editgallery_form()
	{
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 
		if(!file_exists('media/images/gallery/'.$_SEGS['file_id'])) {
			mkdir('media/images/gallery/'.$_SEGS['file_id']);
		}

		if(isset($_SEGS['id']) && $_SEGS['id']>0){
			$content = $this->admin_content_model->gallery_get_data_row($_SEGS['file_id'],$_SEGS['id']);
			$content['hits'] = number_format($content['hits'])." views";
			$content['dtime'] = ($content['dtime']=="" ? date("Y m d H:i:s",time()) : date("Y m d H:i:s",$content['dtime']));
			//$content['linktmp'] = $content['links'];
		}else{
			$content['author'] = $this->session->userdata('username');
			$content['title_content'] = "";
			$content['content'] = "";
			$content['headline'] = "";
			$content['links'] = "";
			//$content['linktmp'] = base_url()."media/images/folder-icon.jpg";
			$content['published'] = 0;
			$content['hits'] = "-";
			$content['dtime'] = date("Y m d H:i:s",time());
			$content['id'] = 0;
		}

		$data = array_merge($file,$content,$_SEGS);
		
		$data['title_group'] = "Admin Content";
		$data['title_form'] = strtoupper($file['module']). " : ".$file['filename'];
		$data['filelist_kartik'] = $this->filelist_kartik_gallery($content['links'],$_SEGS['file_id'],$content['id']);
		$data['lang'] = $this->admin_content_model->get_lang(); 
		$data['content'] = $this->parser->parse("admin/content/editgallery",$data,true);
		$this->template->show($data,"home");
	}

	function cropped(){
		Header("Content-Type: image/jpeg");

		$_SEGS = $this->uri->ruri_to_assoc();
		$this->im= $this->gallery_model->get_data_row($_SEGS['file_id'],$_SEGS['id']); 

		$width_max=600;
		$height_max=600;
		//echo $this->im['links'];
		$this->im=str_replace(base_url(),'',$this->im['links']);

		if(file_exists($this->im)){
			list($width, $height, $type, $attr) = getimagesize($this->im);
			if($width>=$height){
				$percent=$width_max/$width;
				$width_new=$width*$percent;
				$height_new=$height*$percent;
			}else{
				$percent=$height_max/$height;
				$width_new=$width*$percent;
				$height_new=$height*$percent;
			}
		}
		//echo $percent;die;
		$new = imagecreatetruecolor($width_new,$height_new);
		$this->im=imagecreatefromjpeg($this->im);
		imagecopyresized($new,$this->im,0,0,0,0,$width_new,$height_new,$width,$height);
		Imagejpeg($new);
		ImageDestroy($new); 
	}

	function douploadkartik($file_id,$module,$resize_width=0){
		$this->authentication->verify('admin','edit');

		$err=array();
		$output=array();

		$config['upload_path'] = 'media/images/'.$module.'/'.$file_id;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '500';
		$config['max_width']  = '1000';
		$config['max_height']  = '800';

		$this->load->library('upload', $config);

        if($this->upload->do_multi_upload("uploadfile")){
            $upload_data = $this->upload->get_multi_upload_data();
			echo json_encode($upload_data);
   			die();
        } else {   
        	$output = array('error'=>$this->upload->display_errors());
   			echo json_encode($output);
   			die();
        }

        echo "{}";
	
	}

	function douploadkartik_thumb($file_id,$id){
		$this->authentication->verify('admin','edit');

		$err=array();
		$output=array();

		$config['upload_path'] = 'media/images/news/'.$file_id;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '500';
		$config['max_width']  = '1000';
		$config['max_height']  = '800';

		$this->load->library('upload', $config);

        if($this->upload->do_upload("uploadfile_thumb")){
            $upload_data = $this->upload->data();
			$this->admin_content_model->gallery_news_links($file_id,$id,$upload_data);
			echo json_encode($upload_data);
   			die();
       } else {   
        	$output = array('error'=>$this->upload->display_errors());
   			echo json_encode($output);
   			die();
        }

        echo "{}";
	
	}

	function douploadkartik_gallery($file_id,$id){
		$this->authentication->verify('admin','edit');

		$err=array();
		$output=array();

		$config['upload_path'] = 'media/images/gallery/'.$file_id;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '500';
		$config['max_width']  = '1000';
		$config['max_height']  = '800';

		$this->load->library('upload', $config);

        if($this->upload->do_upload("uploadfile")){
            $upload_data = $this->upload->data();
			$this->admin_content_model->gallery_update_links($file_id,$id,$upload_data);
			echo json_encode($upload_data);
   			die();
       } else {   
        	$output = array('error'=>$this->upload->display_errors());
   			echo json_encode($output);
   			die();
        }

        echo "{}";
	
	}

	function douploadkartik_video($file_id,$id){
		$this->authentication->verify('admin','edit');

		$err=array();
		$output=array();

		$config['upload_path'] = 'media/images/video/'.$file_id;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '500';
		$config['max_width']  = '1000';
		$config['max_height']  = '800';

		$this->load->library('upload', $config);

        if($this->upload->do_upload("uploadfile")){
            $upload_data = $this->upload->data();
			$this->admin_content_model->video_update_links($file_id,$id,$upload_data);
			echo json_encode($upload_data);
   			die();
       } else {   
        	$output = array('error'=>$this->upload->display_errors());
   			echo json_encode($output);
   			die();
        }

        echo "{}";
	
	}

	function douploadkartik_download($file_id,$id){
		$this->authentication->verify('admin','edit');

		$err=array();
		$output=array();

		$config['upload_path'] = 'media/files/download/'.$file_id;
		$config['allowed_types'] = 'pdf|zip|rar|doc|docx|jpg|png|jpeg|ppt|pptx';
		$this->load->library('upload', $config);

        if($this->upload->do_upload("uploadfile")){
            $upload_data = $this->upload->data();
			$this->admin_content_model->download_update_links($file_id,$id,$upload_data);
			echo json_encode($upload_data);
   			die();
       } else {   
        	$output = array('error'=>$this->upload->display_errors());
   			echo json_encode($output);
   			die();
        }

        echo "{}";
	
	}

	function douploadimages($file_id,$module,$resize_width=0){
		$this->authentication->verify('admin','edit');

		$config['upload_path'] = 'media/images/'.$module.'/'.$file_id;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '500';
		$config['max_width']  = '1000';
		$config['max_height']  = '800';

		$this->load->library('upload', $config);
	
		if (!$this->upload->do_upload('uploadfile'))
		{
			echo $this->upload->display_errors();
		}	
		else
		{
			$data = $this->upload->data();

			if($resize_width>0){
				$resize['image_library'] = 'gd2';
				$resize['source_image'] = $data['full_path'];
				$resize['width'] = 100;
				$resize['height'] = 100;

				$this->load->library('image_lib', $resize);

				$this->image_lib->resize();		
			}

			//echo "success | ".$data['file_name'];
			echo "{}";
		}
	}

	function doeditgallery(){
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$lang = $this->admin_content_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('title_content_'.$row['kode'], 'Gallery Title '.$row['lang'], 'trim|required');
		}
		//$this->form_validation->set_rules('links', 'Gallery Image', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."admin_content/editgallery_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
		}
		elseif($_SEGS['id']>0){
			if($this->admin_content_model->gallery_update_entry($_SEGS['file_id'],$_SEGS['id'])){
				$this->session->set_flashdata('alert', 'Update data successful...');
				redirect(base_url()."admin_content/editgallery_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editgallery_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
			}
		}
		else{
			if($new_id = $this->admin_content_model->gallery_update_entry($_SEGS['file_id'])){
				$this->session->set_flashdata('alert', 'Insert data successful...');
				redirect(base_url()."admin_content/editgallery_form/file_id/".$_SEGS['file_id']."/id/".$new_id);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editgallery_form/file_id/".$_SEGS['file_id']);
			}
		}
	}

	function dodelgallery($file_id,$id){
		$this->authentication->verify('admin','del');
		$file = $this->admin_content_model->gallery_get_data_row($file_id,$id);
		$path = str_replace(base_url(),"",$file['links']);
		$path = BASEPATH."../".$path;

		if($this->admin_content_model->gallery_delete_entry($file_id,$id)){
			unlink($path);
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}

		redirect(base_url()."admin_content/editgallery/file_id/".$file_id);
	}


	function editevent()
	{
		$this->authentication->verify('admin','show');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		$data['start'] = 1; 
		$data['query'] = $this->admin_content_model->event_get_data($_SEGS); 

		$data['title_group'] = "Admin Content";
		$data['title_form'] = $file['filename'];

		$data['content'] = $this->parser->parse("admin/content/showevent",$data,true);
		$data = array_merge($data,$file,$_SEGS);
		$this->template->show($data,"home");
	}


	function editevent_form()
	{
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 
		if(!file_exists('media/images/event/'.$_SEGS['file_id'])) {
			mkdir('media/images/event/'.$_SEGS['file_id']);
		}

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		if(isset($_SEGS['id']) && $_SEGS['id']>0){
			$content = $this->admin_content_model->event_get_data_row($_SEGS['file_id'],$_SEGS['id']);
			$content['hits'] = number_format($content['hits'])." views";

			$content['dtime'] = ($content['dtime']=="" ? date("d/m/Y",time()) : date("d/m/Y",$content['dtime']));
			$content['dtime_end'] = ($content['dtime_end']=="" ? date("d/m/Y",time()) : date("d/m/Y",$content['dtime_end']));
			$content['dtime'] = $content['dtime']." - ".$content['dtime_end'];
			$content['linktmp'] = base_url()."media/images/folder-icon.jpg";
		}else{
			$content['author'] = $this->session->userdata('username');
			$content['title_content'] = "";
			$content['content'] = "";
			$content['headline'] = "";
			$content['published'] = 0;
			$content['hits'] = "-";
			$content['dtime'] = date("d/m/Y",time()).' - '.date("d/m/Y",time());
			$content['id'] = 0;
			$content['linktmp'] = base_url()."media/images/folder-icon.jpg";
		}

		$data = array_merge($file,$content,$_SEGS);
		
		$data['title_group'] = "Admin Content";
		$data['title_form'] = strtoupper($file['module']). " : ".$file['filename'];
		$data['lang'] = $this->admin_content_model->get_lang(); 
		$data['filelist_kartik'] = $this->filelist_kartik('media/images/event/'.$_SEGS['file_id'],"event",$_SEGS['file_id']);
		$data['content'] = $this->parser->parse("admin/content/editevent",$data,true);
		$this->template->show($data,"home");
	}

	function doeditevent(){
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$lang = $this->admin_content_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('title_content_'.$row['kode'], 'Title '.$row['lang'], 'trim|required');
		}

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."admin_content/editevent_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
		}
		elseif($_SEGS['id']>0){
			if($this->admin_content_model->event_update_entry($_SEGS['file_id'],$_SEGS['id'])){
				$this->session->set_flashdata('alert', 'Update data successful...');
				redirect(base_url()."admin_content/editevent/file_id/".$_SEGS['file_id']);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editevent_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
			}
		}
		else{
			if($this->admin_content_model->event_update_entry($_SEGS['file_id'])){
				$this->session->set_flashdata('alert', 'Insert data successful...');
				redirect(base_url()."admin_content/editevent/file_id/".$_SEGS['file_id']);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editevent_form/file_id/".$_SEGS['file_id']);
			}
		}
	}

	function dodelevent($file_id,$id){
		$this->authentication->verify('admin','del');

		if($this->admin_content_model->event_delete_entry($file_id,$id)){
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."admin_content/editevent/file_id/".$file_id);
	}

	function editvideo(){
		$this->authentication->verify('admin','show');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		$data['start'] = 1; 
		$data['query'] = $this->admin_content_model->video_get_data($_SEGS); 

		$data['title_group'] = "Admin Content";
		$data['title_form'] = $file['filename'];

		$data['content'] = $this->parser->parse("admin/content/showvideo",$data,true);
		$data = array_merge($data,$file,$_SEGS);
		$this->template->show($data,"home");
	}

	function editvideo_form()
	{
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 
		if(!file_exists('media/images/video/'.$_SEGS['file_id'])) {
			mkdir('media/images/video/'.$_SEGS['file_id']);
		}

		if(isset($_SEGS['id']) && $_SEGS['id']>0){
			$content = $this->admin_content_model->video_get_data_row($_SEGS['file_id'],$_SEGS['id']);
			$content['hits'] = number_format($content['hits'])." views";
			$content['dtime'] = ($content['dtime']=="" ? date("m/d/Y",time()) : date("m/d/Y",$content['dtime']));
		}else{
			$content['author'] = $this->session->userdata('username');
			$content['title_content'] = "";
			$content['content'] = "";
			$content['headline'] = "";
			$content['published'] = 0;
			$content['hits'] = "-";
			$content['dtime'] = date("m/d/Y",time());
			$content['id'] = 0;
			$content['links'] = "";
		}

		$data = array_merge($file,$content,$_SEGS);
		
		$data['title_group'] = "Admin Content";
		$data['title_form'] = strtoupper($file['module']). " : ".$file['filename'];
		$data['lang'] = $this->admin_content_model->get_lang(); 
		$data['filelist_kartik'] = $this->filelist_kartik_video($content['links'],$_SEGS['file_id'],$content['id']);
		$data['content'] = $this->parser->parse("admin/content/editvideo",$data,true);
		$this->template->show($data,"home");
	}

	function doeditvideo(){
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$lang = $this->admin_content_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('title_content_'.$row['kode'], 'Judul Halaman '.$row['lang'], 'trim|required');
		}

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."admin_content/editvideo_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
		}
		elseif($_SEGS['id']>0){
			if($this->admin_content_model->video_update_entry($_SEGS['file_id'],$_SEGS['id'])){
				$this->session->set_flashdata('alert', 'Update data successful...');
				redirect(base_url()."admin_content/editvideo/file_id/".$_SEGS['file_id']);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editvideo_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
			}
		}
		else{
			if($new_id = $this->admin_content_model->video_update_entry($_SEGS['file_id'])){
				$this->session->set_flashdata('alert', 'Insert data successful...');
				redirect(base_url()."admin_content/editvideo_form/file_id/".$_SEGS['file_id']."/id/".$new_id);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editvideo_form/file_id/".$_SEGS['file_id']);
			}
		}
	}

	function dodelvideo($file_id,$id){
		$this->authentication->verify('admin','del');

		if($this->admin_content_model->video_delete_entry($file_id,$id)){
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."admin_content/editvideo/file_id/".$file_id);
	}

	function editdownload()
	{
		$this->authentication->verify('admin','show');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 

		$data['start'] = 1; 
		$data['query'] = $this->admin_content_model->download_get_data($_SEGS); 

		$data['title_group'] = "Admin Content";
		$data['title_form'] = $file['filename'];

		$data['content'] = $this->parser->parse("admin/content/showdownload",$data,true);
		$data = array_merge($data,$file,$_SEGS);
		$this->template->show($data,"home");
	}


	function editdownload_form()
	{
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$file = $this->admin_content_model->get_data_row($_SEGS['file_id']); 
		if(!file_exists('media/files/download/'.$_SEGS['file_id'])) {
			mkdir('media/files/download/'.$_SEGS['file_id']);
		}

		if(isset($_SEGS['id']) && $_SEGS['id']>0){
			$content = $this->admin_content_model->download_get_data_row($_SEGS['file_id'],$_SEGS['id']);
			$content['hits'] = number_format($content['hits'])." times";
			$content['dtime'] = ($content['dtime']=="" ? date("Y m d H:i:s",time()) : date("Y m d H:i:s",$content['dtime']));
		}else{
			$content['author'] = $this->session->userdata('username');
			$content['title_content'] = "";
			$content['content'] = "";
			$content['headline'] = "";
			$content['links'] = "";
			$content['published'] = 0;
			$content['hits'] = "-";
			$content['dtime'] = date("Y m d H:i:s",time());
			$content['id'] = 0;
		}

		$data = array_merge($file,$content,$_SEGS);
		
		$data['title_group'] = "Admin Content";
		$data['title_form'] = strtoupper($file['module']). " : ".$file['filename'];
		$data['lang'] = $this->admin_content_model->get_lang(); 
		$data['filelist_kartik'] = $this->filelist_kartik_download($content['links'],$_SEGS['file_id'],$content['id']);
		$data['content'] = $this->parser->parse("admin/content/editdownload",$data,true);
		$this->template->show($data,"home");
	}

	function douploadifile($file_id,$module,$resize_width=0){
		$this->authentication->verify('admin','edit');

		$config['upload_path'] = 'media/files/'.$module.'/'.$file_id;
		$config['max_size']	= '100000';
		$config['allowed_types'] = 'pdf|doc|xls|xlsx|docx|zip|rar|exe|tar|gz|txt|html|htm';

		$this->load->library('upload', $config);
	
		if (!$this->upload->do_upload('uploadfile'))
		{
			echo $this->upload->display_errors();
		}	
		else
		{
			$data = $this->upload->data();

			echo "success | ".$data['file_name'];
		}
	}

	function doeditdownload(){
		$this->authentication->verify('admin','edit');
		$_SEGS = $this->uri->ruri_to_assoc();

		$lang = $this->admin_content_model->get_lang(); 
		foreach($lang as $row){
			$this->form_validation->set_rules('title_content_'.$row['kode'], 'Title '.$row['lang'], 'trim|required');
		}
		//$this->form_validation->set_rules('links', 'File Link', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('alert_form', validation_errors());
			redirect(base_url()."admin_content/editdownload_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
		}
		elseif($_SEGS['id']>0){
			if($this->admin_content_model->download_update_entry($_SEGS['file_id'],$_SEGS['id'])){
				$this->session->set_flashdata('alert', 'Update data successful...');
				redirect(base_url()."admin_content/editdownload/file_id/".$_SEGS['file_id']);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editdownload_form/file_id/".$_SEGS['file_id']."/id/".$_SEGS['id']);
			}
		}
		else{
			if($new_id = $this->admin_content_model->download_update_entry($_SEGS['file_id'])){
				$this->session->set_flashdata('alert', 'Insert data successful...');
				redirect(base_url()."admin_content/editdownload_form/file_id/".$_SEGS['file_id']."/id/".$new_id);
			}
			else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."admin_content/editdownload_form/file_id/".$_SEGS['file_id']);
			}
		}
	}

	function dodeldownload($file_id,$id){
		$this->authentication->verify('admin','del');
		$file = $this->admin_content_model->download_get_data_row($file_id,$id);
		$path = str_replace(base_url(),"",$file['links']);
		$path = BASEPATH."../".$path;

		if($this->admin_content_model->download_delete_entry($file_id,$id)){
			unlink($path);
			$this->session->set_flashdata('alert', 'Delete data successful...');
		}else{
			$this->session->set_flashdata('alert', 'Delete data failed...');
		}
		redirect(base_url()."admin_content/editdownload/file_id/".$file_id);
	}

	function dodelkartik($file_id,$module,$filename){
		$path = BASEPATH."../media/images/".$module."/".$file_id."/".$filename;
		unlink($path);
		echo "{}";
	}

	function filelist_kartik($folder,$module,$file_id){
		$data = array();
		$folder = str_replace("__","/",$folder);

		$this->load->helper('file');
		$data['files']= get_dir_file_info($folder);
		$data['module']= $module;
		$data['file_id']= $file_id;

		return $this->parser->parse("admin/content/filelist_kartik",$data,true);
	}

	function filelist_kartik_thumb($filename,$file_id,$id){
		$data = array();

		$data['filename']= $filename;
		$data['id']= $id;
		$data['file_id']= $file_id;

		return $this->parser->parse("admin/content/filelist_kartik_thumb",$data,true);
	}

	function filelist_kartik_gallery($filename,$file_id,$id){
		$data = array();

		$data['filename']= $filename;
		$data['id']= $id;
		$data['file_id']= $file_id;

		return $this->parser->parse("admin/content/filelist_kartik_gallery",$data,true);
	}

	function filelist_kartik_video($filename,$file_id,$id){
		$data = array();

		$data['filename']= $filename;
		$data['id']= $id;
		$data['file_id']= $file_id;

		return $this->parser->parse("admin/content/filelist_kartik_video",$data,true);
	}

	function filelist_kartik_download($filename,$file_id,$id){
		$data = array();

		$data['filename']= $filename;
		$data['id']= $id;
		$data['file_id']= $file_id;

		return $this->parser->parse("admin/content/filelist_kartik_download",$data,true);
	}
	function filelist($folder){
		$data = array();
		$folder = str_replace("__","/",$folder);

		$this->load->helper('file');
		$data['files']= get_dir_file_info($folder);

		echo $this->parser->parse("admin/content/filelist",$data,true);
	}
}
