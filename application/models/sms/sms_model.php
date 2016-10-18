<?php
class Sms_model extends CI_Model {

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function get_data()
    {
        $data['inbox'] 		= $this->db->get('inbox')->num_rows();
        $data['pbk'] 		= $this->db->get('sms_pbk')->num_rows();
        $data['grup'] 		= $this->db->get('sms_grup')->num_rows();

        $this->db->where('Status','SendingOK');
        $this->db->or_where('Status','SendingOKNoReport');
        $this->db->or_where('Status','DeliveryOK');
        $data['sentitems'] 	= $this->db->get('sentitems')->num_rows();

        return $data;
    }

    function get_sms_kategori()
    {
        $query = $this->db->query("SELECT sms_tipe.nama, COUNT(sms_opini.id_opini) AS jml FROM sms_opini 
        INNER JOIN sms_tipe ON sms_opini.id_sms_tipe=sms_tipe.id_tipe 
        GROUP BY sms_tipe.id_tipe ORDER BY 'nama' asc");

        return $query->result();
    }

    function get_sms_diterima($bln)
    {
        // $data = array();
        $qr = $this->db->query("SELECT bln,SUM(jml) AS jml FROM 
        ((SELECT SUBSTR(ReceivingDateTime,1,7) AS bln,COUNT(*) AS jml FROM inbox GROUP BY SUBSTR(ReceivingDateTime,1,7))
          UNION 
          (SELECT SUBSTR(created_on,1,7) AS bln,COUNT(*) AS jml FROM sms_opini GROUP BY SUBSTR(created_on,1,7))
        ) AS jml where bln='".$bln."' GROUP BY bln");

        $data = $qr->row();
        if(!empty($data->jml)){
            return $data->jml;            
        }else{
            return 0;
        }
    }

    function get_sms_dikirim($bln)
    {
        $qr = $this->db->query("SELECT SUBSTR(SendingDateTime,1,7) AS bln,COUNT(*) AS jml FROM sentitems WHERE STATUS = 'SendingOK' AND SUBSTR(SendingDateTime,1,7)='".$bln."' GROUP BY SUBSTR(SendingDateTime,1,7)   ");

        $data = $qr->row();
        if(!empty($data->jml)){
            return $data->jml;            
        }else{
            return 0;
        }
    } 

    function get_sms_error($bln)
    {
        $qr = $this->db->query("SELECT SUBSTR(SendingDateTime,1,7) AS bln,COUNT(*) AS jml FROM sentitems WHERE STATUS != 'SendingOK' AND SUBSTR(SendingDateTime,1,7)='".$bln."' GROUP BY SUBSTR(SendingDateTime,1,7)   ");

        $data = $qr->row();
        if(!empty($data->jml)){
            return $data->jml;            
        }else{
            return 0;
        }
    } 

    
}