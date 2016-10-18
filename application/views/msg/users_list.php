<div style="padding:0px;background:#FFFFFF;height:100%">
<?php foreach($users as $row){ ?>
	<div style="position:relative;width:97%;padding:5px;border-bottom:1px solid #DFDFDF">
		<div style="font-size:12px;;font-weight:bold"><?php echo $row['name_display'] ?></div>
		<div style="font-size:11px;">NIP. <?php echo $row['nip'] ?></div>
		<div style="font-size:11px;"><?php echo $row['username'] ?></div>
		<div style="font-size:11px;"><?php echo $row['nama_balai'] ?></div>
	</div>
<?php } ?>
</div>
