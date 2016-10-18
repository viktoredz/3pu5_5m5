<?php
class sertifikat_model extends CI_Model {

    var $tabel    = 'disbun_permohonan';

    function __construct() {
        parent::__construct();
    }
    
	function get_all_permohonan() {
		$this->db->where_in('disbun_permohonan.status','sertifikat');
		if ($this->session->userdata('level') != 'administrator'){
			$this->db->where('disbun_permohonan.username',$this->session->userdata('username'));
		}
		$this->db->order_by('kode_permohonan','desc');
		$this->db->join("app_users_profile","app_users_profile.username=disbun_permohonan.username","inner");
		$query = $this->db->get('disbun_permohonan');
		return $query->result();
	}

	function get_sertifikat($kode_permohonan,$kode_komoditi,$kode_varietas) {
		$data = array();
		$this->db->where_in('kode_permohonan',$kode_permohonan);
		$this->db->where_in('kode_komoditi',$kode_komoditi);
		$this->db->where_in('kode_varietas',$kode_varietas);
		$query = $this->db->get('disbun_sertifikat');
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			if ($data['tgl_berlaku'] == null) {
				$data['tgl_berlaku'] = "-";
			}
			if ($data['tgl_berakhir'] == null) {
				$data['tgl_berakhir'] = "-";
			}
		}else{
			$data['nomor_sertifikat'] = "-";
			$data['tgl_berlaku'] = "#";
			$data['tgl_berakhir'] = "#";
			$data['status'] = "0";
		}

		return $data;
	}

	function get_sertifikat_detail($kode_permohonan="",$kode_varietas=0,$kode_komoditi=0) {
		$this->db->where('kode_permohonan',$kode_permohonan);
		$this->db->where('kode_komoditi',$kode_komoditi);
		$this->db->where('kode_varietas',$kode_varietas);
		$query = $this->db->get('disbun_sertifikat');
		return $query->row();
	}

	function get_nomor_sertifikat() {
		$this->db->like('tgl_berlaku',date("Y"),'after');
		$this->db->where('status','1');
		$query = $this->db->get('disbun_sertifikat');
		return "525/".str_pad($query->num_rows()+1, 3, "0", STR_PAD_LEFT)."/BP2MB/".date("Y");
	}

	function update_sertifikat($kode_permohonan="",$kode_varietas=0,$kode_komoditi=0) {
		if ($this->input->post('tgl_berlaku')!="") {
			$data['tgl_berlaku'] = $this->input->post('tgl_berlaku');
		}
		if ($this->input->post('tgl_berakhir')!="") {
			$data['tgl_berakhir'] = $this->input->post('tgl_berakhir');
		}
		$data['nomor_sertifikat'] = $this->input->post('nomor_sertifikat');
		if ($this->input->post('status')!=null) {
			$data['status'] = $this->input->post('status');
		} else {
			$data['status'] = 0;
		}
			$data['preview'] = "";
		$this->db->where('kode_permohonan',$kode_permohonan);
		$this->db->where('kode_komoditi',$kode_komoditi);
		$this->db->where('kode_varietas',$kode_varietas);
		$query = $this->db->get('disbun_sertifikat');
		if ($query->num_rows() > 0) {
			$this->db->where('kode_permohonan',$kode_permohonan);
			$this->db->where('kode_komoditi',$kode_komoditi);
			$this->db->where('kode_varietas',$kode_varietas);
			if ($this->db->update('disbun_sertifikat',$data)) {
				if ($data['status'] == 1) return 2;
				else return 1;
			} else {
				return 0;
			}
		} else {
			$data['kode_permohonan'] = $kode_permohonan;
			$data['kode_varietas'] = $kode_varietas;
			$data['kode_komoditi'] = $kode_komoditi;
			if ($this->db->insert('disbun_sertifikat',$data)) {
				if ($data['status'] == 1) return 2;
				else return 1;
			} else {
				return 0;
			}
		}
	}

	function save_sertifikat($kode_permohonan="",$kode_varietas=0,$kode_komoditi=0) {

		$data['preview'] = $this->input->post('preview');
		$this->db->where('kode_permohonan',$kode_permohonan);
		$this->db->where('kode_komoditi',$kode_komoditi);
		$this->db->where('kode_varietas',$kode_varietas);
		$query = $this->db->get('disbun_sertifikat');
		if ($query->num_rows() > 0) {
			$this->db->where('kode_permohonan',$kode_permohonan);
			$this->db->where('kode_komoditi',$kode_komoditi);
			$this->db->where('kode_varietas',$kode_varietas);
			return $this->db->update('disbun_sertifikat',$data);
		} else {
			return false;
		}
	}

	function get_sertifikat_template($kode){
		$data = array();
		$options = array('kode_sertifikat' => $kode);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

}