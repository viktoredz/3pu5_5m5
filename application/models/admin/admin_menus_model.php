<?php
class Admin_menus_model extends CI_Model {

    var $tabel		= 'menus';
    var $tabel2		= 'menus_category';

    function __construct() {
        parent::__construct();
		$this->load->library('encrypt');
    }
    
    function get_count($options=array())
    {
		if(isset($options['kode_category']) && $options['kode_category']<1)$options['kode_category']="";
		foreach($options as $x=>$y){
			if($y=="-" || $y=="" || $y=="0"){
				continue;
			}
			elseif($x=="kode_category" || $x=="tipe"){
				$this->db->where($x,$y);
			}
			elseif($x=="kode"){
				$this->db->like("menus.kode",$y);
			}
			else{
				$this->db->like($x,$y);
			}
		}

		$this->db->like($options);
        $query = $this->db->get($this->tabel);
		return count($query->result_array());
    }

    function get_data($start,$limit,$options=array())
    {
		if(isset($options['kode_category']) && $options['kode_category']<1)$options['kode_category']="";
		foreach($options as $x=>$y){
			if($y=="-" || $y=="" || $y=="0"){
				continue;
			}
			elseif($x=="kode_category" || $x=="tipe"){
				$this->db->where($x,$y);
			}
			elseif($x=="kode"){
				$this->db->like("menus.kode",$y);
			}
			else{
				$this->db->like($x,$y);
			}
		}
		$this->db->select(array('menus.kode','menus.nama','menus.keterangan','menus.price','menus.cost','menus_category.nama as kategori'));
		$this->db->join('menus_category','menus_category.kode=menus.kode_category');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

    function get_data_category()
    {
		$data[]="-";
        $query = $this->db->get($this->tabel2);
        foreach($query->result_array() as $key=>$dt){
			$data[$dt['kode']]=ucfirst($dt['nama']);
		}

		$query->free_result();    
		return $data;
    }

 	function get_data_row($kode){
		$data = array();
		$options = array('kode' => $kode);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

   function insert_entry()
    {
		$data['kode']=$this->input->post('kode');
		$data['nama']=$this->input->post('nama');
		$data['keterangan']=$this->input->post('keterangan');
		$data['cost']=floatval($this->input->post('cost'));
		$data['price']=floatval($this->input->post('price'));
		$data['kode_category']=$this->input->post('kode_category');
		$data['tipe']=$this->input->post('tipe');

		return $this->db->insert($this->tabel, $data);
    }

    function update_entry($kode)
    {
		$data['nama']=$this->input->post('nama');
 		$data['keterangan']=$this->input->post('keterangan');
		$data['cost']=floatval($this->input->post('cost'));
		$data['price']=floatval($this->input->post('price'));
 		$data['kode_category']=$this->input->post('kode_category');
		$data['img']=$this->input->post('img');
 		$data['tipe']=$this->input->post('tipe');
     
		return $this->db->update($this->tabel, $data, array('kode' => $kode));
    }
	

	function delete_entry($kode)
	{
		$this->db->where(array('kode' => $kode));

		return $this->db->delete($this->tabel);
	}

}