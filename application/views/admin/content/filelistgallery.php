<script type="text/javascript">
	$(function(){
		$("#cekcek").click(function(){
			if($(this).is(':checked')){
				 $(":checkbox[name^='fileshow']").attr('checked', true);	
			}else{
				 $(":checkbox[name^='fileshow']").attr('checked', false);	
			}
		});
	});
</script>
<table cellpadding=2 cellspacing=2 width=100% border=0>
	<form action="<?php echo base_url()?>index.php/admin_content/doeditgallerylist/file_id/{file_id}/id/{id}" method="POST" name="frmFiles">
	<tr valign=top>
		<td colspan=2 align=center>
			<button type="submit" class=btn>Simpan</button>
			<button type="reset" class=btn>Ulang</button>
		</td>
	</tr>
	<tr valign=top>
		<td width="50%">
		<table cellpadding=2 cellspacing=2 width=100% border=0 class="tbl">
			<thead>
				<tr>
					<th class="tbl_head" style="font-size:11px;cursor:pointer"><input type="checkbox" id="cekcek"></th>
					<th class="tbl_head" style="font-size:11px">Filename</th>
					<th class="tbl_head" style="font-size:11px">Module</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$limit = ceil(count($filelist)/2);
			$i=0;
			foreach($filelist as $item=>$file):
				$i++;
				?>
				<tr bgcolor="#FFFFFF" title="<?php echo $file['filename']?>">
					<td><input type="checkbox" name="fileshow[]" value="<?php echo $file['id']?>" <?php echo ($file['checked']==1 ? "checked" : "") ?>></td>
					<td><?php echo $file['filename']?></td>
					<td align=center><?php echo $file['module']?></td>
				</tr>
			<?php 
			if($i==$limit){
			?>
					</tbody>
					</table>
				</td>
				<td width="50%">
					<table cellpadding=2 cellspacing=2 width=100% border=0 class="tbl">
					<thead>
						<tr>
							<th class="tbl_head" style="font-size:11px">&nbsp;</th>
							<th class="tbl_head" style="font-size:11px">Filename</th>
							<th class="tbl_head" style="font-size:11px">Module</th>
						</tr>
					</thead>
					<tbody>
			<?php
			}
			endforeach;
			?>
			</tbody>
			</table>
		</td>
	</tr>
	</form>
</table>
