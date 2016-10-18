<?php
class users_model extends CI_Model {

    var $tabel    = 'app_users_list';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
    function get_data($start=0,$limit=999999,$options=array())
    {
		foreach($options as $x=>$y){
			if($x=="username"){
				$this->db->like($x,$y);
			}else{
				$this->db->where($x,$y);
			}
		}
		$this->db->where('app_users_profile.username <>','admin');
		$this->db->join('app_users_profile','app_users_profile.username=app_users_list.username', "inner");
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($username){
		$data = array();
		

		$this->db->select('app_users_list.*,app_users_profile.*');
		$this->db->where('app_users_list.username', $username);
		$this->db->join('app_users_profile','app_users_profile.username=app_users_list.username', "inner");
		
		$query = $this->db->get("app_users_list");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}
		

		$query->free_result();    
		return $data;
	}
	function get_edit_row($id_log){
		$data = array();
 		$this->db->select('app_users_profile.*, app_users_update.*');
		$this->db->where('app_users_update.id_log', $id_log);		
		$this->db->join('app_users_profile','app_users_profile.id=app_users_update.id', "inner");
		$query = $this->db->get("app_users_update");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}
 		$query->free_result();    
		return $data;
	}
	
	
    function update_entry($id_balai)
    {
		$data['nama_balai']=$this->input->post('nama_balai');
		$data['alamat']=$this->input->post('alamat');
		$data['propinsi']=$this->input->post('propinsi');
		$data['kd_pos']=$this->input->post('kd_pos');
		$data['telp']=$this->input->post('telp');
		$data['fax']=$this->input->post('fax');
		$data['email']=$this->input->post('email');
		$data['nip_kepala']=$this->input->post('nip_kepala');
		$data['nama_kepala']=$this->input->post('nama_kepala');
        
		return $this->db->update($this->tabel, $data, array('id_balai' => $this->input->post('id_balai')));
    }
	
	function doapprove($id,$status)
	{
		$this->db->where(array('username' => $id));

		return $this->db->update("app_users_list",array("status_aproved"=>$status,"status_active"=>1));
	}

 	function get_trup_list($status=""){
		$data = array();
		
		if($this->session->userdata('level')=="member") $this->db->where('app_users_profile.username',$this->session->userdata('username'));

		if($status=="ok") $this->db->where('disbun_trup.status ="ok" or disbun_trup.status="aktif"');
		$this->db->select('disbun_trup.*,app_users_profile.*,disbun_trup.jenis AS jenistrup,disbun_trup.status AS statustrup');
		$this->db->join('app_users_profile','app_users_profile.username=disbun_trup.username', "inner");
		$this->db->order_by('disbun_trup.kode_trup','desc');
		
		$query = $this->db->get("disbun_trup");
        return $query->result();
	}

 	function get_trup($kode_trup){
		$data = array();
		

		if($this->session->userdata('level')=="member") $this->db->where('app_users_profile.username',$this->session->userdata('username'));
		$this->db->select('disbun_trup.*,app_users_profile.*,disbun_trup.jenis AS jenistrup,disbun_trup.status AS statustrup,disbun_trup.nama AS namapemohon');
		$this->db->where('disbun_trup.kode_trup',$kode_trup);
		$this->db->join('app_users_profile','app_users_profile.username=disbun_trup.username', "inner");
		
		$query = $this->db->get("disbun_trup");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			$data['jenistrup'] = ucwords($data['jenistrup'])." Benih";
			if ($data['kerjasama'] == "tidakada") {
				$data['kerjasama'] = "Tidak ada";
			} else {
				$data['kerjasama'] = "Ada";
			}
			// $data['kerjasama'] = ucwords($data['kerjasama']);
			$tgl_tmp = explode("-", $data['tgl_daftar']);
			$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
			$data['tgl_daftar'] = $tgl_tmp[2]." ".$BulanIndo[(int)$tgl_tmp[1]-1]." ".$tgl_tmp[0];

		}

		$query->free_result();    
		return $data;
	}

	function get_trup_draft($kode_trup){
		
		$this->db->where('disbun_trup.kode_trup',$kode_trup);
		$query = $this->db->get("disbun_trup");
		return $query->row();
	}

	function get_trup_rencana_detail($kode_trup="",$kode_varietas="",$kode_komoditi=""){
		
		$this->db->where('kode_trup',$kode_trup);
		$this->db->where('kode_varietas',$kode_varietas);
		$this->db->where('kode_komoditi',$kode_komoditi);
		$query = $this->db->get("disbun_trup_rencana");
		return $query->row();
	}

	function get_trup_eksisting_detail($kode_trup="",$kode_varietas="",$kode_komoditi=""){
		
		$this->db->where('kode_trup',$kode_trup);
		$this->db->where('kode_varietas',$kode_varietas);
		$this->db->where('kode_komoditi',$kode_komoditi);
		$query = $this->db->get("disbun_trup_eksisting");
		return $query->row();
	}

	function trup_add(){
		$tmp = explode("/",$this->input->post('tgl_daftar'));
	
		$data['kode_trup']=$this->createTRUP();
		$data['username']=$this->session->userdata('username');
		$data['tgl_daftar']	= $tmp[2]."-".$tmp[1]."-".$tmp[0];
		$data['jenis']	= $this->input->post('jenis');
		$data['nama']	= $this->input->post('nama');
		$data['penanggungjawab']	= $this->input->post('penanggungjawab');
		$data['ktp']	= $this->input->post('ktp');
		$data['pengalaman']	= $this->input->post('pengalaman');
		$data['modal_asal']	= $this->input->post('modal_asal');
		$data['modal_nilai']	= $this->input->post('modal_nilai');
		$data['kerjasama']	= $this->input->post('kerjasama');
		$data['id_propinsi']	= $this->input->post('propinsi');
		$data['id_kota']	= $this->input->post('kota');
		$data['id_kecamatan']	= $this->input->post('kecamatan');
		$data['id_desa']	= $this->input->post('desa');

		$data['status'] = "draft";
		if($this->db->insert("disbun_trup", $data)){
			return $data['kode_trup'];
		}else{
			return false;
		}
	}

	function trup_update_draft($kode_trup=""){
		$tmp = explode("/",$this->input->post('tgl_daftar'));
	
		$data['tgl_daftar']	= $tmp[2]."-".$tmp[1]."-".$tmp[0];
		$data['jenis']	= $this->input->post('jenis');
		$data['nama']	= $this->input->post('nama');
		$data['penanggungjawab']	= $this->input->post('penanggungjawab');
		$data['ktp']	= $this->input->post('ktp');
		$data['pengalaman']	= $this->input->post('pengalaman');
		$data['modal_asal']	= $this->input->post('modal_asal');
		$data['modal_nilai']	= $this->input->post('modal_nilai');
		$data['kerjasama']	= $this->input->post('kerjasama');
		$data['id_propinsi']	= $this->input->post('id_propinsi');
		$data['id_kota']	= $this->input->post('id_kota');
		$data['id_kecamatan']	= $this->input->post('id_kecamatan');
		$data['id_desa']	= $this->input->post('id_desa');
		$this->db->where('kode_trup',$kode_trup);
		if($this->db->update("disbun_trup", $data)){
			return true;
		}else{
			return false;
		}
	}

	function trup_add_rencana(){
		$data['kode_trup']	= $this->input->post('kode_trup');
		$data['kode_komoditi']	= $this->input->post('komoditi');
		$data['kode_varietas']	= $this->input->post('varietas');
		$data['kode_satuan']	= $this->input->post('satuan');
		$data['jml']	= $this->input->post('jml');
		$data['asal']	= $this->input->post('asal');
		$data['umur']	= $this->input->post('umur');
		$data['penyaluran']	= $this->input->post('penyaluran');

		if($this->db->insert("disbun_trup_rencana", $data)){
			return $data['kode_trup'];
		}else{
			return false;
		}
	}

	function trup_update_rencana($kode_trup="",$kode_varietas="",$kode_komoditi="") {
		$data['jml']	= $this->input->post('jml');
		$data['asal']	= $this->input->post('asal');
		$data['umur']	= $this->input->post('umur');
		$data['penyaluran']	= $this->input->post('penyaluran');
		$data['kode_satuan']	= $this->input->post('satuan');

		$this->db->where('kode_trup',$kode_trup);
		$this->db->where('kode_varietas',$kode_varietas);
		$this->db->where('kode_komoditi',$kode_komoditi);
		if($this->db->update('disbun_trup_rencana', $data)){
			return true;
		}else{
			return false;
		}
	}

	function trup_update_eksisting($kode_trup="",$kode_varietas="",$kode_komoditi="") {
		$data['kode_satuan']	= $this->input->post('satuan');
		$data['jml']	= $this->input->post('jml');
		$data['asal']	= $this->input->post('asal');
		$data['umur']	= $this->input->post('umur');
		$data['penyaluran']	= $this->input->post('penyaluran');

		$this->db->where('kode_trup',$kode_trup);
		$this->db->where('kode_varietas',$kode_varietas);
		$this->db->where('kode_komoditi',$kode_komoditi);
		if($this->db->update('disbun_trup_eksisting', $data)){
			return true;
		}else{
			return false;
		}
	}

	function get_trup_rencana($kode_trup="") {
		$this->db->select('disbun_trup_rencana.*,prm_komoditi.nama AS komoditi,prm_varietas.nama AS varietas');
		$this->db->join('prm_komoditi','prm_komoditi.kode_komoditi=disbun_trup_rencana.kode_komoditi', "inner");
		$this->db->join('prm_varietas','prm_varietas.kode_varietas=disbun_trup_rencana.kode_varietas', "inner");
		$this->db->where('kode_trup',$kode_trup);
		$query = $this->db->get("disbun_trup_rencana");
        return $query->result();
	}

	function trup_add_eksisting(){
		$data['kode_trup']	= $this->input->post('kode_trup');
		$data['kode_komoditi']	= $this->input->post('komoditi');
		$data['kode_varietas']	= $this->input->post('varietas');
		$data['kode_satuan']	= $this->input->post('satuan');
		$data['jml']	= $this->input->post('jml');
		$data['asal']	= $this->input->post('asal');
		$data['umur']	= $this->input->post('umur');
		$data['penyaluran']	= $this->input->post('penyaluran');

		if($this->db->insert("disbun_trup_eksisting", $data)){
			return $data['kode_trup'];
		}else{
			return false;
		}
	}

	

	function get_trup_eksisting($kode_trup="") {
		$this->db->select('disbun_trup_eksisting.*,prm_komoditi.nama AS komoditi,prm_varietas.nama AS varietas');
		$this->db->join('prm_komoditi','prm_komoditi.kode_komoditi=disbun_trup_eksisting.kode_komoditi', "inner");
		$this->db->join('prm_varietas','prm_varietas.kode_varietas=disbun_trup_eksisting.kode_varietas', "inner");
		$this->db->where('kode_trup',$kode_trup);
		$query = $this->db->get("disbun_trup_eksisting");
        return $query->result();
	}

     function trup_update($kode_trup)
    {
		$data['nomor_trup']=$this->input->post('nomor_trup');
		$data['pemeriksa']=$this->input->post('pemeriksa');
		$data['tgl_pemeriksaan']=$this->input->post('tgl_pemeriksaan');
		$data['tgl_aktif']=$this->input->post('tgl_aktif');
		$data['tgl_akhir']=$this->input->post('tgl_akhir');
		$data['status']=$this->input->post('statustrup');
        
		return $this->db->update("disbun_trup", $data, array('kode_trup' => $kode_trup));
    }

    function get_varietas_rencana_selected($kode_trup="") {
		$this->db->select('kode_varietas');
		$this->db->where('kode_trup',$kode_trup);
		$query = $this->db->get('disbun_trup_rencana');
		$kode_varietas = array();
		foreach ($query->result() as $row) {
			$kode_varietas[] = $row->kode_varietas;
		}
		return $kode_varietas;
	}

	function get_varietas_eksisting_selected($kode_trup="") {
		$this->db->select('kode_varietas');
		$this->db->where('kode_trup',$kode_trup);
		$query = $this->db->get('disbun_trup_eksisting');
		$kode_varietas = array();
		foreach ($query->result() as $row) {
			$kode_varietas[] = $row->kode_varietas;
		}
		return $kode_varietas;
	}

    function createTRUP(){
    	$tmp = date("Ym.");
        $this->db->select("MAX(kode_trup) as kode_trup");
        $this->db->where("kode_trup LIKE '$tmp%'");
        $query = $this->db->get('disbun_trup');
		$data = $query->row_array();

		if ($data['kode_trup'] != ""){
			$expl = explode(".",$data['kode_trup']);
			$trup = $expl[1]+1;
			$trup = str_repeat("0", (3-strlen($trup))) . $trup;

			return $tmp.$trup;
		}else{
			return $tmp."001";
		}
    }

 	function trup_delete() {
 		$kode_trup = $this->input->post('kode_trup');
		if (!empty($kode_trup)) {
			$kode_trup = $this->input->post('kode_trup');
			foreach ($kode_trup as $value) {
            	$this->db->where('kode_trup',$value);
            	$this->db->delete('disbun_trup');
            }
		}
	}

	function trup_rencana_delete() {
		$trup_rencana = $this->input->post('trup_rencana');
		if (!empty($trup_rencana)) {
			$komoditi = $this->input->post('trup_rencana');
			foreach ($komoditi as $value) {
				$tmp = explode("_", $value);
            	$this->db->where('kode_trup',$tmp[0]);
            	$this->db->where('kode_varietas',$tmp[1]);
            	$this->db->where('kode_komoditi',$tmp[2]);
            	$this->db->delete('disbun_trup_rencana');
            }
		}
	}

	function trup_eksisting_delete() {
		$trup_eksisting = $this->input->post('trup_eksisting');
		if (!empty($trup_eksisting)) {
			$komoditi = $this->input->post('trup_eksisting');
			foreach ($komoditi as $value) {
				$tmp = explode("_", $value);
            	$this->db->where('kode_trup',$tmp[0]);
            	$this->db->where('kode_varietas',$tmp[1]);
            	$this->db->where('kode_komoditi',$tmp[2]);
            	$this->db->delete('disbun_trup_eksisting');
            }
		}
	}

	function kirim_trup($id=0) {
 		$this->db->like('tgl_aktif',date("Y"),'after');
 		$query = $this->db->get('disbun_trup');
		$data['nomor_trup'] = "525/".str_pad($query->num_rows()+1, 3, "0", STR_PAD_LEFT)."/TRUP/BSPMB/".date("Y");
		$this->db->where('kode_trup',$id);
 		$data['status'] = "ok";
 		if($this->db->update('disbun_trup',$data))
 			return true;
 		else
 			return false;   	
    }

    function update_trup($kode_trup="") {
		if ($this->input->post('tgl_pemeriksaan')!="") {
			$tmp = explode("/",$this->input->post('tgl_pemeriksaan'));
			$data['tgl_pemeriksaan']	= $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
		if ($this->input->post('tgl_aktif')!="") {
			$tmp2 = explode("/",$this->input->post('tgl_aktif'));
			$data['tgl_aktif']	= $tmp2[2]."-".$tmp2[1]."-".$tmp2[0];
		}
		if ($this->input->post('tgl_akhir')!="") {
			$tmp2 = explode("/",$this->input->post('tgl_akhir'));
			$data['tgl_akhir']	= $tmp2[2]."-".$tmp2[1]."-".$tmp2[0];
		}
		$data['nomor_trup'] = $this->input->post('nomor_trup');
		$data['pemeriksa'] = $this->input->post('pemeriksa');
		$data['kesimpulan'] = $this->input->post('kesimpulan');
		$data['tenagakerja'] = $this->input->post('tenagakerja');
		$data['benih_pupuk'] = $this->input->post('benih_pupuk');
		$data['benih_dosis'] = $this->input->post('benih_dosis');
		$data['benih_pengendalian'] = $this->input->post('benih_pengendalian');
		$data['benih_penyiangan'] = $this->input->post('benih_penyiangan');
		$data['pembinaan'] = $this->input->post('pembinaan');
		$data['pelatihan'] = $this->input->post('pelatihan');
		$data['bidangusaha'] = $this->input->post('bidangusaha');

		if ($this->input->post('status')!=null) {
			$data['status'] = $this->input->post('status');
		} 
		$data['preview'] = "";

		$this->db->where('kode_trup',$kode_trup);
		
		if ($this->db->update('disbun_trup',$data)) {
				if ($data['status'] == "aktif") return 2;
				else return 1;
			} else {
				return 0;
			}

	}

	function check_varietas_komoditi_rencana($kode_trup="",$kode_varietas="",$kode_komoditi=""){
		$this->db->where('kode_trup',$kode_trup);
		$this->db->where('kode_varietas',$kode_varietas);
		$this->db->where('kode_komoditi',$kode_komoditi);
		$result = $this->db->get('disbun_trup_rencana');
		if ($result->num_rows() > 0) {
			return false;
		} else {
			return true;
		}
	}

	function check_varietas_komoditi_eksisting($kode_trup="",$kode_varietas="",$kode_komoditi=""){
		$this->db->where('kode_trup',$kode_trup);
		$this->db->where('kode_varietas',$kode_varietas);
		$this->db->where('kode_komoditi',$kode_komoditi);
		$result = $this->db->get('disbun_trup_eksisting');
		if ($result->num_rows() > 0) {
			return false;
		} else {
			return true;
		}
	}

	function save_surat($kode_trup="") {

		$data['preview'] = $this->input->post('surat');
		$this->db->where('kode_trup',$kode_trup);
		$query = $this->db->get('disbun_trup');
		if ($query->num_rows() > 0) {
			$this->db->where('kode_trup',$kode_trup);
			return $this->db->update('disbun_trup',$data);
		} else {
			return false;
		}
	}

   function create_aktifasi($id,$userid,$email){
		$id = md5(time());
		if($this->db->insert("app_users_activation",array("id"=>$id, "userid" => $userid, "email" => $email))){
			return $id;
		}else{
			return 0;
		}
	}
	

	function doaktifasi($id,$email)
	{
		$this->db->where(array('id' => $id,'email' => $email));
		$query = $this->db->get("app_users_activation");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
			$this->db->where(array('id' => $data['userid']));
			if($this->db->update("app_users_list",array("status_active"=>1, "last_active" => time()))){
				return $data['userid'];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

}