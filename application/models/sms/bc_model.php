<?php
class Bc_model extends CI_Model {

    var $tabel    = 'sms_bc';
	var $lang	  = 'ina';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->select("sms_bc.*,sms_tipe.nama as tipe,count(sms_bc_tujuan.nomor) as jml");
	    $this->db->join('sms_tipe','sms_tipe.id_tipe=sms_bc.id_sms_tipe','inner');
	    $this->db->join('sms_bc_tujuan','sms_bc_tujuan.id_sms_bc=sms_bc.id_bc','left');
	    $this->db->group_by('sms_bc.id_bc');
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
	
    }

    function get_penerima($id,$start=0,$limit=999999,$options=array())
    {
		$this->db->select("sms_pbk.*,sms_grup.nama as grup");
		$this->db->where("id_sms_bc",$id);
		$this->db->join("sms_pbk","sms_pbk.nomor=sms_bc_tujuan.nomor");
		$this->db->join("sms_grup","sms_grup.id_grup=sms_pbk.id_sms_grup");
	    $query = $this->db->get("sms_bc_tujuan",$limit,$start);
    	return $query->result();
    }

    function get_pbk($id,$start=0,$limit=999999,$options=array())
    {
		$this->db->where("nomor NOT IN (SELECT nomor FROM sms_bc_tujuan WHERE id_sms_bc=".$id.")");
	    $query = $this->db->get("sms_pbk",$limit,$start);
    	return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$this->db->where("id_bc",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

 	function get_data_ID($id){
		$data = array();
		$this->db->where("id_bc",$id);
		$query = $this->db->get($this->tabel)->row_array();

		if(!empty($query)){
			if($query['status']=="baru"){
				$update = array("status"=>"baca");
				$this->db->where("id_bc",$id);
				$this->db->update($this->tabel,$update);
			}

			return $query;
		}else{
			return $data;
		}

		$query->free_result();    
	}

	public function getSelectedData($tabel,$data)
    {
        return $this->db->get_where($tabel, array('nomor'=>$data));
    }

   function remove_number($id, $nomor){
		$data['id_sms_bc']	= $id;
		$data['nomor']		= $nomor;
		$this->db->delete('sms_bc_tujuan', $data);
   }

   function add_number($id, $nomor){
		$data['id_sms_bc']		= $id;
		$data['nomor']		= $nomor;
		$this->db->insert('sms_bc_tujuan', $data);
   }

   function insert_entry()
    {
		$data['id_sms_tipe']	= $this->input->post('id_sms_tipe');
		$data['tgl_mulai']		= date("Y-m-d",strtotime($this->input->post('tgl_mulai')));
		$data['tgl_akhir']		= date("Y-m-d",strtotime($this->input->post('tgl_akhir')));
		$data['is_loop']		= $this->input->post('is_loop');
		$data['is_mingguan']	= $this->input->post('is_mingguan');
		$data['is_bulanan']		= $this->input->post('is_bulanan');
		$data['is_harian']		= date("H:i:s",strtotime($this->input->post('is_harian')));
		$data['pesan']			= $this->input->post('pesan');
		$data['status']			= $this->input->post('status');

		if($this->db->insert($this->tabel, $data)){
			$id= mysql_insert_id();
			return $id;
		}else{
			return mysql_error();
		}
    }

    function update_entry($id_bc)
    {
		$data['id_sms_tipe']	= $this->input->post('id_sms_tipe');
		$data['tgl_mulai']		= date("Y-m-d",strtotime($this->input->post('tgl_mulai')));
		$data['tgl_akhir']		= date("Y-m-d",strtotime($this->input->post('tgl_akhir')));
		$data['is_loop']		= $this->input->post('is_loop');
		$data['is_mingguan']	= $this->input->post('is_mingguan');
		$data['is_bulanan']		= $this->input->post('is_bulanan');
		$data['is_harian']		= date("H:i:s",strtotime($this->input->post('is_harian')));
		$data['pesan']			= $this->input->post('pesan');
		$data['status']			= $this->input->post('status');

		if($this->db->update($this->tabel, $data, array("id_bc"=>$id_bc))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('id_bc',$id);

		return $this->db->delete($this->tabel);
	}

    function get_menu()
    {
	    $query = $this->db->get('sms_bc_menu');
    	return $query->result();
	
    }}