<script type="text/javascript">
	$(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '<?php echo base_url()?>plugins/js/tinymce/jscripts/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "<?php echo base_url()?>application/views/<?php echo $this->template->template?>/css/css-rte.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "<?php echo base_url()?>js/tinymce/examples/lists/template_list.js",
			external_link_list_url : "<?php echo base_url()?>js/tinymce/examples/lists/link_list.js",
			external_image_list_url : "<?php echo base_url()?>js/tinymce/examples/lists/image_list.js",
			media_external_list_url : "<?php echo base_url()?>js/tinymce/examples/lists/media_list.js",
			relative_urls : false,

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
/*
		new AjaxUpload($('#linkimages'), {
			action: '<?php echo base_url()?>index.php/admin_content/douploadimages2/{file_id}/galleryevent',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				$('#linkimages_alert').show('fold',500);
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
					$('#linkimages_alert').html('<div align=right onClick="$(\'#linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>Only JPG, PNG or GIF files are allowed');
					return false;
				}
				$('#linkimages_alert').show('fold',500);
				$('#linkimages_alert').html('Uploading image...');
			},
			onComplete: function(file, response){
				$('#linkimages_alert').html('<div align=right onClick="$(\'#linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>'+response);
				$.ajax({ url: "<?php echo base_url()?>index.php/admin_content/filelist/media__images__galleryevent__{file_id}", context: document.body, success: function(result){
						$("#image_lsit").html(result);
					}
				});
			}
		});
		*/
		
		new AjaxUpload($('#linkimages'), {
				action: '<?php echo base_url()?>index.php/admin_content/douploadimages2/{file_id}/galleryevent/{id}/100',
				name: 'uploadfile',
				onSubmit: function(file, ext){
					$('#linkimages_alert').show('fold',500);
					 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
						$('#linkimages_alert').html('<div align=right onClick="$(\'#linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>Only JPG, PNG or GIF files are allowed');
						return false;
					}
					$('#linkimages_alert').show('fold',500);
					$('#linkimages_alert').html('Uploading image...');
				},
				onComplete: function(file, response){
					stat = response.substr(0,7)
					filename = response.substr(10)
					if(stat==="success"){
						$('#linkimages').attr("src", "<?php echo base_url()?>media/images/galleryevent/{file_id}/{id}/"+filename);
						$('#links_foto').val("<?php echo base_url()?>media/images/galleryevent/{file_id}/{id}/"+filename);
						$('#linkimages_alert').html('<div align=right onClick="$(\'#linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>Upload Image OK');
					} else{
						$('#linkimages_alert').html('<div align=right onClick="$(\'#linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>'+response);
					}
				}
});
		new AjaxUpload($('#thumb_linkimages'), {
			action: '<?php echo base_url()?>index.php/admin_content/douploadimages/{file_id}/galeryevent/100',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				$('#thumb_linkimages_alert').show('fold',500);
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
					$('#thumb_linkimages_alert').html('<div align=right onClick="$(\'#thumb_linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>Only JPG, PNG or GIF files are allowed');
					return false;
				}
				$('#thumb_linkimages_alert').show('fold',500);
				$('#thumb_linkimages_alert').html('Uploading image...');
			},
			onComplete: function(file, response){
				stat = response.substr(0,7)
				filename = response.substr(10)
				if(stat==="success"){
					$('#thumb_linkimages').attr("src", "<?php echo base_url()?>media/images/galeryevent/{file_id}/"+filename);
					$('#links').val("<?php echo base_url()?>media/images/galeryevent/{file_id}/"+filename);
					$('#thumb_linkimages_alert').html('<div align=right onClick="$(\'#thumb_linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>Upload Image OK');
				} else{
					$('#thumb_linkimages_alert').html('<div align=right onClick="$(\'#thumb_linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>'+response);
				}
			}
		});

		$.ajax({ url: "<?php echo base_url()?>index.php/admin_content/filelist/media__images__galeryevent__{file_id}", context: document.body, success: function(result){
				$("#image_lsit").html(result);
			}
		});
		
		$.ajax({ url: "<?php echo base_url()?>index.php/admin_content/list_foto_event/{file_id}_{id}", context: document.body, success: function(result){
			//	$("td[id=list_foto_event]").html(result);
			}
		});
		
		
	});
</script>

<div class="title">News &raquo; {filename}</div>
<div class="clear">&nbsp;</div>
<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert" id="alert">
<div align=right onClick="$('#alert').hide('fold',1000);" style="color:red;font-weight:bold">X</div>
<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div class="clear">&nbsp;</div>
<form action="<?php echo base_url()?>index.php/admin_content/doeditgalleryevent/file_id/{file_id}/id/{id}" method="POST" name="frmFiles">
	<button type="submit" class=btn>Simpan</button>
	<button type="reset" class=btn>Ulang</button>
	<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/editgalleryevent/file_id/{file_id}';">Kembali</button>
	<?php
		$this->db->where("file_id",$file_id);
		$this->db->where("id",$id);
		$this->db->orderby("dtime","DESC");
		$query = $this->db->get("app_files_contents_event",1,0);
		
		if($data = $query->num_rows()<1)
			{ ?>
			
			<input type=hidden name="id_event" value=1>
			
		<?php }
		else
		{
			$data = $query->row_array();
			$id_now = intval($data['id_event'])+1;
		?>
			<input type=hidden name="id_event" value=<?php echo $id_now; ?>>
		<?php
		}
		$query->free_result();
		//$tmp['id'] = $data['id']+1;
		//$tmp['id'] = $data['id'];
		
	?>
	<br />
	<br />
	<table border="0" cellpadding="0" cellspacing="8" class="panel">
		<tr>
			<td>

				<table border="0" cellpadding="3" cellspacing="2">
					<?php 
					foreach($lang as $row):
						eval("\$title_content= (isset(\$title_content_".$row['kode'].") ? \$title_content_".$row['kode']." : '');");
					?>
						<tr>
							<td>Title <b><?php echo $row['lang']?></b></td>
							<td>:</td>
							<td><input class=input type="text" size="50" name="title_content_<?php echo $row['kode']?>" value="<?php echo $title_content?>" /></td>
						</tr>
					<?php endforeach;?>
					<tr>
						<td>Author</td>
						<td>:</td>
						<td>{author}</td>
					</tr>
					<tr>
						<td>Time</td>
						<td>:</td>
						<td>{dtime}</td>
					</tr>
					<tr>
						<td>Hits</td>
						<td>:</td>
						<td>{hits}</td>
					</tr>
					<tr>
						<td>Publish/Unpublish</td>
						<td>:</td>
						<td><input class=input type="checkbox" name="published" value="1" <?php if($published) echo "checked"; ?>></td>
					</tr>
					<tr>
						<td>Thumbnail</td>
						<td>:</td>
						<td><img src="<?php echo $links?>" id='thumb_linkimages' style='border:1px solid #999999' width="100" height="100">
						<div class="alert" id="thumb_linkimages_alert" style='display:none;float:right'></div></td>
						<input class=input type="hidden" size="80" name="links" id="links" value="<?php echo $links1?>" readonly/>
					</tr>
					<?php 
					foreach($lang as $row):
						eval("\$headline= (isset(\$headline_".$row['kode'].") ? \$headline_".$row['kode']." : '');");
					?>
						<tr>
							<td>Headline <b><?php echo $row['lang']?></b></td>
							<td>:</td>
							<td><textarea name="headline_<?php echo $row['kode']?>" class=input rows=5 cols=80><?php echo $headline?></textarea></td>
						</tr>
					<?php endforeach;?>
					<?php 
					foreach($lang as $row):
						eval("\$content= (isset(\$content_".$row['kode'].") ? \$content_".$row['kode']." : '');");
					?>
						<tr>
							<td colspan="3">Content <b><?php echo $row['lang']?></b></td>
						</tr>
						<tr>
							<td colspan="3" style="background:white"><textarea name="content_<?php echo $row['kode']?>" cols="120" rows="20"  class="tinymce"><?php echo $content?></textarea></td>
						</tr>
					<?php endforeach;?>
					<!--<tr>
						<td rowspan="2">Tambah Gambar</td>
						<td rowspan="2">:</td>
						<td><img src="<?php echo $linktmp?>" id='linkimages' style='border:1px solid #999999'></td>
					</tr>
					<tr>
						<td colspan="3"><div class="alert" id="linkimages_alert" style='display:none;'></div></td>
					</tr>-->
					
					
					</form>
					
					<tr>
						<td colspan="3" id="image_lsitss">
						
						<table border=0 width=100% class=panel style=''>
							<Tr>
								<td>
								<b>Tambah Foto</b>
								</td>
							</Tr>
							<tr>
								<td>
									<table border="0" cellpadding="0" cellspacing="8" class="panel">
			<tr>
			<td>

				<input class=input type="hidden" name="published_foto" value="1">
				<table border="0" cellpadding="3" cellspacing="2" width=100%>
					<?php 
					
					foreach($lang as $row):
						eval("\$headline= (isset(\$headline_".$row['kode'].") ? \$headline_".$row['kode']." : '');");
						
					?>
						<tr>
							<td>Description <?php echo $row['kode']; ?></td>
							<td>:</td>
							<td><input class=input type="text" size="50" name="headline_foto_<?php echo $row['kode']?>" /></td>
						</tr>
						
					<?php endforeach;?>
				
					<tr>
						<td rowspan="3">File</td>
						<td rowspan="3">:</td>
						<td><img src="<?php echo $linktmp?>" id='linkimages' style='border:1px solid #999999' width="100" height="100"></td>
					</tr>
					<tr>
						<td><input class=input type="text" size="50" name="links_foto" id="links_foto" value="<?php echo $links?>" readonly/></td>
					</tr>
					<tr>
						<td><div class="alert" id="linkimages_alert" style='display:none;'></div></td>
					</tr>
				</table>
				</form>
					<input type=hidden name=add_foto id=add_foto value=0>
					<button type="button" class=btn id=tambah_foto onclick="$('#add_foto').val('1'); document.frmFiles.submit()">Tambah</button>
			</td>
		</tr>
	</table>
	
								</td>
							</tr>
							
						</table><br>
						<table border=0 width=100% class=tbl>
									<tr>
									<td colspan=7 class=tbl_head><b>List Foto</b></td>
									</tr>
									<tr>
									<td width=25px class=tbl_head>No</td>
									<td width=100px class=tbl_head>&nbsp;</td>
									<td class=tbl_head>Description</td>
									<td class=tbl_head width=50px>Del</td>
									</tr>
									
									
									<?php 							
									$this->db->where("file_id",$file_id);
									$this->db->where("id",$id);
									$this->db->where("lang",'ina');
									$this->db->groupby("id_event");
									$this->db->orderby("dtime,lang","DESC");
									$querys = $this->db->get("app_files_contents_event");
									$xx=1;
									foreach ($querys->result_array() as $row)
									{
									   echo "<tr>";
									   echo "<td rowspan=2 align=center class=tbl_list>".$xx."</td>";
									   echo "<td rowspan=2 class=tbl_list style='background:url(".$row['links'].");background-repeat:no-repeat;height:100px'>&nbsp;</td>";
									   echo "<td class=tbl_list>".$row['headline']."</td>";
									   echo "<td rowspan=2 class=tbl_list align=center>";
									   ?>
									   
									   <a href="#" onclick="if(confirm('Hapus data ini?'))document.location.href='<?php echo base_url()?>index.php/admin_content/dodelgalleryeventdetail/{file_id}/{id}/<?php echo $row['id_event'];?>'" title="Hapus"><img src="<?php echo base_url()?>media/images/16_del.gif" /></a></td>
									   <?php
								
									   echo "</tr>";
									   echo "<tr>";
									   
										$this->db->where("file_id",$file_id);
										$this->db->where("id",$id);
										$this->db->where("lang",'en');
										$this->db->where("id_event",$row['id_event']);
										$this->db->orderby("dtime,lang","DESC");
										$queryx = $this->db->get("app_files_contents_event");
										$rowx	= @$queryx->row_array($queryx);
									
									   echo "<td class=tbl_list>".$rowx['headline']."</td>";
									   echo "</tr>";
									   $xx++;
									}
									?>
									</table>
						
						</td>
					</tr>
					<tr>
								<td id=list_foto_event>
									
								</td>
							</tr>
				</table>

			</td>
		</tr>
	</table>
	<br />
	<button type="button" class=btn onclick="document.frmFiles.submit()">Simpan</button>
	<button type="reset" class=btn>Ulang</button>
	<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/editgalleryevent/file_id/{file_id}';">Kembali</button>

