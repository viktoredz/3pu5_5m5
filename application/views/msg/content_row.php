<div id="message-content-item">
	<div style="font-size:11px;"><?php echo $row['username'] ?></div>
	<div style="font-size:11px;"><?php echo date("d M Y H:i:s",$row['dtime']) ?></div>
	<div style="font-size:14px;"><?php echo $row['mmessage'] ?></div>
</div>
<script>
	$(function() {
		$('#stack').val("{mid}__<?php echo $row['dtime']?>");
	}); 		
</script>
