<script type="text/javascript">
	$(function() {
		$("#frm").submit(function() {
			var act = '<?php echo base_url(); ?>index.php/admin_master_penanggungjawab/index';
			if(jQuery.trim($("input[name='nip']").val()) !="") act += '/nip/' + jQuery.trim($("input[name='nip']").val());
			if(jQuery.trim($("input[name='nama']").val()) !="") act += '/nama/' + $("input[name='nama']").val();
			window.location= act;
			return false;
		});
	});
</script>
<div class="clear">&nbsp;</div>
<form action="" method="post" name="frm" id="frm">
	<br />
	<table border="0" cellpadding="0" cellspacing="8" class="panel" width=80%>
		<tr>
			<td>
				<table width=100% border="0" cellpadding="3" cellspacing="2">
					<tr>
						<td>NIP</td>
						<td>:</td>
						<td><input class=input type="text" size="10" name="nip" value="<?php echo $nip?>" /></td>
						<td><button type="submit" class="btn"> Cari </button></td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td><input class=input type="text" size="50" name="nama" value="<?php echo $nama?>" /></td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
	<br />
</form>
<div class="clear">&nbsp;</div>
