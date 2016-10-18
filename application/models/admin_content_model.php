<?php
class Admin_content_model extends CI_Model {

    var $tabel    = 'app_files';
    var $tabel_content    = 'app_files_contents';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');

		$this->web_modules = array();
		$query = $this->db->get('app_web_modules');
		foreach ($query->result_array() as $row)
		{
		   $this->web_modules[] = $row['modules'];
		}
    }
    
    function get_count($options)
    {
		if(count($options)<1) $options = array('id_theme' => $this->session->userdata('id_theme'));
		$options['lang'] = $this->lang;
		$this->db->where_in('module',$this->web_modules);
		$this->db->where($options);
        return $this->db->count_all_results($this->tabel);
    }

    function get_data($options,$start=0,$limit=999999)
    {
		if(count($options)<1) $options = array('id_theme' => $this->session->userdata('id_theme'));
		if($this->session->userdata('level')=="admin_bidang") {
			$this->db->where('module <> ','text');
		}
		$options['lang'] = $this->lang;
		$this->db->where_in('module',$this->web_modules);
		$this->db->where($options);
		$this->db->where(array('lang' => $this->lang));
		$this->db->order_by('filename');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

    function get_lang()
    {
		$this->db->order_by('kode', 'DESC');
        $query = $this->db->get('app_lang');
        return $query->result_array();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('id' => $id, 'lang' => 'ina');
		$this->db->where_in('module',$this->web_modules);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

    function get_theme($blank=0)
    {
        $query = $this->db->get('app_theme');
		if($blank==1) $data[""]= "-";
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['id_theme']]=ucfirst($dt['name']);
		}
		$query->free_result();    
		return $data;
    }


 	function text_get_data_row($file_id){
		$data = array();
		$this->db->where('file_id',$file_id);
		$this->db->where('id',1);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['file_id']=$dt['file_id'];
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['published']=$dt['published'];
				$data['author']=$dt['author'];
				$data['dtime']=$dt['dtime'];
				$data['links']=$dt['links'];
				$data['hits']=$dt['hits'];
				$data['dtime_end']=$dt['dtime_end'];
				$data['published']=$dt['published'];
				$data['title_content_'.$dt['lang']]=$dt['title_content'];
				$data['headline_'.$dt['lang']]=$dt['headline'];
				$data['content_'.$dt['lang']]=$dt['content'];
			}
		}

		$query->free_result();    
		return $data;
	}


    function text_update_entry($file_id)
    {
	    $lang = $this->get_lang();
		foreach($lang as $row){
			$title_content = 'title_content_'.$row['kode'];
			$headline = 'headline_'.$row['kode'];
			$content = 'content_'.$row['kode'];

			$data['title_content']=$this->input->post($title_content);
			$data['published']=intval($this->input->post('published'));
			$data['dtime']=time();
			$data['headline']=$this->input->post($headline);
			$data['content']=$this->input->post($content);

			$options = array('file_id' => $file_id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => 1,'lang'=>$row['kode']));
			}else{
				$data['file_id']=$file_id;
				$data['id']=1;
				$data['lang']=$row['kode'];
				$data['author']=$this->session->userdata('username');
				$data['links']="";
				$data['hits']=0;
				$this->db->insert($this->tabel_content, $data);
			}

		}
		return true;
    }

    function news_get_data($options,$start=0,$limit=9999)
    {
		$options['lang'] = $this->lang;
		$this->db->order_by('dtime','DESC');
		$this->db->where($options);
		$this->db->select('app_files_contents.*,prm_bidang.nama AS bidang');
        $query = $this->db->get($this->tabel_content,$limit,$start);
        return $query->result();
    }

    function news_get_count($options)
    {
		$options['lang'] = $this->lang;
		$this->db->where($options);
        return $this->db->count_all_results($this->tabel_content);
    }

 	function news_get_data_row($file_id,$id){
		$data = array();
		$this->db->where('file_id',$file_id);
		$this->db->where('id',$id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['file_id']=$dt['file_id'];
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['published']=$dt['published'];
				$data['author']=$dt['author'];
				$data['dtime']=$dt['dtime'];
				$data['links']=$dt['links'];
				$data['hits']=$dt['hits'];
				$data['dtime_end']=$dt['dtime_end'];
				$data['published']=$dt['published'];
				$data['title_content_'.$dt['lang']]=$dt['title_content'];
				$data['headline_'.$dt['lang']]=$dt['headline'];
				$data['content_'.$dt['lang']]=$dt['content'];
			}
		}

		$query->free_result();    
		return $data;
	}

 	function news_get_data_last_id($file_id){
		$this->db->select_max('id');
		$this->db->where('file_id',$file_id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			$id = $data['id']+1;
		}else{
			$id = 1;
		}

		$query->free_result();    
		return $id;
	}

    function news_update_entry($file_id,$id=0)
    {
	    $lang = $this->get_lang();
		$new_id=$this->news_get_data_last_id($file_id);
		foreach($lang as $row){
			$title_content = 'title_content_'.$row['kode'];
			$headline = 'headline_'.$row['kode'];
			$content = 'content_'.$row['kode'];

			$data['title_content']=$this->input->post($title_content);
			$data['published']=intval($this->input->post('published'));
			$data['dtime']=time();
			$data['headline']=$this->input->post($headline);
			$data['content']=$this->input->post($content);

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}else{
				$data['file_id']=$file_id;
				$data['id']=$new_id;
				$data['lang']=$row['kode'];
				$data['author']=$this->session->userdata('username');
				$data['hits']=0;
				$this->db->insert($this->tabel_content, $data);
			}

		}
		return true;
    }

	function news_delete_entry($file_id,$id)
	{
		$this->db->where('id' , $id);
		$this->db->where('file_id' , $file_id);

		return $this->db->delete($this->tabel_content);
	}


    function gallery_get_data($options,$start=0,$limit=9999)
    {
		$options['lang'] = $this->lang;
		$this->db->where($options);
 		$this->db->select('app_files_contents.*,prm_bidang.nama AS bidang');
        $query = $this->db->get($this->tabel_content,$limit,$start);
        return $query->result();
    }

    function gallery_get_count($options)
    {
		$options['lang'] = $this->lang;
		$this->db->where($options);
        return $this->db->count_all_results($this->tabel_content);
    }

 	function gallery_get_data_row($file_id,$id){
		$data = array();
		$this->db->where('file_id',$file_id);
		$this->db->where('id',$id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['file_id']=$dt['file_id'];
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['published']=$dt['published'];
				$data['author']=$dt['author'];
				$data['dtime']=$dt['dtime'];
				$data['links']=$dt['links'];
				$data['hits']=$dt['hits'];
				$data['dtime_end']=$dt['dtime_end'];
				$data['published']=$dt['published'];
				$data['title_content_'.$dt['lang']]=$dt['title_content'];
				$data['headline_'.$dt['lang']]=$dt['headline'];
				$data['content_'.$dt['lang']]=$dt['content'];
			}
		}

		$query->free_result();    
		return $data;
	}

 	function gallery_get_data_last_id($file_id){
		$this->db->select_max('id');
		$this->db->where('file_id',$file_id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			$id = $data['id']+1;
		}else{
			$id = 1;
		}

		$query->free_result();    
		return $id;
	}

    function gallery_update_entry($file_id,$id=0)
    {
	    $lang = $this->get_lang();
		$new_id=$this->gallery_get_data_last_id($file_id);
		foreach($lang as $row){
			$title_content = 'title_content_'.$row['kode'];
			$headline = 'headline_'.$row['kode'];
			$content = 'content_'.$row['kode'];

			$data['title_content']=$this->input->post($title_content);
			$data['published']=intval($this->input->post('published'));
			$data['dtime']=time();
			$data['headline']=$this->input->post($headline);
			//$data['links']=$this->input->post('links');

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}else{
				$data['file_id']=$file_id;
				$data['id']=$new_id;
				$data['lang']=$row['kode'];
				$data['author']=$this->session->userdata('username');
				$data['content']="";
				$data['hits']=0;
				$this->db->insert($this->tabel_content, $data);
			}

		}
		return $new_id;
    }

    function gallery_news_links($file_id, $id, $upload_data)
    {
	    $lang = $this->get_lang();
		foreach($lang as $row){
			$data['dtime']=time();

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$path = base_url()."media/images/news/".$file_id."/";
				$data['links']=$path.$upload_data['orig_name'];

				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}

		}
		return true;
    }

    function gallery_update_links($file_id, $id, $upload_data)
    {
	    $lang = $this->get_lang();
		foreach($lang as $row){
			$data['dtime']=time();

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$path = base_url()."media/images/gallery/".$file_id."/";
				$data['links']=$path.$upload_data['orig_name'];

				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}

		}
		return true;
    }

    function video_update_links($file_id, $id, $upload_data)
    {
	    $lang = $this->get_lang();
		foreach($lang as $row){
			$data['dtime']=time();

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$path = base_url()."media/images/video/".$file_id."/";
				$data['links']=$path.$upload_data['orig_name'];

				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}

		}
		return true;
    }

    function download_update_links($file_id, $id, $upload_data)
    {
	    $lang = $this->get_lang();
		foreach($lang as $row){
			$data['dtime']=time();

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$path = base_url()."media/files/download/".$file_id."/";
				$data['links']=$path.$upload_data['orig_name'];

				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}

		}
		return true;
    }

	function gallery_delete_entry($file_id,$id)
	{
		$this->db->where('id' , $id);
		$this->db->where('file_id' , $file_id);

		return $this->db->delete($this->tabel_content);
	}


    function event_get_data($options,$start=0,$limit=9999)
    {
		$options['lang'] = $this->lang;

		$this->db->order_by('dtime','DESC');
		$this->db->where($options);
 		$this->db->select('app_files_contents.*,prm_bidang.nama AS bidang');
        $query = $this->db->get($this->tabel_content,$limit,$start);
        return $query->result();
    }

    function event_get_count($options)
    {
		$options['lang'] = $this->lang;
		$this->db->where($options);
        return $this->db->count_all_results($this->tabel_content);
    }

 	function event_get_data_row($file_id,$id){
		$data = array();
		$this->db->where('file_id',$file_id);
		$this->db->where('id',$id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['file_id']=$dt['file_id'];
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['published']=$dt['published'];
				$data['author']=$dt['author'];
				$data['dtime']=$dt['dtime'];
				$data['links']=$dt['links'];
				$data['hits']=$dt['hits'];
				$data['dtime_end']=$dt['dtime_end'];
				$data['published']=$dt['published'];
				$data['title_content_'.$dt['lang']]=$dt['title_content'];
				$data['headline_'.$dt['lang']]=$dt['headline'];
				$data['content_'.$dt['lang']]=$dt['content'];
			}
		}

		$query->free_result();    
		return $data;
	}

 	function event_get_data_last_id($file_id){
		$this->db->select_max('id');
		$this->db->where('file_id',$file_id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			$id = $data['id']+1;
		}else{
			$id = 1;
		}

		$query->free_result();    
		return $id;
	}

   function event_add_entry($file_id)
    {
		$data['file_id']=$file_id;
		$data['id']=$this->event_get_data_last_id($file_id);
		$data['title_content']=$this->input->post('title_content');
		$data['published']=$this->input->post('published');
		$data['author']=$this->session->userdata('username');
		$dtime=explode("/",$this->input->post('dtime'));
		$data['dtime']=mktime(0,0,0,$dtime[0],$dtime[1],$dtime[2]);
		$dtime_end=explode("/",$this->input->post('dtime_end'));
		$data['dtime_end']=mktime(0,0,0,$dtime_end[0],$dtime_end[1],$dtime_end[2]);
		$data['headline']=$this->input->post('headline');
		$data['content']=$this->input->post('content');
		$data['links']="";
		$data['hits']=0;

        return $this->db->insert($this->tabel_content, $data);
    }

    function event_update_entry($file_id,$id=0)
    {
	    $lang = $this->get_lang();
		$new_id=$this->event_get_data_last_id($file_id);
		foreach($lang as $row){
			$title_content = 'title_content_'.$row['kode'];
			$headline = 'headline_'.$row['kode'];
			$content = 'content_'.$row['kode'];

			$data['title_content']=$this->input->post($title_content);
			$data['published']=intval($this->input->post('published'));

			$dtimes=explode(" - ",$this->input->post('dtime'));

			$dtime=explode("/",$dtimes[0]);
			$data['dtime']=mktime(0,0,0,$dtime[1],$dtime[0],$dtime[2]);
			$dtime_end=explode("/",$dtimes[1]);
			$data['dtime_end']=mktime(0,0,0,$dtime_end[1],$dtime_end[0],$dtime_end[2]);

			$data['headline']=$this->input->post($headline);
			$data['content']=$this->input->post($content);

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}else{
				$data['file_id']=$file_id;
				$data['id']=$new_id;
				$data['lang']=$row['kode'];
				$data['author']=$this->session->userdata('username');
				$data['links']="";
				$data['hits']=0;
				$this->db->insert($this->tabel_content, $data);
			}

		}
		return true;
    }

	function event_delete_entry($file_id,$id)
	{
		$this->db->where('id' , $id);
		$this->db->where('file_id' , $file_id);

		return $this->db->delete($this->tabel_content);
	}

    function video_get_data($options,$start=0,$limit=9999)
    {
		$options['lang'] = $this->lang;
		$this->db->order_by('dtime','DESC');
		$this->db->where($options);
 		$this->db->select('app_files_contents.*,prm_bidang.nama AS bidang');
        $query = $this->db->get($this->tabel_content,$limit,$start);
        return $query->result();
    }

    function video_get_count($options)
    {
		$options['lang'] = $this->lang;
		$this->db->where($options);
        return $this->db->count_all_results($this->tabel_content);
    }

 	function video_get_data_row($file_id,$id){
		$data = array();
		$this->db->where('file_id',$file_id);
		$this->db->where('id',$id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['file_id']=$dt['file_id'];
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['published']=$dt['published'];
				$data['author']=$dt['author'];
				$data['dtime']=$dt['dtime'];
				$data['links']=$dt['links'];
				$data['hits']=$dt['hits'];
				$data['dtime_end']=$dt['dtime_end'];
				$data['published']=$dt['published'];
				$data['title_content_'.$dt['lang']]=$dt['title_content'];
				$data['headline_'.$dt['lang']]=$dt['headline'];
				$data['content']=$dt['content'];
			}
		}

		$query->free_result();    
		return $data;
	}

 	function video_get_data_last_id($file_id){
		$this->db->select_max('id');
		$this->db->where('file_id',$file_id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			$id = $data['id']+1;
		}else{
			$id = 1;
		}

		$query->free_result();    
		return $id;
	}

    function video_update_entry($file_id,$id=0)
    {
	    $lang = $this->get_lang();
		$new_id=$this->video_get_data_last_id($file_id);
		foreach($lang as $row){
			$title_content = 'title_content_'.$row['kode'];
			$headline = 'headline_'.$row['kode'];

			$data['title_content']=$this->input->post($title_content);
			$data['published']=intval($this->input->post('published'));
			$data['dtime']=time();
			$data['headline']=$this->input->post($headline);
			$data['content']=$this->input->post('content');
 			//$data['links']=$this->input->post('links');

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}else{
				$data['file_id']=$file_id;
				$data['id']=$new_id;
				$data['lang']=$row['kode'];
				$data['author']=$this->session->userdata('username');
				$data['hits']=0;
				$this->db->insert($this->tabel_content, $data);
			}

		}
		return $new_id;
    }

	function video_delete_entry($file_id,$id)
	{
		$this->db->where('id' , $id);
		$this->db->where('file_id' , $file_id);

		return $this->db->delete($this->tabel_content);
	}

    function download_get_data($options,$start=0,$limit=9999)
    {
		$options['lang'] = $this->lang;
		$this->db->where($options);
 		$this->db->select('app_files_contents.*,prm_bidang.nama AS bidang');
        $query = $this->db->get($this->tabel_content,$limit,$start);
        return $query->result();
    }

    function download_get_count($options)
    {
		$options['lang'] = $this->lang;
		$this->db->where($options);
        return $this->db->count_all_results($this->tabel_content);
    }

 	function download_get_data_row($file_id,$id){
		$data = array();
		$this->db->where('file_id',$file_id);
		$this->db->where('id',$id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['file_id']=$dt['file_id'];
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['published']=$dt['published'];
				$data['author']=$dt['author'];
				$data['dtime']=$dt['dtime'];
				$data['links']=$dt['links'];
				$data['hits']=$dt['hits'];
				$data['dtime_end']=$dt['dtime_end'];
				$data['published']=$dt['published'];
				$data['title_content_'.$dt['lang']]=$dt['title_content'];
				$data['headline_'.$dt['lang']]=$dt['headline'];
				$data['content']=$dt['content'];
			}
		}

		$query->free_result();    
		return $data;
	}

 	function download_get_data_last_id($file_id){
		$this->db->select_max('id');
		$this->db->where('file_id',$file_id);
		$query = $this->db->get($this->tabel_content);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			$id = $data['id']+1;
		}else{
			$id = 1;
		}

		$query->free_result();    
		return $id;
	}

   function download_add_entry($file_id)
    {
		$data['file_id']=$file_id;
		$data['id']=$this->download_get_data_last_id($file_id);
		$data['title_content']=$this->input->post('title_content');
		$data['published']=$this->input->post('published');
		$data['author']=$this->session->userdata('username');
		$data['dtime']=time();
		$data['headline']=$this->input->post('headline');
		$data['content']="";
		$data['links']=$this->input->post('links');
		$data['hits']=0;

        return $this->db->insert($this->tabel_content, $data);
    }

    function download_update_entry($file_id,$id=0)
    {
	    $lang = $this->get_lang();
		$new_id=$this->news_get_data_last_id($file_id);
		foreach($lang as $row){
			$title_content = 'title_content_'.$row['kode'];
			$headline = 'headline_'.$row['kode'];

			$data['title_content']=$this->input->post($title_content);
			$data['published']=intval($this->input->post('published'));
			$data['dtime']=time();
			$data['headline']=$this->input->post($headline);
			//$data['links']=$this->input->post('links');

			$options = array('file_id' => $file_id,'id'=>$id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel_content,$options);
			if ($query->num_rows()> 0){
				$this->db->update($this->tabel_content, $data, array('file_id' => $file_id,'id' => $id,'lang'=>$row['kode']));
			}else{
				$data['file_id']=$file_id;
				$data['id']=$new_id;
				$data['lang']=$row['kode'];
				$data['author']=$this->session->userdata('username');
				$data['content']="";
				$data['hits']=0;
				$this->db->insert($this->tabel_content, $data);
			}

		}
		return $new_id;
    }

	function download_delete_entry($file_id,$id)
	{
		$this->db->where('id' , $id);
		$this->db->where('file_id' , $file_id);

		return $this->db->delete($this->tabel_content);
	}

}