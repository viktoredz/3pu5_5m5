<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * C-R-U-D Model
 *
 * @package		 CodeIgniter
 * @subpackage 	 Model
 * @category	 Create-Retrieve-Update-Delete
 *
 */
 
class Crud extends CI_Model {

	var $data = array();
	var $returnArray = TRUE;
	var $table;
	var $fields;
	var $__numRows;
	var $__insertID;
	var $__affectedRows;
	var $id;
	var $primaryKey = 'id';

	function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
	}

	/**
	 * Load the associated database table.
	 *
	 */
	function useTable($table)
	{
		$this->table  = $table;
		$this->fields = $this->db->list_fields($table);
	}
  

	/**
	 * Returns a resultset array with specified fields from database matching given conditions.
	 */
	function findAll($conditions = NULL, $fields = '*', $order = NULL, $start = 0, $limit = NULL)
	{
		if ($conditions != NULL) {
			$this->db->where($conditions);
		}

		if ($fields != NULL) {
			$this->db->select($fields);
		}

		if ($order != NULL) {
			$this->db->orderby($order);
		}

		if ($limit != NULL) {
			$this->db->limit($limit, $start);
		}

		$query = $this->db->get($this->table);
		$this->__numRows = $query->num_rows();

		return ($this->returnArray) ? $query->result_array() : $query->result();
	}

	/**
	 * Return a single row as a resultset array with specified fields from database matching given conditions.
	 */
	function find($conditions = NULL, $fields = '*', $order = 'id ASC')
	{
		$data = $this->findAll($conditions, $fields, $order, 0, 1);

		if ($data) {
			return $data[0];
		}
		else {
			return false;
		}
	}
	
	function get_insertid(){
		return $this->__insertID;	
	}
	
	function unsetMe(){
		$this->crud->data = array();
		$this->crud->fields = null;
		$this->crud->id = null;
		$this->crud->primaryKey = null;
	}

	/**
	*  Update Method
	*/
	function save($data = null, $id = null, $pk = null, $stat = null)
	{
		if ($data)
		{
			$this->data = $data;
		}

		foreach ($this->data as $key => $value)
		{
			if (array_search($key, $this->fields) === FALSE)
			{
				unset($this->data[$key]);
			}
		}
		
		foreach ($this->data as $key => $value)
		{
			//echo $value;
			if ($value == '')
			{
				unset($this->data[$key]);
			}
		}

		if ($id != null)
		{
			$this->id = $id;
		}
		if ($pk != null)
		{
			$this->primaryKey = $pk;
		}

		$id = $this->id;

		if ($this->id !== null && $this->id !== false)
		{
			$this->db->where($this->primaryKey, $id);
			$ret = $this->db->update($this->table, $this->data);
			$this->__affectedRows = $this->db->affected_rows();
			//if($stat!=99){
			  //echo $this->id;
			  //return $this->id;
			//}
			//echo $this->db->last_query();
		}
		else
		{
			$ret = $this->db->insert($this->table,$this->data);
			$this->__affectedRows = $this->db->affected_rows();
			$this->__insertID = $this->db->insert_id();
			//if($stat!=99){
			  //echo 1;
			  //return $this->__insertID;
			//}
		}
		return $ret;
	}

	/**
	*  Update Method multiple primary key
	*/
	function save_multipk($data = null, $pk = null){
		if ($data){
			$this->data = $data;
		}
		if ($pk){
			$this->pk = $pk;
		}
		   
		$this->db->select('*');
		foreach($this->pk as $key=> $value){
			$this->db->where($key, $value);
		}
		$query = $this->db->get($this->table);
		
		foreach ($this->data as $key => $value){
			if (array_search($key, $this->fields) === FALSE){
				unset($this->data[$key]);
			}
		}
		   
		$numrows = $query->num_rows();
		
		if ($numrows>0){
			foreach($this->pk as $key=> $value){
			   $this->db->where($key, $value);
			}
			$ret = $this->db->update($this->table, $this->data);
			$this->__affectedRows = $this->db->affected_rows();
		}else{ 
			 $ret = $this->db->insert($this->table,$this->data);
			 $this->__affectedRows = $this->db->affected_rows();
			 $this->__insertID = $this->db->insert_id();
			 //return $this->__insertID;
		}
		return $ret;
	}
	
	function delete($id = null, $pk = null)
	{
		if ($id != null)
		{
			$this->id = $id;
		}
		if ($pk != null)
		{
			$this->primaryKey = $pk;
		}

		$id = $this->id;

		if ($this->id !== null && $this->id !== false)
		{
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->table);
        $this->__affectedRows = $this->db->affected_rows();
		  //echo $this->id;
        return $this->id;
		}
	}
	
	/**
	*  Get row affected from update
	*/
	function getAffectedRows()
	{
		return $this->__affectedRows;
	}
	
	/**
	*  Get count data using specific condition
	*/
	function getCount($conditions = null)
	{
		$data = $this->findAll($conditions, 'COUNT(*) AS count', null, 0, 1);

		if ($data){
			return $data[0]['count'];
		} else {
			return false;
		}
	}

	function provinsi_option($id=0){
		$html ="<option value=''>-</option>";
		$sql = "select * from cl_province ORDER BY value ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($id==$row->code)
				$html .= "<option value=".$row->code." selected>".$row->value."</option>";
			else
				$html .= "<option value=".$row->code.">".$row->value."</option>";
		}
		return $html;
	}

	function kota_option($kode_provinsi="",$id=0){
		if($kode_provinsi=="") $kode_provinsi ="-";
		$html ="<option value=''>-</option>";
		$sql = "select * from mas_kota where id_kota like '".$kode_provinsi."%' ORDER BY nama_kota ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($id==$row->id_kota)
				$html .= "<option value=".$row->id_kota." selected>".$row->nama_kota."</option>";
			else
				$html .= "<option value=".$row->id_kota.">".$row->nama_kota."</option>";
		}
		return $html;
	}


	function desa_option($kode_kec, $id=""){
		$html ="<option value=''>-</option>";
		if($kode_kec=="") $kode_kec ="-";
		$sql = "select * from mas_desa where id_desa like '".$kode_kec."%' ORDER BY nama_desa ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($id==$row->id_desa)
				$html .= "<option value=".$row->id_desa." selected>".$row->nama_desa."</option>";
			else
				$html .= "<option value=".$row->id_desa.">".$row->nama_desa."</option>";
		}
		return $html;
	}

	function kecamatan_option($kode_kota, $id=""){
		$html ="<option value=''>-</option>";
		if($kode_kota=="") $kode_kota ="-";
		$sql = "select * from mas_kecamatan where id_kecamatan like '".$kode_kota."%' ORDER BY nama_kecamatan ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($id==$row->id_kecamatan)
				$html .= "<option value=".$row->id_kecamatan." selected>".$row->nama_kecamatan."</option>";
			else
				$html .= "<option value=".$row->id_kecamatan.">".$row->nama_kecamatan."</option>";
		}
		return $html;
	}
	
	function get_kota($kode_provinsi, $id=""){
		if($kode_provinsi=="") $kode_provinsi ="-";
		$sql = "select * from mas_kota where id_kota like '".$kode_provinsi."%' ORDER BY nama_kota ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			$data[$row->id_kota] = $row->nama_kota;
		}

		return $data;
	}
	
	function get_kecamatan($kode_kota, $id=""){
		if($kode_kota=="") $kode_kota ="-";
		$sql = "select * from mas_kecamatan where id_kecamatan like '".$kode_kota."%' ORDER BY nama_kecamatan ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			$data[$row->id_kecamatan] = $row->nama_kecamatan;
		}

		return $data;
	}
	
	function get_desa($kode_kec, $id=""){
		if($kode_kec=="") $kode_kec ="-";
		$sql = "select * from mas_desa where id_desa like '".$kode_kec."%' ORDER BY nama_desa ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			$data[$row->id_desa] = $row->nama_desa;
		}

		return $data;
	}
	

	function jenis_lampiran($jenis_lampiran=""){
		$html = "<select id='jenis_lampiran' name='jenis_lampiran' >";
			$html .= "<option value='RIP' ".($jenis_lampiran=='RIP' ? "selected" : "" ).">RIP Pabrik</option>";
			$html .= "<option value='Dokumentasi' ".($jenis_lampiran=='Dokumentasi' ? "selected" : "" ).">Dokumentasi CPOTB/CPKB </option>";
		return $html."</select>";
	
	}

	function jenis_sertifikat($jenis_sertifikat=""){
		$html = "<select id='jenis_sertifikat' name='jenis_sertifikat' >";
			$html .= "<option value='CPOTB' ".($jenis_sertifikat=='CPOTB' ? "selected" : "" ).">CPOTB</option>";
			$html .= "<option value='CPKB' ".($jenis_sertifikat=='CPKB' ? "selected" : "" ).">CPKB</option>";
		return $html."</select>";
	
	}

	function select_tahun($name="thn",$val="",$etc=""){
		$html = "<select id='".$name."' name='".$name."' ".$etc." style='padding:2px'>";
		$html .= "<option value=0>-</option>";
		for($i=date("Y")-10;$i<=date("Y")+10;$i++){
			if($val==$i)
				$html .= "<option value=".$i." selected>".$i."</option>";
			else
				$html .= "<option value=".$i.">".$i."</option>";
		}
		return $html."</select>";
	}
	
	function select_bulan($name="bln",$val="",$etc=""){
		$html = "<select id='".$name."' name='".$name."' ".$etc." style='padding:2px'>";
		$html .= "<option value=0>-</option>";
		for($i=1;$i<=12;$i++){
			if($val==$i)
				$html .= "<option value=".$i." selected>".$i."</option>";
			else
				$html .= "<option value=".$i.">".$i."</option>";
		}
		return $html."</select>";
	}
	
	function select_hari($name="hr",$val="",$etc=""){
		$html = "<select id='".$name."' name='".$name."' ".$etc." style='padding:2px'>";
		$html .= "<option value=0>-</option>";
		for($i=1;$i<=31;$i++){
			if($val==$i)
				$html .= "<option value=".$i." selected>".$i."</option>";
			else
				$html .= "<option value=".$i.">".$i."</option>";
		}
		return $html."</select>";
	}
	
	function select_plant($id_industri,$id=""){
		$sql = "select a.id_plant,a.alamat_pabrik,b.nama_propinsi from mas_industri_plant AS a,mas_propinsi AS b WHERE a.propinsi=b.id_propinsi AND a.id_industri='".$id_industri."'";
		$query = $this->db->query($sql);
		$html = "<select id='id_plant' name='id_plant'  style='width:300px'>
			<option>-</option>";
		foreach($query->result() as $row){
			$html .= "<option value=".$row->id_plant." ".($id==$row->id_plant ? "selected" : "" ).">".$row->alamat_pabrik.", ".$row->nama_propinsi."</option>";
		}
		return $html."</select>";
	}
	
	function select_izin($id=""){
		$sql = "select * from mas_izin ORDER BY jenis ASC, nama ASC";
		$query = $this->db->query($sql);
		$html = "<select id='id_izin' name='id_izin' >
			<option>-</option>";
		foreach($query->result() as $row){
			$html .= "<option value=".$row->id_izin." ".($id==$row->id_izin ? "selected" : "" ).">".$row->jenis.": ".$row->nama."</option>";
		}
		return $html."</select>";
	}
	
	function select_lokasi($id=""){
		$sql = "select * from mas_lokasi_industri";
		$query = $this->db->query($sql);
		$html = "<select id='lokasi' name='lokasi'>
			<option>-</option>";
		foreach($query->result() as $row){
			$html .= "<option value=".$row->id_lokasi." ".($id==$row->id_lokasi ? "selected" : "" ).">".$row->nama_lokasi."</option>";
		}
		return $html."</select>";
	}
	
	function get_lokasi_pabrik($id=""){
		$sql = "select * from mas_lokasi_industri";
		$query = $this->db->query($sql);
		$html = "<select id='lokasi' name='lokasi' >";
		foreach($query->result() as $row){
			if($id==$row->id_lokasi)
				$html .= "<option value=".$row->id_lokasi." selected>".$row->nama_lokasi."</option>";
			else
				$html .= "<option value=".$row->id_lokasi.">".$row->nama_lokasi."</option>";
		}
		return $html."</select>";
	}
	
	function get_fasilitas_pabrik($id="",$iterat=""){
		$sql = "select * from mas_fasilitas";
		$query = $this->db->query($sql);
		$html = "<select id='fasilitas$iterat' name='fasilitas$iterat' >";
		foreach($query->result() as $row){
			if($id==$row->id_fasilitas)
				$html .= "<option value=".$row->id_fasilitas." selected>".$row->nama_fasilitas."</option>";
			else
				$html .= "<option value=".$row->id_fasilitas.">".$row->nama_fasilitas."</option>";
		}
		return $html."</select>";
	}
	
	function get_sediaan_pabrik($id_jenis="",$id="",$iterat="",$attr=""){
		$sql = "select * from mas_bentuk_sediaan WHERE id_jenis='".$id_jenis."' ORDER BY nama_sediaan ASC";
		$query = $this->db->query($sql);
		$html = "<select id='sediaan$iterat' name='sediaan$iterat' $attr ><option>-</option>";
		foreach($query->result() as $row){
			$html .= "<option ".($id==$row->id_sediaan ? "selected" : "")." value=".$row->id_sediaan." >".$row->nama_sediaan."</option>";
		}
		return $html."</select>";
	}
	
	/*function get_sediaan_pabrik($id="",$iterat="",$attr=""){
		$sql = "select * from mas_bentuk_sediaan ORDER BY nama_sediaan ASC";
		$query = $this->db->query($sql);
		$html = "<select id='sediaan$iterat' name='sediaan$iterat' $attr >
			<option>-</option>";
		foreach($query->result() as $row){
			if($id==$row->id_sediaan)
				$html .= "<option id_jenis=".$row->id_jenis." value=".$row->id_sediaan." selected>".$row->nama_sediaan."</option>";
			else
				$html .= "<option id_jenis=".$row->id_jenis." value=".$row->id_sediaan.">".$row->nama_sediaan."</option>";
		}
		return $html."</select>";
	}*/
	
	function get_sediaan_pabrik2($id,$id_plant,$id_jenis,$bentuk_sediaan=0,$iterat="",$attr=""){
		$sql = "select * from mas_bentuk_sediaan WHERE id_jenis='".$id_jenis."' ORDER BY nama_sediaan ASC";
		$query = $this->db->query($sql);
		$html = "<select id='bentuk_sediaan$iterat' name='bentuk_sediaan$iterat' $attr >
			<option value=''>-</option>";
			foreach($query->result() as $row){
				if($this->cek_sediaan_pabrik($id,$id_plant,$row->id_sediaan) || $bentuk_sediaan==$row->id_sediaan){
					if($bentuk_sediaan==$row->id_sediaan)
						$html .= "<option id_jenis=".$row->id_jenis." value=".$row->id_sediaan." selected>".$row->nama_sediaan."</option>";
					else
						$html .= "<option id_jenis=".$row->id_jenis." value=".$row->id_sediaan.">".$row->nama_sediaan."</option>";
				}
			}
		return $html."</select>";
	}
	
	function cek_sediaan_pabrik($id,$id_plant,$bentuk_sediaan){
		$sql = "select * FROM mas_industri_sediaan WHERE id_industri='".$id."' AND id_plant='".$id_plant."' AND bentuk_sediaan='".$bentuk_sediaan."'";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return false;
		}else{
			return true;	
		}
	}
	
	function get_balai($id="",$iterat="",$attr=""){
		$this->db->order_by("id_balai","ASC");
		$query = $this->db->get("mas_balai");
		$html = "<select id='kode_balai$iterat' name='kode_balai$iterat'  ".$attr.">";
		foreach($query->result() as $row){
			if($id==$row->id_balai)
				$html .= "<option value=".$row->id_balai." selected>".$row->nama_balai."</option>";
			else
				$html .= "<option value=".$row->id_balai.">".$row->nama_balai."</option>";
		}
		return $html."</select>";
	}
	
	function get_balai_session($id="",$iterat="",$attr=""){
		$sql = "SELECT *  FROM mas_balai a INNER JOIN app_users_list b ON a.id_balai=b.kode_balai WHERE b.id=".$this->session->userdata('id')."";
		$query = $this->db->query($sql);
	
		$html = "<select id='kode_balai$iterat' name='kode_balai$iterat'  ".$attr.">";
		foreach($query->result() as $row){
			if($id==$row->id_balai)
				$html .= "<option value=".$row->id_balai." selected>".$row->nama_balai."</option>";
			else
				$html .= "<option value=".$row->id_balai.">".$row->nama_balai."</option>";
		}
		return $html."</select>";
	}
	
	function get_balai_span($id="",$attr=""){
		$sql = "select * from mas_balai";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->id_balai)
				$html .= $row->nama_balai;
		}
		return $html."</span>";
	}

	function get_pnbp($id="",$iterat="",$attr=""){
		$sql = "SELECT *  FROM mas_pnbp";
		$query = $this->db->query($sql);
	
		$html = "<select id='kode_pnbp$iterat' name='kode_pnbp$iterat'  ".$attr.">";
		foreach($query->result() as $row){
			if($id==$row->kode_pnbp)
				$html .= "<option value=".$row->kode_pnbp." selected>".$row->pnbp."</option>";
			else
				$html .= "<option value=".$row->kode_pnbp.">".$row->pnbp."</option>";
		}
		return $html."</select>";
	}
	
	function get_pnbp_span($id="",$attr=""){
		$sql = "select * from mas_pnbp";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->kode_pnbp)
				$html .= $row->pnbp;
		}
		return $html."</span>";
	}
	
	function get_jenis_industri($id="",$iterat="",$attr=""){
		$sql = "select * from mas_jenis";
		$query = $this->db->query($sql);
		$html = "<select id='kode_jenisusaha$iterat' name='kode_jenisusaha$iterat' $attr>";
		foreach($query->result() as $row){
			if($id==$row->id_jenis)
				$html .= "<option value=".$row->id_jenis." selected>".$row->nama_jenis." - ".$row->nama_jenis2."</option>";
			else
				$html .= "<option value=".$row->id_jenis.">".$row->nama_jenis." - ".$row->nama_jenis2."</option>";
		}
		return $html."</select>";
	}
	
	function get_jenis_industri_session($id="",$iterat="",$attr=""){
		$sql = "SELECT *  FROM mas_jenis a INNER JOIN app_users_izin b ON a.id_jenis=b.kode_jenis WHERE b.uid=".$this->session->userdata('id')."";
		$query = $this->db->query($sql);
		$html = "<select id='kode_jenisusaha$iterat' name='kode_jenisusaha$iterat' $attr>";
		foreach($query->result() as $row){
			if($id==$row->id_jenis)
				$html .= "<option value=".$row->id_jenis." selected>".$row->nama_jenis2." - ".$row->no_izin."</option>";
			else
				$html .= "<option value=".$row->id_jenis.">".$row->nama_jenis2." - ".$row->no_izin."</option>";
		}
		return $html."</select>";
	}
	
	function get_jenis_industri_span($id="",$attr=""){
		$sql = "select * from mas_jenis";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->id_jenis)
				$html .= $row->nama_jenis." - ".$row->nama_jenis2;
		}
		return $html."</span>";
	}
	
	function get_jenis_industri2($id,$id_plant,$id_jenis="",$iterat="",$attr=""){
		$sql = "select * from mas_jenis";
		$query = $this->db->query($sql);
		$html = "<select id='id_jenis$iterat' name='id_jenis$iterat' $attr>";
		foreach($query->result() as $row){
			if($this->cek_jenis_industri($id,$id_plant,$row->id_jenis) || $id_jenis==$row->id_jenis){
				if($id_jenis==$row->id_jenis)
					$html .= "<option value=".$row->id_jenis." selected>".$row->nama_jenis." - ".$row->nama_jenis2."</option>";
				else
					$html .= "<option value=".$row->id_jenis.">".$row->nama_jenis." - ".$row->nama_jenis2."</option>";
			}
		}
		return $html."</select>";
	}
	
	function cek_jenis_industri($id,$id_plant,$id_jenis){
		$sql = "select * FROM mas_industri_plant_jenis WHERE id_industri='".$id."' AND id_plant='".$id_plant."' AND id_jenis='".$id_jenis."'";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return false;
		}else{
			return true;	
		}
	}
	
	function get_fasilitas_industri2($id,$id_plant,$id_fasilitas="",$iterat="",$attr=""){
		$sql = "select * from mas_fasilitas";
		$query = $this->db->query($sql);
		$html = "<select id='id_fasilitas$iterat' name='id_fasilitas$iterat' $attr style='width:200px'>";
		foreach($query->result() as $row){
			if($this->cek_fasilitas_industri($id,$id_plant,$row->id_fasilitas) || $id_fasilitas==$row->id_fasilitas){
				if($id_fasilitas==$row->id_fasilitas)
					$html .= "<option value=".$row->id_fasilitas." selected>".$row->nama_fasilitas."</option>";
				else
					$html .= "<option value=".$row->id_fasilitas.">".$row->nama_fasilitas."</option>";
			}
		}
		return $html."</select>";
	}
	
	function cek_fasilitas_industri($id,$id_plant,$id_fasilitas){
		$sql = "select * FROM mas_industri_fasilitas WHERE id_industri='".$id."' AND id_plant='".$id_plant."' AND id_fasilitas='".$id_fasilitas."'";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return false;
		}else{
			return true;	
		}
	}
	
	function get_jenis_status($id="",$iterat="",$attr=""){
		$sql = "select * from mas_status_industri";
		$query = $this->db->query($sql);
		$html = "<select id='id_status$iterat' name='id_status$iterat' $attr>";
		foreach($query->result() as $row){
			if($id==$row->id_status)
				$html .= "<option value=".$row->id_status." selected>".$row->nama."</option>";
			else
				$html .= "<option value=".$row->id_status.">".$row->nama."</option>";
		}
		return $html."</select>";
	}
	
	function get_status_industri($id="",$iterat="",$attr=""){
		$html = "<select id='status$iterat' name='status$iterat' $attr>";
		$html .= "<option value=1 ".($id=="1" ? "selected" : "").">Aktif</option>";
		$html .= "<option value=2 ".($id=="2" ? "selected" : "").">Non Aktif</option>";
		return $html."</select>";
	}
	
	function get_group($id="", $iterat="", $class="input", $attr=""){
		$sql = "select * from mas_group_industri";
		$query = $this->db->query($sql);
		$html = "<select id='id_group$iterat' name='nama_group$iterat' ".$attr."><option value=''>-</option>";
		foreach($query->result() as $row){
			if($id==$row->id_group)
				$html .= "<option value=".$row->id_group." selected>".$row->nama_group."</option>";
			else
				$html .= "<option value=".$row->id_group.">".$row->nama_group."</option>";
		}
		return $html."</select>";
	}
	
	function get_group_disable($id="", $iterat="", $class="input", $attr=""){
		$sql = "select * from mas_group_industri";
		$query = $this->db->query($sql);
		$html = "<select id='id_group$iterat' name='nama_group$iterat' ".$attr.">";
		foreach($query->result() as $row){
			if($id==$row->id_group)
				$html .= "<option  value=".$row->id_group." selected disabled='disabled'>".$row->nama_group."</option>";
			else
				$html .= "<option disabled='disabled' value=".$row->id_group.">".$row->nama_group."</option>";
		}
		return $html."</select>";
	}
	
	function get_propinsi($id="", $iterat="", $class="input", $attr=""){
		$sql = "select * from mas_propinsi";
		$query = $this->db->query($sql);
		$html = "<select id='kode_provinsi$iterat' name='kode_provinsi$iterat' ".$attr."><option value=''>-</option>";
		foreach($query->result() as $row){
			if($id==$row->id_propinsi)
				$html .= "<option value=".$row->id_propinsi." selected>".$row->nama_propinsi."</option>";
			else
				$html .= "<option value=".$row->id_propinsi.">".$row->nama_propinsi."</option>";
		}
		return $html."</select>";
	}
	
	function get_group_industri($id="", $iterat="", $class="input", $attr=""){
		$sql = "select * from mas_group_industri";
		$query = $this->db->query($sql);
		$html = "<select id='id_group$iterat' name='id$iterat' ".$attr."><option value=''>-</option>";
		foreach($query->result() as $row){
			if($id==$row->id_jenis)
				$html .= "<option value=".$row->id." selected>".$row->name."</option>";
			else
				$html .= "<option value=".$row->id.">".$row->name."</option>";
		}
		return $html."</select>";
	}
	
	function get_propinsi_kantor($id="", $iterat="", $class="input", $attr=""){
		$sql = "select * from mas_propinsi";
		$query = $this->db->query($sql);
		$html = "<select id='kantor_kode_provinsi$iterat' name='kantor_kode_provinsi$iterat' ".$attr."><option value=''>-</option>";
		foreach($query->result() as $row){
			if($id==$row->id_propinsi)
				$html .= "<option value=".$row->id_propinsi." selected>".$row->nama_propinsi."</option>";
			else
				$html .= "<option value=".$row->id_propinsi.">".$row->nama_propinsi."</option>";
		}
		return $html."</select>";
	}
	
	function get_propinsi_pabrik($id="", $iterat="", $class="input", $attr=""){
		$sql = "select * from mas_propinsi";
		$query = $this->db->query($sql);
		$html = "<select id='pabrik_kode_provinsi$iterat' name='pabrik_kode_provinsi$iterat' ".$attr."><option value=''>-</option>";
		foreach($query->result() as $row){
			if($id==$row->id_propinsi)
				$html .= "<option value=".$row->id_propinsi." selected>".$row->nama_propinsi."</option>";
			else
				$html .= "<option value=".$row->id_propinsi.">".$row->nama_propinsi."</option>";
		}
		return $html."</select>";
	}
	
	function get_propinsi_span($id="",$attr=""){
		$sql = "select * from mas_propinsi";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->id_propinsi)
				$html .= $row->nama_propinsi;
		}
		return $html."</span>";
	}
	
	function get_jenis($id_group, $id="",$iterat="",$class="input"){
		$data = array();
		$sql = "select * from mas_jenis where id_group ='".$id_group."' ";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			$data[$row->id_jenis] = $row->nama_jenis.' - '.$row->nama_jenis2;
		}
		return $data;
	}

	function get_kota_span($kode_provinsi, $id, $class="input"){
		$sql = "select * from mas_kota where id_kota='".$id."' ";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->id_kota)
				$html .= $row->nama_kota;
		}

		return $html."</span>";
	}
	
	function get_propinsi_span_session($id="",$attr=""){
		$sql = "SELECT * FROM mas_propinsi WHERE id_propinsi='".$id."'";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
				$html .= $row->nama_propinsi;
		}
		return $html."</span>";
	}
	
	function get_negara_span($id, $class="input"){
		$sql = "SELECT * FROM mas_negara WHERE id_negara='".$id."'";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->id_negara)
				$html .= $row->nama_negara;
		}

		return $html."</span>";
	}
	
	function get_negara($id="",$iterat="",$attr=""){
		$sql = "select * from mas_negara ORDER BY nama_negara ASC";
		$query = $this->db->query($sql);
		$html = "<select id='kode_negara$iterat' name='kode_negara$iterat' ".$attr.">";
		foreach($query->result() as $row){
			if($id==$row->id_negara)
				$html .= "<option value=".$row->id_negara." selected>".$row->nama_negara."</option>";
			else
				$html .= "<option value=".$row->id_negara.">".$row->nama_negara."</option>";
		}
		return $html."</select>";
	}

	function get_komoditi($id="",$iterat="",$attr=""){
		$sql = "select * from mas_jenis_komoditi ORDER BY jenis ASC, nama ASC";
		$query = $this->db->query($sql);
		$html = "<select id='id_jenis_komoditi$iterat' name='id_jenis_komoditi$iterat' ".$attr.">";
		foreach($query->result() as $row){
			if($id==$row->id_jenis)
				$html .= "<option value=".$row->id_jenis." selected>".($row->jenis=="Bahan" ? "Bahan Baku" : $row->jenis).": ".$row->nama."</option>";
			else
				$html .= "<option value=".$row->id_jenis.">".($row->jenis=="Bahan" ? "Bahan Baku" : $row->jenis).": ".$row->nama."</option>";
		}
		return $html."</select>";
	}

	function get_option($option=""){
		$html = "<select id='options' name='options'>";
		$html .= "<option value='-'>none</option>";
		$html .= "<option value='Distributed by' ".($option=="Distributed by" ? " selected":"").">Distributed by</option>";
		$html .= "<option value='For' ".($option=="For" ? "selected":"").">For</option>";
		return $html."</select>";
	}

	function get_persyaratan($id_jenis="",$id="",$jenis="produk",$status=""){
		$this->db->where('id_jenis',$id_jenis);
		$this->db->where('jenis',$jenis);
		if($status==1){
			$this->db->where('status',1);
		}
		$query = $this->db->get('mas_persyaratan');
		$html = "<select id='id_persyaratan_$jenis' name='id_persyaratan'>";
		foreach($query->result() as $row){
			if($id==$row->id)
				$html .= "<option value=".$row->id." selected>".$row->nama."</option>";
			else
				$html .= "<option value=".$row->id.">".$row->nama."</option>";
		}
		return $html."</select>";
	}

	function get_komoditi_span($id="",$attr=""){
		$sql = "select * from mas_jenis_komoditi";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->id_jenis)
				$html .= $row->nama;
		}
		return $html."</span>";
	}
	
	function get_sertifikat($id="",$iterat="",$attr=""){
		$sql = "select * from mas_jenis_permohonan";
		$query = $this->db->query($sql);
		$html = "<select id='id_jenis_permohonan$iterat' name='id_jenis_permohonan$iterat' ".$attr.">";
		foreach($query->result() as $row){
			if($id==$row->id)
				$html .= "<option value=".$row->id." selected>".$row->nama."</option>";
			else
				$html .= "<option value=".$row->id.">".$row->nama."</option>";
		}
		return $html."</select>";
	}

	function get_sertifikat_span($id="",$attr=""){
		$sql = "select * from mas_jenis_permohonan";
		$query = $this->db->query($sql);
		$html = "<span>";
		foreach($query->result() as $row){
			if($id==$row->id)
				$html .= $row->nama;
		}
		return $html."</span>";
	}
	
	function get_pelabuhan($id){
		$this->db->where('id_pelabuhan',$id);
		$query = $this->db->get('mas_pelabuhan');
		$data = $query->row_array();
		$data['nama'] = isset($data['nama']) ? $data['nama'] : "";
		return $data['nama'];
	}

	function get_plant($idindustri, $idplant="", $attr=""){
		$sql = "select id_plant from mas_industri_plant where id_industri='$idindustri'";
		
		$query = $this->db->query($sql);
		$html = "<select id='plant' name='plant' $attr>";
		foreach($query->result() as $row){
			if($idplant==$row->id_plant)
				$html .= "<option value=".$row->id_plant." selected>".$row->id_plant."</option>";
			else
				$html .= "<option value=".$row->id_plant.">".$row->id_plant."</option>";
		}
		return $html."</select>";
	}
	
	function get_attr_balai($id){
		$sql = "select * from mas_balai where id_balai='$id'";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
	
	function get_attr_sediaan($id,$col="nama_sediaan"){
		$sql = "select $col from mas_bentuk_sediaan where id_sediaan=$id";
		$query = $this->db->query($sql);
		$rs =  $query->row_array();
		return $rs[$col];
	}
	
	function get_kantor($idindustri, $idkantor="", $attr=""){
		$sql = "select id_kantor from mas_industri_kantor where id_industri='$idindustri'";
		
		$query = $this->db->query($sql);
		$html = "<select id='kantor' name='kantor' $attr>";
		foreach($query->result() as $row){
			if($idkantor==$row->id_kantor)
				$html .= "<option value=".$row->id_kantor." selected>Kantor ".$row->id_kantor."</option>";
			else
				$html .= "<option value=".$row->id_kantor.">Kantor ".$row->id_kantor."</option>";
		}
		return $html."</select>";
	}
	
	function get_col($col,$tbl,$where=""){
		$sql = "select $col from $tbl $where";
		$query = $this->db->query($sql);
		$rs =  $query->row_array();
		return $rs[$col];
	}
	
	function get_bentuk_usaha($id="",$iterat=""){
		$sql = "select * from mas_bentuk_perusahaan";
		$query = $this->db->query($sql);
		$html = "<select id='bentuk_usaha$iterat' name='bentuk_usaha$iterat'>";
		foreach($query->result() as $row){
			if($id==$row->id)
				$html .= "<option value=".$row->id." selected>".$row->nama_bentuk." - ".$row->nama_bentuk2."</option>";
			else
				$html .= "<option value=".$row->id.">".$row->nama_bentuk." - ".$row->nama_bentuk2."</option>";
		}
		return $html."</select>";
	}

	function select_propinsi($id="",$iterat="",$name="propinsi",$etc=""){
		$sql = "select * from mas_propinsi";
		$query = $this->db->query($sql);
		$html = "<select id='propinsi' name='".$name."' ".$etc.">";
		$html .= "<option value=0>-</option>";
		foreach($query->result() as $row){
			if($id==$row->id_propinsi)
				$html .= "<option value=".$row->id_propinsi." selected>".$row->id_propinsi." - ".$row->nama_propinsi."</option>";
			else
				$html .= "<option value=".$row->id_propinsi.">".$row->id_propinsi." - ".$row->nama_propinsi."</option>";
		}
		return $html."</select>";
	}
	
	function select_catatan($type="sd",$val=""){
		$html = "<select id='".$type."' name='".$type."'>";
		$html .= "<option>-</option>";
		$html .= "<option value='LL'  ".($val=="LL" ? "selected":"")." >LL</option>";
		$html .= "<option value='LTL' ".($val=="LTL" ? "selected":"").">LTL</option>";
		$html .= "<option value='TL'  ".($val=="TL" ? "selected":"")." >TL</option>";
		return $html."</select>";
	}
	
	function get_penanggungjawab($jabatan=0,$col="id"){
		$query = $this->db->query("select * from mas_penanggungjawab where status=1 and id_jabatan=$jabatan");
		$r = $query->row();
		return $r->$col;
	}

	function get_propinsi_by_id($id=""){
		$sql = "select nama_propinsi from mas_propinsi where id_propinsi = '".$id."'";
		$query = $this->db->query($sql);

		return $query->row();
	}

	function get_kota_by_id($id=""){
		$sql = "select nama_kota from mas_kota where id_kota = '".$id."'";
		$query = $this->db->query($sql);

		return $query->row();
	}

	function get_desa_by_id($id=""){
		$sql = "select nama_desa from mas_desa where id_desa = '".$id."'";
		$query = $this->db->query($sql);

		return $query->row();
	}

	function get_kecamatan_by_id($id=""){
		$sql = "select nama_kecamatan from mas_kecamatan where id_kecamatan = '".$id."'";
		$query = $this->db->query($sql);

		return $query->row();
	}

	function jenistanaman_option($kode_komoditi=0,$lock=0){
		$html = "";
		if($lock==0) $html ="<option value=''>-</option>";
		$sql = "select * from prm_komoditi ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($kode_komoditi==$row->kode_komoditi)
				$html .= "<option value=".$row->kode_komoditi." selected>".$row->nama."</option>";
			elseif($lock==0)
				$html .= "<option value=".$row->kode_komoditi.">".$row->nama."</option>";
		}
		return $html;
	}

	function retribusi_option($kode_retribusi=0,$lock=0){
		$html = "";
		if($lock==0) $html ="<option value=''>-</option>";
		$sql = "select * from prm_jenis_retribusi ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($kode_retribusi==$row->kode_retribusi)
				$html .= "<option value=".$row->kode_retribusi." selected>".$row->nama."</option>";
			elseif($lock==0)
				$html .= "<option value=".$row->kode_retribusi.">".$row->nama."</option>";
		}
		return $html;
	}

	function spesifikasi_option($kode_spesifikasi=0,$lock=0){
		$html = "";
		if($lock==0) $html ="<option value=''>-</option>";
		$sql = "select * from prm_spesifikasi ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($kode_spesifikasi==$row->kode_spek)
				$html .= "<option value=".$row->kode_spek." selected>".$row->nama."</option>";
			elseif($lock==0)
				$html .= "<option value=".$row->kode_spek.">".$row->nama."</option>";
		}
		return $html;
	}

	function satuan_option($kode_satuan="",$lock=0){
		$html = "";
		if($lock==0) $html ="<option value=''>-</option>";
		$sql = "select * from prm_satuan ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($kode_satuan==$row->kode_satuan)
				$html .= "<option value=".$row->kode_satuan." selected>".$row->nama."</option>";
			elseif($lock==0)
				$html .= "<option value=".$row->kode_satuan.">".$row->nama."</option>";
		}
		return $html;
	}

	function bentukbenih_option($kode_bentuk=0,$lock=0){
		$html = "";
		if($lock==0) $html ="<option value=''>-</option>";
		$sql = "select * from prm_bentuk_benih ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($kode_bentuk==$row->kode_bentuk)
				$html .= "<option value=".$row->kode_bentuk." selected>".$row->nama."</option>";
			elseif($lock==0)
				$html .= "<option value=".$row->kode_bentuk.">".$row->nama."</option>";
		}
		return $html;
	}

	function varietas_option($kode_varietas=0){
		$html ="<option value=''>-</option>";
		$sql = "select * from prm_varietas ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($kode_varietas==$row->kode_varietas)
				$html .= "<option value=".$row->kode_varietas." selected>".$row->nama."</option>";
			else
				$html .= "<option value=".$row->kode_varietas.">".$row->nama."</option>";
		}
		return $html;
	}

	function varietas_option_nonselected($selected,$kode_varietas=0) {
		$html ="<option value=''>-</option>";
		$sql = "select * from prm_varietas ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if (!in_array($row->kode_varietas, $selected)) {
				if($kode_varietas==$row->kode_varietas)
					$html .= "<option value=".$row->kode_varietas." selected>".$row->nama."</option>";
				else
					$html .= "<option value=".$row->kode_varietas.">".$row->nama."</option>";
			} else {
				if($kode_varietas==$row->kode_varietas)
					$html .= "<option value=".$row->kode_varietas." selected>".$row->nama."</option>";
			}
			
		}
		return $html;
	}

	function sertifikat_option($kode_sertifikat=""){
		$html ="<option value=''>-</option>";
		$sql = "select * from prm_sertifikat ORDER BY nama ASC";
		$query = $this->db->query($sql);
		foreach($query->result() as $row){
			if($kode_sertifikat==$row->kode_sertifikat)
				$html .= "<option value=".$row->kode_sertifikat." selected>".$row->nama."</option>";
			else
				$html .= "<option value=".$row->kode_sertifikat.">".$row->nama."</option>";
		}
		return $html;
	}

	function get_retribusi($komoditi=""){
		if($komoditi=="") $komoditi ="-";
		$sql = "select * from disbun_pad where kode_komoditi = '".$komoditi."'";
		$query = $this->db->query($sql);
		$retribusi_id = array();
		foreach($query->result() as $row){
			if (!in_array($row->kode_retribusi, $retribusi_id)) {
				$retribusi_id[] = $row->kode_retribusi;
			}
		}
		$this->db->where_in('kode_retribusi', $retribusi_id);
		$query = $this->db->get('prm_jenis_retribusi');
		foreach($query->result() as $row){
			$data[$row->kode_retribusi] = $row->nama;
		}

		return $data;
	}

	function get_spesifikasi($komoditi="",$retribusi=""){
		if($komoditi=="") $komoditi ="-";
		if($retribusi=="") $retribusi ="-";
		$sql = "select * from disbun_pad where kode_komoditi = '".$komoditi."' and kode_retribusi = '".$retribusi."'";
		$query = $this->db->query($sql);
		$spesifikasi_id = array();
		foreach($query->result() as $row){
			if (!in_array($row->kode_spek, $spesifikasi_id)) {
				$spesifikasi_id[] = $row->kode_spek;
			}
		}
		$this->db->where_in('kode_spek', $spesifikasi_id);
		$query = $this->db->get('prm_spesifikasi');
		foreach($query->result() as $row){
			$data[$row->kode_spek] = $row->nama;
		}

		return $data;
	}

	function get_satuan($komoditi="",$retribusi="",$spesifikasi=""){
		if($komoditi=="") $komoditi ="-";
		if($retribusi=="") $retribusi ="-";
		if($spesifikasi=="") $spesifikasi ="-";
		$sql = "select * from disbun_pad where kode_komoditi = '".$komoditi."' and kode_retribusi = '".$retribusi."' and kode_spek = '".$spesifikasi."'";
		$query = $this->db->query($sql);
		$satuan_id = array();
		foreach($query->result() as $row){
			if (!in_array($row->kode_satuan, $satuan_id)) {
				$satuan_id[] = $row->kode_satuan;
			}
		}
		$this->db->where_in('kode_satuan', $satuan_id);
		$query = $this->db->get('prm_satuan');
		foreach($query->result() as $row){
			$data[$row->kode_satuan] = $row->nama;
		}

		return $data;
	}

	function jqxGrid($query){
		$tabel="(".$query.") as ROWS ";
		$pagenum = $_POST['pagenum'];
		$pagesize = $_POST['pagesize'];
		$start = $pagenum * $pagesize;
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM $tabel LIMIT $start, $pagesize";
		$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
		$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
		$rows = mysql_query($sql);
		$rows = mysql_fetch_assoc($rows);
		$total_rows = $rows['found_rows'];
		$filterquery = "";
		
		if (isset($_POST['filterscount']))
		{
			$filterscount = $_POST['filterscount'];
			
			if ($filterscount > 0)
			{
				$where = " WHERE (";
				$tmpdatafield = "";
				$tmpfilteroperator = "";
				for ($i=0; $i < $filterscount; $i++)
				{
					$filtervalue		= $_POST["filtervalue" . $i];
					$filtercondition	= $_POST["filtercondition" . $i];
					$filterdatafield	= $_POST["filterdatafield" . $i];
					$filteroperator		= $_POST["filteroperator" . $i];
					
					if ($tmpdatafield == "")
					{
						$tmpdatafield = $filterdatafield;			
					}
					else if ($tmpdatafield <> $filterdatafield)
					{
						$where .= ")AND(";
					}
					else if ($tmpdatafield == $filterdatafield)
					{
						if ($tmpfilteroperator == 0)
						{
							$where .= " AND ";
						}
						else $where .= " OR ";	
					}
					
					// build the "WHERE" clause depending on the filter's condition, value and datafield.
					switch($filtercondition)
					{
						case "NOT_EMPTY":
						case "NOT_NULL":
							$where .= " " . $filterdatafield . " NOT LIKE '" . "" ."'";
							break;
						case "EMPTY":
						case "NULL":
							$where .= " " . $filterdatafield . " LIKE '" . "" ."'";
							break;
						case "CONTAINS_CASE_SENSITIVE":
							$where .= " BINARY  " . $filterdatafield . " LIKE '%" . $filtervalue ."%'";
							break;
						case "CONTAINS":
							$where .= " " . $filterdatafield . " LIKE '%" . $filtervalue ."%'";
							break;
						case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " NOT LIKE '%" . $filtervalue ."%'";
							break;
						case "DOES_NOT_CONTAIN":
							$where .= " " . $filterdatafield . " NOT LIKE '%" . $filtervalue ."%'";
							break;
						case "EQUAL_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " = '" . $filtervalue ."'";
							break;
						case "EQUAL":
							$where .= " " . $filterdatafield . " = '" . $filtervalue ."'";
							break;
						case "NOT_EQUAL_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " <> '" . $filtervalue ."'";
							break;
						case "NOT_EQUAL":
							$where .= " " . $filterdatafield . " <> '" . $filtervalue ."'";
							break;
						case "GREATER_THAN":
							$where .= " " . $filterdatafield . " > '" . $filtervalue ."'";
							break;
						case "LESS_THAN":
							$where .= " " . $filterdatafield . " < '" . $filtervalue ."'";
							break;
						case "GREATER_THAN_OR_EQUAL":
							$where .= " " . $filterdatafield . " >= '" . $filtervalue ."'";
							break;
						case "LESS_THAN_OR_EQUAL":
							$where .= " " . $filterdatafield . " <= '" . $filtervalue ."'";
							break;
						case "STARTS_WITH_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " LIKE '" . $filtervalue ."%'";
							break;
						case "STARTS_WITH":
							$where .= " " . $filterdatafield . " LIKE '" . $filtervalue ."%'";
							break;
						case "ENDS_WITH_CASE_SENSITIVE":
							$where .= " BINARY " . $filterdatafield . " LIKE '%" . $filtervalue ."'";
							break;
						case "ENDS_WITH":
							$where .= " " . $filterdatafield . " LIKE '%" . $filtervalue ."'";
							break;
					}
									
					if ($i == $filterscount - 1)
					{
						$where .= ")";
					}
					
					$tmpfilteroperator = $filteroperator;
					$tmpdatafield = $filterdatafield;			
				}
				// build the query.
				$query = "SELECT * FROM $tabel ".$where;
				$filterquery = $query;
				$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());
				$sql = "SELECT FOUND_ROWS() AS `found_rows`;";
				$rows = mysql_query($sql);
				$rows = mysql_fetch_assoc($rows);
				$new_total_rows = $rows['found_rows'];		
				$query = "SELECT * FROM $tabel ".$where." LIMIT $start, $pagesize";	
				$total_rows = $new_total_rows;	
			}
		}
		
		if (isset($_POST['sortdatafield']))
		{
		
			$sortfield = $_POST['sortdatafield'];
			$sortorder = $_POST['sortorder'];
			
			if ($sortorder != '')
			{
				if ($_POST['filterscount'] == 0)
				{
					if ($sortorder == "desc")
					{
						$query = "SELECT * FROM $tabel ORDER BY" . " " . $sortfield . " DESC LIMIT $start, $pagesize";
					}
					else if ($sortorder == "asc")
					{
						$query = "SELECT * FROM $tabel ORDER BY" . " " . $sortfield . " ASC LIMIT $start, $pagesize";
					}
				}
				else
				{
					if ($sortorder == "desc")
					{
						$filterquery .= " ORDER BY" . " " . $sortfield . " DESC LIMIT $start, $pagesize";
					}
					else if ($sortorder == "asc")	
					{
						$filterquery .= " ORDER BY" . " " . $sortfield . " ASC LIMIT $start, $pagesize";
					}
					$query = $filterquery;
				}		
			}
		}
		
		//die($query);
		$sql = "SELECT @i:=0";
        mysql_query($sql);

		$result = mysql_query($query) or die("SQL Error 1: " . mysql_error());

		$orders = null;

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$orders[] = $row;
		}

		$data[] = array(
		   'TotalRows' => $total_rows,
		   'Rows' => $orders
		);

		return $data;

		}
	}

	
/* End of file crud.php */
/* Location: ./application/model/crud.php */
?>