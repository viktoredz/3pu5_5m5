<?php

	class Authentication {
		
		function Authentication()
		{
			$this->obj =& get_instance();
		}

		function verify($module,$action){


			$query = $this->obj->db-> query("SELECT * FROM app_users_access AS a, app_files AS b WHERE a.file_id=b.id AND b.module='".$module."' AND a.level_id='".$this->obj->session->userdata('level')."' AND do".$action."=1");
			if ($query-> num_rows() <1 && $this->obj->session->userdata('level')!="super administrator"){

				$this->obj->session->set_flashdata('alert', 'Maaf anda belum dapat mengakses halaman yang anda pilih.<br>Silahkan melakukan <b>'.anchor(base_url().'users/registrasi','pendaftaran').'</b> terlebih dahulu. <br><b> '.anchor(base_url().'users/registrasi','Klik disini').'</b> untuk melakukan pendaftaran <br>');

				$application_theme = array('morganisasi');
				$segment_theme=explode("_",$this->obj->uri->segment(1));
				redirect(base_url()."morganisasi/login");
			}
		}
		
		function indonesian_date($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') {
			if (trim ($timestamp) == '')
			{
					$timestamp = time ();
			}
			elseif (!ctype_digit ($timestamp))
			{
				$timestamp = strtotime ($timestamp);
			}
			
			# remove S (st,nd,rd,th) there are no such things in indonesia :p
			$date_format = preg_replace ("/S/", "", $date_format);
			
			$pattern = array (
				'/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
				'/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
				'/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
				'/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
				'/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
				'/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
				'/April/','/June/','/July/','/August/','/September/','/October/',
				'/November/','/December/',
			);
			
			$replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
				'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
				'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
				'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
				'Oktober','November','Desember',
			);
			$date = date ($date_format, $timestamp);
			$date = preg_replace ($pattern, $replace, $date);
			$date = "{$date} {$suffix}";
			return $date;
		}
		
		function get_icon($ext){
			$return = base_url()."public/images/";
			switch($ext){
				case "doc" : $return .= "file_doc.png";break;
				case "ocx" : $return .= "file_doc.png";break;
				case "xls" : $return .= "file_excel.png";break;
				case "lsx" : $return .= "file_excel.png";break;
				case "rar" : $return .= "file_rar.png";break;
				case "txt" : $return .= "file_txt.png";break;
				case "pdf" : $return .= "file_pdf.png";break;
				case "jpg" : $return .= "file_jpg.png";break;
				case "png" : $return .= "file_png.png";break;
				case "gif" : $return .= "file_gif.png";break;
				default : $return .= "file_unk.png";break;
			}
			return $return;
		}
	}	
