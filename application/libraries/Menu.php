<?php
	class Menu {

		var $menus;
		var $app_menus			= 'app_menus';
		var $app_files			= 'app_files';
		var $app_users_access	= 'app_users_access';
		
		function Menu()
		{
			$this->obj =& get_instance();
			$_SESSION['lang'] = (!isset($_SESSION['lang']) || $_SESSION['lang']=="" ? $this->obj->config->item('language') : $_SESSION['lang']) ;

			$this->create_sitemap(0);
			$query = $this->obj->db->get('app_web_modules');
			foreach ($query->result_array() as $row)
			{
			   $this->web_modules[] = $row['modules'];
			}

		}

		function have_child($id){
			$query=$this->obj->db->query("select count(id) as n from ".$this->app_menus." where sub_id=".$id);			
			$x=0;
			foreach($query->result() as $q){
				$x = $q->n;
			}
			
			if($x > 0){
				return true;
			}else{
				return false;
			}
		}

		function create_menu($posisi, $subid){
			$query=$this->obj->db->query("select app_menus.id, app_files.id as idmenu, filename, module from ".$this->app_menus." inner join ".$this->app_files." on ".$this->app_menus.".file_id = ".$this->app_files.".id inner join ".$this->app_users_access." using(file_id) where level_id='".$this->obj->session->userdata('level')."' and position = ".$posisi." and sub_id =".$subid." and lang = '".$_SESSION['lang']."'  and doshow=1 order by sort asc");
			$icon = array(
				"menus" => "fa fa-cogs",
				"submenu" => "fa fa-circle-o",
				"39" => "fa fa-dashboard",
				"74" => "fa fa-users",
				"55" => "fa fa-money",
				"57" => "fa fa-dropbox",
				"59" => "fa fa-envelope",
				"41" => "fa fa-bar-chart-o",
				"49" => "fa fa-asterisk",
				"126" => "fa fa-tasks",
				"136" => "fa fa-heart",
				"6" => "fa fa-table"				
			);
			
			$text="";
			if($subid==0){
				$text=$text."<ul class=\"sidebar-menu\">";
			}else{				
				$text=$text."<ul class=\"treeview-menu\">";
			}
			
			if($this->obj->session->userdata('level')=='administrator'){
				$lvl = 'admin';
			}else{
				$lvl = $this->obj->session->userdata('level');
			}
			
			foreach($query->result() as $q){				
				if(!empty($icon[$q->idmenu])){
					$ico = $icon[$q->idmenu];
				}else{
					$ico = "fa fa-circle-o";
				}
				if($q->module=="#") $id_menu = str_replace(" ", "_",str_replace("/", "_", strtolower($q->filename)));
				else $id_menu = str_replace("/", "_", $q->module);

				if($this->have_child($q->id)){	
					$text=$text."<li class=\"treeview\" id=\"menu_".$id_menu."\">
						<a href=\" ".base_url().$q->module." \">
							<i class=\" ".$ico." \"></i> <span> ".$q->filename." </span> <i class=\"fa fa-angle-left pull-right\"></i>
						</a>";
					$text=$text.$this->create_menu($posisi, $q->id);
					 				
				}else{
					$text=$text."<li id=\"menu_".$id_menu."\">
						<a href=\" ".base_url().$q->module." \">
							<i class=\" ".$ico."\"></i> <span> ".$q->filename." </span> <i class=\"pull-right\"></i>
						</a>";
				}
				
				
				$text=$text."</li>";
			}
			$text=$text."</ul>";
			return $text;
		}
		
		function create_menux($position,$class,$sub_id=0){
			if($this->obj->session->userdata('level') == "super administrator"){
				$query = $this->obj->db-> query("SELECT a.id,a.file_id,c.filename,c.module FROM ".$this->app_menus." AS a,".$this->app_files." as c WHERE a.file_id=c.id AND a.`position`=".$position." AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}
			elseif($this->obj->session->userdata('level') != ""){
				$query = $this->obj->db-> query("SELECT a.id,a.file_id,c.filename,c.module FROM ".$this->app_menus." AS a,".$this->app_users_access." AS b,".$this->app_files." as c WHERE a.file_id=c.id AND a.file_id=b.file_id AND b.doshow=1 AND b.level_id='".$this->obj->session->userdata('level')."' AND a.`position`=".$position." AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}else{
				$query = $this->obj->db-> query("SELECT a.id,a.file_id,c.filename,c.module FROM ".$this->app_menus." AS a,".$this->app_users_access." AS b,".$this->app_files." as c WHERE a.file_id=c.id AND a.file_id=b.file_id AND b.doshow=1 AND b.level_id='guest' AND a.`position`=".$position." AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}

			if(!isset($this->menus[$position])){
				$this->menus[$position]="<ul class='".$class."'>";
			}else{
				$this->menus[$position].="<ul class='".$class."'>";
			}

			$unread = $this->obj->db-> query("SELECT COUNT(*) as unread FROM app_user_msgs_read WHERE username='".$this->obj->session->userdata('username')."' AND dtime_seen=0");
			$unread = $unread->row_array();
			$unread = intval($unread['unread']);

			foreach ($query->result() as $row){
				if($class=="menu-2") $row->filename = "&nbsp;";

				$is_msg = explode("/",$row->module);
				if(isset($is_msg[2])){
					if($is_msg[2]=="inbox"){
						$row->filename = "[".$unread."] ".$row->filename." ".($unread>0 ? "<b>*</b>":"");
					}
				}

				if($row->filename=="Profile"){
					$row->filename = $row->filename.($unread>0 ? "<b>*</b>":"");
				}

				if(in_array($row->module,$this->web_modules)){
					$row->module=base_url()."".$row->module."/".$row->file_id."/".$row->filename;
				}
				if($row->module == "#" || $row->module=="" ) $this->menus[$position] .= "<li id=".$row->id."><a href=#>".$row->filename."</a>";
				else	$this->menus[$position] .= "<li id='list_".$row->id."'>".anchor($row->module,$row->filename);
				$this->create_menu($position,$class,$row->id);

				$this->menus[$position] .= "</li>";
			}
			$this->menus[$position].="</ul>";

		}
		
		function create_menu_basic($position,$class,$sub_id=0){
			if($this->obj->session->userdata('level') == "super administrator"){
				$query = $this->obj->db-> query("SELECT a.id,a.file_id,c.filename,c.module FROM ".$this->app_menus." AS a,".$this->app_files." as c WHERE a.file_id=c.id AND a.`position`=".$position." AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}
			elseif($this->obj->session->userdata('level') != ""){
				$query = $this->obj->db-> query("SELECT a.id,a.file_id,c.filename,c.module FROM ".$this->app_menus." AS a,".$this->app_users_access." AS b,".$this->app_files." as c WHERE a.file_id=c.id AND a.file_id=b.file_id AND b.doshow=1 AND b.level_id='".$this->obj->session->userdata('level')."' AND a.`position`=".$position." AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}else{
				$query = $this->obj->db-> query("SELECT a.id,a.file_id,c.filename,c.module FROM ".$this->app_menus." AS a,".$this->app_users_access." AS b,".$this->app_files." as c WHERE a.file_id=c.id AND a.file_id=b.file_id AND b.doshow=1 AND b.level_id='guest' AND a.`position`=".$position." AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}
			
			$this->menus[$position] = "";
			foreach ($query->result() as $row){
				if($this->menus[$position]!="") $this->menus[$position] .= " | ";

				if(in_array($row->module,$this->web_modules)){
					$row->module=base_url()."".$row->module."/".$row->file_id."/".$row->filename;
				}
				if($row->module == "#" || $row->module=="" ) $this->menus[$position] .= "<a href=#>".$row->filename."</a>";
				else	$this->menus[$position] .= anchor($row->module,$row->filename);
			}

		}
		
		function create_sitemap($sub_id){
			$this->sitemap="<ul class='sitemap'>";
			if($this->obj->session->userdata('level') == "super administrator"){
				$query = $this->obj->db-> query("SELECT c.* FROM ".$this->app_menus." AS a,".$this->app_files." as c WHERE a.file_id=c.id AND a.`position`=1 AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}
			elseif($this->obj->session->userdata('level') != ""){
				$query = $this->obj->db-> query("SELECT c.* FROM ".$this->app_menus." AS a,".$this->app_users_access." AS b,".$this->app_files." as c WHERE a.file_id=c.id AND a.file_id=b.file_id AND b.doshow=1 AND b.level_id='".$this->obj->session->userdata('level')."' AND a.`position`=1 AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}else{
				$query = $this->obj->db-> query("SELECT c.* FROM ".$this->app_menus." AS a,".$this->app_users_access." AS b,".$this->app_files." as c WHERE a.file_id=c.id AND a.file_id=b.file_id AND b.doshow=1 AND b.level_id='guest' AND a.`position`=1 AND a.sub_id='".$sub_id."' AND c.lang='".$_SESSION['lang']."' GROUP BY a.position,a.id ORDER BY sort");
			}

			foreach ($query->result() as $row){
				if($row->module == "#" || $row->module=="" ) $this->sitemap .= "<li><a href=#>".$row->filename."</a>";
				else	$this->sitemap .= "<li>".anchor($row->module,$row->filename);
				$this->create_sitemap($row->id);

				$this->sitemap .= "</li>";
			}
			$this->sitemap.="</ul>";

		}
		
	}	
