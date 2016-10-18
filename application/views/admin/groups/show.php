<div style="padding:10px">
<div class="title">Groups</div>
<div class="clear">&nbsp;</div>
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert" id="alert">
<div align=right onClick="$('#alert').hide('fold',1000);" style="color:red;font-weight:bold">X</div>
<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<form action="<?php echo base_url()?>index.php/admin_group/dodel_multi" method="POST" name="frmGroups">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td>
	<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_group/add'">Tambah</button>
	<?php 
		$button=$this->verifikasi_icon->del_button('admin_group');
		echo $button;
	?>
<!--	<button type="submit" class=btn onclick="if(!confirm('Hapus semua data yang dipilih?'))return false;">Hapus</button>-->
	
	
	</td><td align=right>
	<div class="paging">{start} - {end} dari {count} data 
	<?php if($page_count>1) { ?>|| Pindah halaman : <select class=input onchange="document.location.href='<?php echo base_url()?>index.php/admin_group/index/'+this.value+'.html';">';
		<?php for($i=1;$i<=$page_count;$i++): ?>
				<option value="<?php echo $i?>" <?php if($page==$i) echo "selected"; ?>><?php echo $i?></option>
		<?php endfor ?>
		</select> 
	<?php } ?>
	</div>
</td></tr></table><br />
<?php echo $this->session->flashdata('notification')?><br />
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tbl">
		<thead>
			<tr>
				<th class=tbl_head width=5% style="font-size:11px">&nbsp;</th>
				<th class=tbl_head width=5% align=center style="font-size:11px">NO</font></th>
				<th class=tbl_head width=40% style="font-size:11px">Level</th>
				<th class=tbl_head width=5% align=center style="font-size:11px">Ubah</th>
				<th class=tbl_head width=5% align=center style="font-size:11px">Hapus</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($query as $row):?>
			<tr onMouseOver="bgColor='#EEEEEE'" onmouseout="bgColor='#FFFFFF'" bgcolor="#FFFFFF">
				<td class=tbl_list align=center><input type="checkbox" name="id[]" value="<?php echo $row->level?>" /></td>
				<td class=tbl_list align=center><?php echo $start++?>&nbsp;</td>
				<td class=tbl_list><?php echo $row->level?>&nbsp;</td>
				<td class=tbl_list align=center><a href="<?php echo base_url()?>index.php/admin_group/edit/<?php echo $row->level?>" title="Ubah"><img src="<?php echo base_url()?>media/images/16_edit.gif" /></a></td>
				<td class="tbl_list" align="center">
				<?php 
					$linkData="index.php/admin_group/dodel/".$row->level;
					$testLink=$this->verifikasi_icon->del_icon('admin_group',$linkData);
					echo $testLink;
//					$level=$this->session->userdata('level');
//					if($level=="super administrator"){ 
				?>	
					
				
					</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<br />
	
	<button type="button" class=btn onclick="document.location.href='<?php echo base_url()?>index.php/admin_group/add'">Tambah</button>
	<?php 
//		$linkbutton="index.php/admin_group/dodel/".$row->level;
		$button=$this->verifikasi_icon->del_button('admin_group');
		echo $button;
					
	?>
	
</form>
</div>