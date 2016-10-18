<table cellpadding=2 cellspacing=2 width=100% border=0 class="tbl">
<thead>
	<tr>
		<th class="tbl_head" style="font-size:11px">No.</th>
		<th class="tbl_head" style="font-size:11px">Image</th>
		<th class="tbl_head" style="font-size:11px">Filename</th>
		<th class="tbl_head" style="font-size:11px">Size | Date</th>
		<th class="tbl_head" style="font-size:11px">Link</th>
	</tr>
</thead>
<tbody>
<?php 
$i=0;
foreach($files as $item=>$file):
	$i++;?>
	<tr bgcolor="#FFFFFF" title="<?php echo $file['name']?>">
		<td><?php echo $i?>.</td>
		<td><img width="60" src="<?php echo base_url().$file['relative_path']."/".$file['name']?>"></td>
		<td><?php echo substr($file['name'],0,20)?>...</td>
		<td><?php echo number_format($file['size']/1024,2)?> Kb<br><?php echo date("d-m-Y H:i:s",$file['date'])?></td>
		<td><input type="text" size="60" value="<?php echo base_url().$file['relative_path']."/".$file['name']?>" class="input" onFocus="select()"></td>
	</tr>
<?php endforeach;?>
</tbody>
</table>
