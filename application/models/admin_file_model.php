<?php
class Admin_file_model extends CI_Model {

    var $tabel    = 'app_files';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
    function get_count($options)
    {
		if(count($options)<1) $options = array('id_theme' => $this->session->userdata('id_theme'));
		$this->db->where($options);
		$this->db->where(array('lang' => $this->lang));
        return $this->db->count_all_results($this->tabel);
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('filename','asc');
		$this->db->where(array('lang' => $this->lang));
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
		$options = array('id' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			foreach($query->result_array() as $key=>$dt){
				$data['id']=$dt['id'];
				$data['lang']=$dt['lang'];
				$data['module']=$dt['module'];
				$data['id_theme']=$dt['id_theme'];
				$data['filename_'.$dt['lang']]=$dt['filename'];
			}
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

   function insert_entry()
    {
	    $lang = $this->get_lang();
		foreach($lang as $row){
			$filename = 'filename_'.$row['kode'];
			$data['lang']=$row['kode'];
			$data['filename']=$this->input->post($filename);
			$data['module']=$this->input->post('module');
			$data['id_theme']=$this->input->post('id_theme');
			if(isset($id)) $data['id']=$id;

			$this->db->insert($this->tabel, $data);
			$id= mysql_insert_id();
		}
		return true;
    }

    function update_entry($id)
    {
	    $lang = $this->get_lang();
		foreach($lang as $row){
			$filename = 'filename_'.$row['kode'];
			$data['filename']=$this->input->post($filename);
			$data['module']=$this->input->post('module');
			$data['id_theme']=$this->input->post('id_theme');

			$options = array('id' => $id,'lang'=>$row['kode']);
			$query = $this->db->get_where($this->tabel,$options);
			if ($query->num_rows()> 0){
				$this->db->update($this->tabel, $data, array('id' => $id,'lang'=>$row['kode']));
			}else{
				$data['lang']=$row['kode'];
				$data['id']=$id;
				$this->db->insert($this->tabel, $data);
			}

		}
		return true;
    }

	function delete_entry($id)
	{
		$this->db->where(array('id' => $id));

		return $this->db->delete($this->tabel);
	}
}