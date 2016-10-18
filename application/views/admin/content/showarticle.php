<div class="title">Content Manager &raquo; {filename}</div>
<div class="clear">&nbsp;</div>
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert" id="alert">
<div align=right onClick="$('#alert').hide('fold',1000);" style="color:red;font-weight:bold">X</div>
<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<table cellpadding="1" cellspacing="1" border="0" width="100%">
<tr>
	<td>
		<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/editarticle_form/file_id/{file_id}'">Tambah</button>
		<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/';">Kembali</button>
	</td>
	<td align=right>
	<div class="paging">{start} - {end} dari {count} data 
	<?php if($page_count>1) { ?>|| Pindah halaman : <select class=input onchange="document.location.href='<?php echo base_url()?>index.php/admin_content/editarticle/{segments}page/'+this.value+'.html';">';
		<?php for($i=1;$i<=$page_count;$i++): ?>
				<option value="<?php echo $i?>" <?php if($page==$i) echo "selected"; ?>><?php echo $i?></option>
		<?php endfor ?>
		</select> 
	<?php } ?>
	</div>
	</td>
</tr>
</table><br />
<?php echo $this->session->flashdata('notification')?><br />
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tbl">
		<thead>
			<tr>
				<th class=tbl_head width=5% align=center style="font-size:11px">NO</font></th>
				<th class=tbl_head width=5% align=center style="font-size:11px">ID</font></th>
				<th class=tbl_head width=40% style="font-size:11px">Title</th>
				<th class=tbl_head width=5% style="font-size:11px">Published</th>
				<th class=tbl_head width=20% style="font-size:11px">Author</th>
				<th class=tbl_head width=20% style="font-size:11px">Time</th>
				<th class=tbl_head width=5% style="font-size:11px">Views</th>
				<th class=tbl_head width=5% align=center style="font-size:11px">Detail</th>
				<th class=tbl_head width=5% align=center style="font-size:11px">Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($query as $row):?>
			<tr onMouseOver="bgColor='#EEEEEE'" onmouseout="bgColor='#FFFFFF'">
				<td class=tbl_list align=center><?php echo $start++?>&nbsp;</td>
				<td class=tbl_list align=center><?php echo $row->id?>&nbsp;</td>
				<td class=tbl_list><?php echo $row->title_content?>&nbsp;</td>
				<td class=tbl_list><?php echo ($row->published==1 ? "yes" : "no" )?>&nbsp;</td>
				<td class=tbl_list><?php echo $row->author?>&nbsp;</td>
				<td class=tbl_list><?php echo date("d M Y H:i:s",$row->dtime)?>&nbsp;</td>
				<td class=tbl_list><?php echo $row->hits?>&nbsp;</td>
				<td class=tbl_list align=center><a href="<?php echo base_url()?>index.php/admin_content/editarticle_form/file_id/{file_id}/id/<?php echo $row->id?>" title="Ubah"><img src="<?php echo base_url()?>media/images/16_edit.gif" /></a></td>
				<td class="tbl_list" align="center"><a href="#" onclick="if(confirm('Hapus data ini?'))document.location.href='<?php echo base_url()?>index.php/admin_content/dodelarticle/{file_id}/<?php echo $row->id?>'" title="Hapus"><img src="<?php echo base_url()?>media/images/16_del.gif" /></a></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<br />
		<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/editarticle_form/file_id/{file_id}'">Tambah</button>
		<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_content/';">Kembali</button>
