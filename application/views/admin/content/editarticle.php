<script type="text/javascript">
	$(function() {
		tinymce.init({
			selector: "textarea",
			theme: "modern",
			plugins: [
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor"
			],
			toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
			toolbar2: "print preview media | forecolor backcolor emoticons",
			image_advtab: true,
			convert_urls:false,
			relative_urls:false,
			forced_root_block : '',
			force_p_newlines : 'false',
			remove_linebreaks : false,
			force_br_newlines : 'true',
			remove_trailing_nbsp : false,
			verify_html : false,
			templates: [
				{title: 'Test template 1', content: 'Test 1'},
				{title: 'Test template 2', content: 'Test 2'}
			]
		});

		new AjaxUpload($('#linkimages'), {
			action: '<?php echo base_url()?>index.php/admin_content/douploadimages/{file_id}/article',
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
				if(response==="success"){
					$('#linkimages').attr("src", "<?php echo base_url()?>media/images/article/{file_id}/"+file);
					$('#links').val("<?php echo base_url()?>media/images/article/{file_id}/"+file);
					$('#linkimages_alert').html('<div align=right onClick="$(\'#linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>Upload Image OK<br>Please copy image url above');
				} else{
					$('#linkimages_alert').html('<div align=right onClick="$(\'#linkimages_alert\').hide(\'fold\',500);" style="color:red;font-weight:bold">X</div><br>'+response);
				}
			}
		});
	});
</script>

<div class="title">Article &raquo; {filename}</div>
<div class="clear">&nbsp;</div>
<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert" id="alert">
<div align=right onClick="$('#alert').hide('fold',1000);" style="color:red;font-weight:bold">X</div>
<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div class="clear">&nbsp;</div>
<form action="<?php echo base_url()?>index.php/admin_content/doeditarticle/file_id/{file_id}/id/{id}" method="POST" name="frmFiles">
	<button type="submit" class=btn>Simpan</button>
	<button type="reset" class=btn>Ulang</button>
	<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/editarticle/file_id/{file_id}';">Kembali</button>
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
							<td colspan="3" style="background:white"><textarea name="content_<?php echo $row['kode']?>" cols="120" rows="50"  class="tinymce"><?php echo $content?></textarea></td>
						</tr>
					<?php endforeach;?>
					<tr>
						<td rowspan="3">Upload files/images</td>
						<td rowspan="3">:</td>
						<td><img src="<?php echo $linktmp?>" id='linkimages' style='border:1px solid #999999'></td>
					</tr>
					<tr>
						<td><input class=input type="text" size="80" name="links" id="links" readonly/></td>
					</tr>
					<tr>
						<td><div class="alert" id="linkimages_alert" style='display:none;'></div></td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
	<br />
	<button type="submit" class=btn>Simpan</button>
	<button type="reset" class=btn>Ulang</button>
	<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/editarticle/file_id/{file_id}';">Kembali</button>
</form>
