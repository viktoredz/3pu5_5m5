<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package		 CodeIgniter
 * @subpackage 	 Model
 * @category	 PCare API
 *
 */
 
class Bpjs extends CI_Model {

	var $server;
	var $consid;
	var $secretkey;
	var $username; 
	var $password;
	var $xtime;
	var $xauth;
	var $data;
	var $signature;
	var $xsign;

	function __construct() {
		parent::__construct();
	   	
	   	require_once(APPPATH.'third_party/httpful.phar');

		$this->get_data_bpjs("live");
	}

	function get_demo_bpjs(){
    	$data = array();
    	$data['server'] 	='http://dvlp.bpjs-kesehatan.go.id:9080/pcare-rest-dev/v1/';
    	$data['username'] 	='pkmbangko';
    	$data['password'] 	='05050101';
    	$data['consid'] 	='23921';
    	$data['secretkey'] 	='0pMBE6D40F';
    	return $data;
	}
	function get_data_bpjs($default = "live", $kdProviderPeserta = ""){
    	$data = array();
    	if($default=="live"){
    		if($kdProviderPeserta!="" && $kdProviderPeserta!=0){
		    	$this->db->where('username',$kdProviderPeserta);
		    	$data = $this->db->get('cl_phc_bpjs')->row_array();
				if(!isset($data['code']) || !isset($data['server']) || !isset($data['username']) || !isset($data['password']) || !isset($data['consid']) || !isset($data['secretkey'])) {
	        		$data = $this->get_demo_bpjs();
		        }
    		}else{
		    	$id='P'.$this->session->userdata('puskesmas');
		    	$this->db->where('code',$id);
		    	$data = $this->db->get('cl_phc_bpjs')->row_array();
				if(!isset($data['code']) || !isset($data['server']) || !isset($data['username']) || !isset($data['password']) || !isset($data['consid']) || !isset($data['secretkey'])) {
	        		$data = $this->get_demo_bpjs();
		        }
    		}
    		//print_r($data);die();
	    }
    	elseif($default=="global"){
	    	$id='P'.$this->session->userdata('puskesmas');
	    	$this->db->where('code',$id);
	    	$data = $this->db->get('cl_phc_bpjs')->row_array();
			if(!isset($data['code']) || !isset($data['server']) || !isset($data['username']) || !isset($data['password']) || !isset($data['consid']) || !isset($data['secretkey'])) {
		    	$id='P3172010202';
		    	$this->db->where('code',$id);
		    	$data = $this->db->get('cl_phc_bpjs')->row_array();
				if(!isset($data['code']) || !isset($data['server']) || !isset($data['username']) || !isset($data['password']) || !isset($data['consid']) || !isset($data['secretkey'])) {
	        		$data = $this->get_demo_bpjs();
		        }
	        }
        }
        else{
        	$data = $this->get_demo_bpjs();
        }

	    $this->server 		= $data['server'];
	    $this->username 	= $data['username'];
	    $this->password 	= $data['password'];
	    $this->consid 		= $data['consid'];
	    $this->secretkey 	= $data['secretkey'];
	    $this->xtime 		= time();
	    $this->maxxtimeget 	= 15;
	    $this->maxxtimepost	= 120;
	    $this->xauth 		= base64_encode($this->username.':'.$this->password.':095');
	    $this->data 		= $this->consid."&".$this->xtime;
	    $this->signature 	= hash_hmac('sha256', $this->data, $this->secretkey, true);
	    $this->xsign 		= base64_encode($this->signature);

	    return $data;
    }

	function getApi($url="",$methode="global",$ver = 1,$provider=""){
		if($provider!=""){
		   $this->get_data_bpjs("live",$provider);
		}else{
		   $this->get_data_bpjs($methode);
		}

	   if($ver!=1){
			$this->server = str_replace("v1", "v2", $this->server);
	   }

	   try
	    {
	      $response = \Httpful\Request::get($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
	      ->timeout($this->maxxtimeget)
	      ->send();
	       $data = json_decode($response,true);
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      $data = array("metaData"=>array("message" =>'error',"code"=>777));
	      //die(json_encode(array("res"=>"error","msg"=>$data)));

	    }
	    
	    if($data["metaData"]["code"]=="500"){
	      die(json_encode(array("res"=>"500","msg"=>$data["metaData"]["message"])));
	    } 
	    /*update message ketika nomor kartu tidak ditemukan--> kasus no kartu tidak sama dengan 13*/
	    if($data["metaData"]["code"]=="412"){
	      die(json_encode(array("res"=>"412","msg"=>$data["response"][0]["message"])));
	    } 

	    return $data;
	}

	function postApi($url="", $data=array()){
		if(isset($data['kdProviderPeserta'])){
		   $this->get_data_bpjs("live",$data['kdProviderPeserta']);
		}else{
		   $this->get_data_bpjs("live");
		}
	   //$this->get_data_bpjs("demo");
	   try
	    {
	      $response = \Httpful\Request::post($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
		  ->body($data)
		  ->sendsJson()
		  ->timeout($this->maxxtimepost)
	      ->send();
	      $data = json_decode($response,true);
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      $data = array("metaData"=>array("message" =>'error',"code"=>777));
	      //die(json_encode(array("res"=>"error","msg"=>$data)));
	    }
	    //die(print_r($response));
		// if($response["metaData"]["code"]=="201"){

		// }elseif($response["metaData"]["code"]=="304"){

		// }else{

		// }

	    return $data;
	}

	function putApi($url="", $data=array()){
	   $this->get_data_bpjs("live");
	   try
	    {
	      $response = \Httpful\Request::post($this->server.$url)
	      ->xConsId($this->consid)
	      ->xTimestamp($this->xtime)
	      ->xSignature($this->xsign)
	      ->xAuthorization("Basic ".$this->xauth)
		  ->body($data)
		  ->sendsJson()
		  ->timeout($this->maxxtimepost)
	      ->send();
	    }
	    catch(Exception $E)
	    {
	      $reflector = new \ReflectionClass($E);
	      $classProperty = $reflector->getProperty('message');
	      $classProperty->setAccessible(true);
	      $data = $classProperty->getValue($E);
	      $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
	      die(json_encode(array("res"=>"error","msg"=>$data)));
	    }
	    $data = json_decode($response,true);
	    
		if($response["metaData"]["code"]=="200"){

		}else{

		}

	    return $data;
	}

	function deleteApi($url="",$methode="live"){
	   $this->get_data_bpjs($methode);
	   try
        {
          $response = \Httpful\Request::delete($this->server.$url)
          ->xConsId($this->consid)
          ->xTimestamp($this->xtime)
          ->xSignature($this->xsign)
          ->xAuthorization("Basic ".$this->xauth)
          ->send();
          $data = json_decode($response,true);
        }
        catch(Exception $E)
        {
          $reflector = new \ReflectionClass($E);
          $classProperty = $reflector->getProperty('message');
          $classProperty->setAccessible(true);
          $data = $classProperty->getValue($E);
          $data = "Tidak dapat terkoneksi ke server BPJS, silakan dicoba lagi";
          $data = array("metaData"=>array("message" =>'error',"code"=>777));
        }
        return $data;
	}

	function bpjs_club($kdJnsKelompok="01"){
		$data = $this->getApi('pcare-rest-dev/v1/kelompok/club/'.$kdJnsKelompok);

      	return $data['response']['list'];
	}

	function bpjs_option($type="poli"){
		$data = $this->getApi('poli/fktp/0/99');

      	return $data['response']['list'];
	}

	function db_search($by="nik",$no){
		if($by == "nik"){
	    	$this->db->where('nik',$no);
	    	$search = $this->db->get('data_keluarga_anggota')->row();
			if(!empty($search->id_data_keluarga)) return "exists";
		}else{
	    	$this->db->where('bpjs',$no);
	    	$search = $this->db->get('data_keluarga_anggota')->row();
			if(!empty($search->id_data_keluarga)) return "exists";
		}
	}

	function bpjs_search($by="nik",$no){
		if($by == "nik"){
			$data = $this->getApi('peserta/nik/'.$no,"global",2);
		}else{
			$data = $this->getApi('peserta/'.$no,"global",2);
		}

      	return $data;
	}

	/*function get_dokter($code){
    	$this->db->where('code',$code);
    	$data = $this->db->get('cl_phc_bpjs')->row_array();

		if(isset($data['username'])){
			$data = $this->getApi('dokter/0/99',"live",1,$data['username']);
			if(isset($data['response']['count']) && $data['response']['count']>0){
	    		$this->db->where('cl_phc', $code);
	    		$this->db->delete('bpjs_data_dokter');

				foreach ($data['response']['list'] as $dokter) {
					$dt['code']		= $dokter['kdDokter'];
					$dt['value']	= $dokter['nmDokter'];
					$dt['cl_phc']	= $code;
					$dt['status']	= '1';

		    		$this->db->insert('bpjs_data_dokter', $dt);
				}
			}

	      	return $data['response']['count'];
		}
	}*/

	function get_obat(){
		$data = $this->getApi('obat/dpho/1301/0/9999',"live");
		if(isset($data['response']['count']) && $data['response']['count']>0){
    		$this->db->where('code <> ""');
    		$this->db->delete('bpjs_data_obat');

			foreach ($data['response']['list'] as $dokter) {
				$dt['code']		= $dokter['kdObat'];
				$dt['value']	= $dokter['nmObat'];
				$dt['sediaan']	= $dokter['sedia'];
				$dt['status']	= '1';

	    		$this->db->insert('bpjs_data_obat', $dt);
			}
		}

      	return $data['response']['count'];
	}

	function inserbpjs($kode){
       $tampildata = $this->getApi('peserta/'.$kode);
       if (($tampildata['metaData']['message']=='error')&&($tampildata['metaData']['code']=='777')) {
           return  'bpjserror';
       }else{
	        if (array_key_exists("kdProvider",$tampildata['response']['kdProviderPst'])){
	            $kodeprov = $tampildata['response']['kdProviderPst']['kdProvider'];
	        }else{
	            $kodeprov = '0';
	        }
            $data_kunjungan = array(
              "kdProviderPeserta" => $kodeprov,
              "tglDaftar" 	=> date('d-m-Y'),
              "noKartu" 	=> $tampildata['response']['noKartu'],
              "kdPoli" 		=> "020",
              "keluhan" 	=> null,
              "kunjSakit" 	=> false,
              "sistole" 	=> 0,
              "diastole" 	=> 0,
              "beratBadan" 	=> 0,
              "tinggiBadan" => 0,
              "respRate" 	=> 0,
              "heartRate" 	=> 0,
              "rujukBalik" 	=> 0,
              "rawatInap" 	=> false
            ); 
            $datavisit = $this->postApi('pendaftaran', $data_kunjungan);
            if (($datavisit['metaData']['message']=='CREATED') && ($datavisit['metaData']['code']=='201')){
            	return $datasmpn = $this->simpandatabpjs($datavisit['response']['message'],$kode);
	        }
	        elseif(($datavisit['metaData']['message']=='NOT_MODIFIED') && ($datavisit['metaData']['code']=='304')){
	            return 'dataada';
	        }
	        elseif(($datavisit['metaData']['message']=='PRECONDITION_FAILED') && ($datavisit['metaData']['code']=='412')){
	            return 'datatidakada';
	        }else{
	            return 'bpjserror';
	        }
        }
    }
   
    function simpandatabpjs($nourut=0,$kartu=0){
        $tampildata = $this->getApi('peserta/'.$kartu);
        if (($tampildata['metaData']['message']=='error') && ($tampildata['metaData']['code']=='777')) {
           return  'bpjserror';
        }else{
           if (isset($tampildata['response']['kdProviderPst']['kdProvider']) && $tampildata['response']['kdProviderPst']['kdProvider']!=""){
                $kodeprov = $tampildata['response']['kdProviderPst']['kdProvider'];
            }else{
                $kodeprov = '0';
            }
            $this->db->where('no_kartu',$kartu);
            $this->db->where('tgl_daftar',date("d-m-Y"));
            $dt = $this->db->get('data_keluarga_anggota_bpjs')->row();
            if(empty($dt->no_kartu)){
	            $data = array(
		            'kd_provider_peserta'  =>  $kodeprov,
		            'no_kartu'  	=> $kartu,
		            'tgl_daftar'  	=> date("d-m-Y"),
		            'no_urut'  		=> $nourut
	            );
	            $this->db->insert('data_keluarga_anggota_bpjs',$data);
            }
            return 'datatersimpan';
        }
    }
    function keluargaanggotabpjs($kode=0){
        $this->db->where('no_kartu',$kode);
        $query = $this->db->get('data_keluarga_anggota_bpjs');
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
        }else{
            $data['kd_provider_peserta'] = '';
            $data['no_kartu'] 	= '';
            $data['tgl_daftar'] = '';
            $data['no_urut'] 	= '';
        }
        $query->free_result();
        return $data;
    }
    function deletebpjs($kode){
    	$tampildata = $this->keluargaanggotabpjs($kode);
    	/*$datavisit 	= $this->deleteApi("/pendaftaran/peserta/".$tampildata['no_kartu']."/tglDaftar/".$tampildata['tgl_daftar']."/noUrut/".$tampildata['no_urut']);
        if (($datavisit['metaData']['message']=='OK')&&($datavisit['metaData']['code']=='200')) {
            return 'datatersimpan';
        }else{
            return 'bpjserror';
        }*/
    }

	function bpjs_send_kegiatan($kode){
    	$this->db->where('id_data_kegiatan',$kode);
    	$data = $this->db->get('data_kegiatan')->row_array();

    	if($data['status_penyuluhan']==1 && $data['status_senam']==1){
    		$kdKegiatan = "11";
    	}elseif($data['status_penyuluhan']==1 && $data['status_senam']==0){
    		$kdKegiatan = "10";
    	}else{
    		$kdKegiatan = "01";
    	}

        $data_kegiatan = array(
          "eduId" 		=> null,
          "clubId" 		=> $data['kode_club'],
          "tglPelayanan"=> date("d-m-Y",strtotime($data['tgl'])),
          "kdKegiatan" 	=> $kdKegiatan,
          "kdKelompok" 	=> $data['kode_kelompok'],
          "materi" 		=> $data['materi'],
          "pembicara" 	=> $data['pembicara'],
          "lokasi" 		=> $data['lokasi'],
          "keterangan" 	=> $data['keterangan'],
          "biaya" 		=> $data['biaya'],
        ); 
        $datavisit = $this->postApi('kelompok/kegiatan', $data_kegiatan);
        if (($datavisit['metaData']['message']=='CREATED') && ($datavisit['metaData']['code']=='201')){
        	$update = array();
        	$update['eduId'] = $datavisit['response']['message'];
        	$this->db->where('id_data_kegiatan',$kode);
        	$this->db->update('data_kegiatan',$update);

        	$this->bpjs_resend_kegiatan($kode);
        	return 'ok';
        }
        elseif(($datavisit['metaData']['message']=='NOT_MODIFIED') && ($datavisit['metaData']['code']=='304')){
            return 'dataada';
        }
        elseif(($datavisit['metaData']['message']=='PRECONDITION_FAILED') && ($datavisit['metaData']['code']=='412')){
            return print_r($datavisit['response'],true);
        }else{
            return 'bpjserror';
        }
    }
   
	function bpjs_resend_kegiatan($kode){
    	$this->db->where('id_data_kegiatan',$kode);
    	$kegiatan = $this->db->get('data_kegiatan')->row_array();

    	/*if($kegiatan['status_penyuluhan']==1 && $kegiatan['status_senam']==1){
    		$kdKegiatan = "11";
    	}elseif($kegiatan['status_penyuluhan']==1 && $kegiatan['status_senam']==0){
    		$kdKegiatan = "10";
    	}else{
    		$kdKegiatan = "01";
    	}
        $data_kegiatan = array(
          "eduId" 		=> $kegiatan['eduId'],
          "clubId" 		=> $kegiatan['kode_club'],
          "tglPelayanan"=> date("d-m-Y",strtotime($kegiatan['tgl'])),
          "kdKegiatan" 	=> $kdKegiatan,
          "kdKelompok" 	=> $kegiatan['kode_kelompok'],
          "materi" 		=> $kegiatan['materi'],
          "pembicara" 	=> $kegiatan['pembicara'],
          "lokasi" 		=> $kegiatan['lokasi'],
          "keterangan" 	=> $kegiatan['keterangan'],
          "biaya" 		=> $kegiatan['biaya'],
        ); 
        $datavisit = $this->postApi('kelompok/kegiatan/', $data_kegiatan);
    	$getpeserta = $this->getApi("kelompok/peserta/".$kegiatan['eduId'], "live");
    	if(is_array($getpeserta['response']['list'])){
    		$list = $getpeserta['response']['list'];
    		foreach ($list as $pst) {
    			$delpeserta = $this->deleteApi("kelompok/peserta/".$kegiatan['eduId']."/".$pst['peserta']['noKartu'],"live");
			   	echo "\n".$this->server."kelompok/peserta/".$kegiatan['eduId']."/".$pst['peserta']['noKartu'];
			   	echo "\n".$this->consid;
			   	echo "\n".$this->xtime;
			   	echo "\n".$this->xsign;
			   	echo "\n"."Basic ".$this->xauth;
			   	echo "\n";

    			print_r($delpeserta);
    		}
    	}
        */

    	$this->db->where('id_data_kegiatan',$kode);
    	$peserta = $this->db->get('data_kegiatan_peserta')->result_array();
    	foreach ($peserta as $value) {
	        $data_peserta = array(
	          "eduId" 		=> $kegiatan['eduId'],
	          "noKartu" 	=> $value['no_kartu'],
	        ); 
        	$datapeserta = $this->postApi('kelompok/peserta', $data_peserta);
        	if (($datapeserta['metaData']['message']=='CREATED') && ($datapeserta['metaData']['code']=='201')){
	        	$update = array();
	        	$update['eduId'] = $kegiatan['eduId'];
	        	$this->db->where('id_data_kegiatan',$kode);
	        	$this->db->where('no_kartu',$value['no_kartu']);
	        	$this->db->update('data_kegiatan_peserta',$update);
	        }
	        /*elseif(($datapeserta['metaData']['message']=='NOT_MODIFIED') && ($datavisit['metaData']['code']=='304')){
	            return 'dataada';
	        }
	        elseif(($datapeserta['metaData']['message']=='PRECONDITION_FAILED') && ($datavisit['metaData']['code']=='412')){
	            return print_r($datapeserta['response'],true);
	        }else{
	            return 'bpjserror';
	        }*/
    	}

    	return "Data peserta berhasil terkirim ke PCare";
        
    }
   

}
?>