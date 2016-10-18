<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Location_model extends CI_Model {

	var $data = array();

	function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
	}

	function select_kantor($id,$id_kantor)
	{
		$sql = "SELECT a.*,b.nama_kota, c.nama_propinsi FROM mas_industri_kantor AS a, mas_kota AS b, mas_propinsi AS c WHERE a.kotakab=b.id_kota AND a.propinsi=c.id_propinsi AND a.id_industri='".$id."'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$data=array();
		$data['kantor']="<option value=0'>-</option>";
		foreach($result as $x=>$y){
			$data['kantor'].="<option value='".$y['id_kantor']."' ".($id_kantor==$y['id_kantor'] ? "selected":"").">".$y['id_kantor'].". ".$y['alamat_kantor']." - ".$y['nama_kota'].", ".$y['nama_propinsi']."</option>";
		}
		
		return $data;
	}

	function select_plant($id,$id_plant)
	{
		$sql = "SELECT a.*,b.nama_kota, c.nama_propinsi FROM mas_industri_plant AS a, mas_kota AS b, mas_propinsi AS c WHERE a.kotakab=b.id_kota AND a.propinsi=c.id_propinsi AND a.id_industri='".$id."'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$data=array();
		$data['plant']="<option value=0'>-</option>";
		foreach($result as $x=>$y){
			$data['plant'].="<option value='".$y['id_plant']."' ".($id_plant==$y['id_plant'] ? "selected":"").">".$y['id_plant'].". ".$y['alamat_pabrik']." - ".$y['nama_kota'].", ".$y['nama_propinsi']."</option>";
		}
		
		return $data;
	}

	function select_plant_jenis($id,$id_plant,$id_jenis,$jenis_sertifikasi="")
	{
		$sql = "SELECT a.*,b.nama_jenis, b.nama_jenis2 FROM mas_industri_plant_jenis AS a, mas_jenis AS b WHERE a.id_jenis=b.id_jenis AND a.id_industri='".$id."' AND a.id_plant='".$id_plant."'";
		if($jenis_sertifikasi!="" && $jenis_sertifikasi=="CPOTB") {
			$sql.=" AND b.id_jenis>2";
		}
		elseif($jenis_sertifikasi!="" && $jenis_sertifikasi=="CPKB") {
			$sql.=" AND b.id_jenis<=2";
		}
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$data=array();
		$data['plant']="<option value=0'>-</option>";
		foreach($result as $x=>$y){
			$data['plant'].="<option value='".$y['id_jenis']."' ".($id_jenis==$y['id_jenis'] ? "selected":"").">".$y['nama_jenis']." - ".$y['nama_jenis2']."</option>";
		}
		
		return $data;
	}

	function select_kotakab($propinsi,$kotakab)
	{
		$sql = "SELECT * FROM mas_kota WHERE id_kota LIKE '".$propinsi."%'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$data=array();
		$data['kotakab']="<option value=0'>-</option>";
		foreach($result as $x=>$y){
			$data['kotakab'].="<option value='".$y['id_kota']."' ".($kotakab==$y['id_kota'] ? "selected":"").">".$y['id_kota']." - ".$y['nama_kota']."</option>";
		}
		
		return $data;
	}

	function select_kecamatan($kotakab,$kecamatan)
	{
		$sql = "SELECT * FROM mas_kecamatan WHERE id_kecamatan LIKE '".$kotakab."%'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$data=array();
		$data['kecamatan']="<option value=0'>-</option>";
		foreach($result as $x=>$y){
			$data['kecamatan'].="<option value='".$y['id_kecamatan']."' ".($kecamatan==$y['id_kecamatan'] ? "selected":"").">".$y['id_kecamatan']." - ".$y['nama_kecamatan']."</option>";
		}
		
		return $data;
	}

	function select_desa($kecamatan,$desa)
	{
		$sql = "SELECT * FROM mas_desa WHERE id_desa LIKE '".$kecamatan."%'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$data=array();
		$data['desa']="<option value=0'>-</option>";
		foreach($result as $x=>$y){
			$data['desa'].="<option value='".$y['id_desa']."' ".($desa==$y['id_desa'] ? "selected":"").">".$y['id_desa']." - ".$y['nama_desa']."</option>";
		}
		
		return $data;
	}

}
?>