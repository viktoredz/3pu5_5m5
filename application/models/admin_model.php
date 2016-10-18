<?php
class Admin_model extends CI_Model {

    var $tabel     = 'app_theme';

    function __construct() {
        parent::__construct();
    }
    
 	function get_theme($id){
		$data = array();
		$options = array('id_theme' => $id);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}


	function get_inv_barang(){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		$query = $this->db->query("SELECT SUM(jml) AS jml, SUM(nilai) AS nilai FROM ((SELECT COUNT(id_inventaris_barang) AS jml,SUM(harga) AS nilai FROM inv_inventaris_barang WHERE id_pengadaan=0 and code_cl_phc =".'"'.$pusk.'"'.")UNION(SELECT COUNT(id_inventaris_barang) AS jml,SUM(harga) AS nilai FROM inv_inventaris_barang INNER JOIN inv_pengadaan ON inv_pengadaan.id_pengadaan=inv_inventaris_barang.id_pengadaan and inv_inventaris_barang.code_cl_phc =".'"'.$pusk.'"'.")) AS aset
			");

		return $query->result();
	}

	function get_inv_barang1(){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		$query = $this->db->query("SELECT COUNT(id_mst_inv_ruangan) as jml from mst_inv_ruangan where code_cl_phc =".'"'.$pusk.'"'."");

		return $query->result();
	}

	function get_jum_aset(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="and inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE (pilihan_keadaan_barang = 'B' || pilihan_keadaan_barang = 'KB') $dbwhere GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}

	function get_nilai_aset(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="and inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE (pilihan_keadaan_barang = 'B' || pilihan_keadaan_barang = 'KB') $dbwhere GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}

	function get_jum_aset1(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="and inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang  AND inv_inventaris_distribusi.status=1
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RR'  
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}

	function get_nilai_aset1(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="and inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang  AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RR'
		$dbwhere
		 GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}


	function get_jum_aset2(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="and inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RB'  
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}

	function get_nilai_aset2(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="and inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RB' $dbwhere GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}

	function get_jum_nilai_aset()
	{
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="where inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query = $this->db->query("SELECT cl_phc.value,id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
			INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
			LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code $dbwhere 
			GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}

	function get_jum_nilai_aset2()
	{
		$loginpuskesmas = $this->session->userdata('puskesmas');
		if (strlen($loginpuskesmas)==4) {
			$dbwhere ='';
		}else{
			$dbwhere ="where inv_inventaris_distribusi.id_cl_phc=".'"'.'P'.$loginpuskesmas.'"'."";
		}
		$query = $this->db->query("SELECT cl_phc.value,id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
			INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
			LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code   $dbwhere 
			GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		return $query->result();
	}

}