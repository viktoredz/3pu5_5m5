<script>
	$(function() {
		 $("#message-contentx").niceScroll({touchbehavior:false,cursorcolor:"#999",cursoropacitymax:0.6,cursorwidth:8});		
		 var scrollheight = $('#reply')[0].scrollHeight < 230 ? 230 : $('#reply')[0].scrollHeight;
		 $('#message-contentx').animate({ scrollTop:scrollheight}, 1000);
	 }); 		
</script>
<div style="width:100%;height:250px;font-size:11px;color:#666;padding-bottom:5px;background:#EFEFEF" id="message-contentx">
<div style="padding:10px;background:#FFFFFF;min-height:240px;" id="reply">
<?php 
if(isset($msg) && is_array($msg)){
	foreach($msg as $row){ ?>
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

<?php 
	}
}
?>
</div>
</div>

