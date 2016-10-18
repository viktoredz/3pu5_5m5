<script>
	$(function() {
		$(".div_del").click(function(){
			var mid = $(this).attr('id').split("__");
			if($('#location').val()=="trash"){
				if(confirm("Delete message forever ? ")){
					$.ajax({ 
						type: "GET",
						url: "<?php echo base_url()?>index.php/msg/dodel/"+mid[1]+"/trash/",
						success: function(response){
							get_message_list($('#location').val());
						}
					 }); 		
				}
			}else{
				if(confirm("Move message to trash ? ")){
					$.ajax({ 
						type: "GET",
						url: "<?php echo base_url()?>index.php/msg/domove/"+mid[1]+"/trash/",
						success: function(response){
							get_message_list($('#location').val());
						}
					 }); 		
				}
			}
		});

		$(".div_arc").click(function(){
			var mid = $(this).attr('id').split("__");
			if($('#location').val()=="inbox"){
				if(confirm("Move message to archive ? ")){
					$.ajax({ 
						type: "GET",
						url: "<?php echo base_url()?>index.php/msg/domove/"+mid[1]+"/archived",
						success: function(response){
							get_message_list($('#location').val());
						}
					 }); 		
				}
			}else{
				if(confirm("Move message to inbox ? ")){
					$.ajax({ 
						type: "GET",
						url: "<?php echo base_url()?>index.php/msg/domove/"+mid[1]+"/inbox",
						success: function(response){
							get_message_list($('#location').val());
						}
					 }); 		
				}
			}
		});

		$(".message-list-item-inside").click(function(){
			var mid = $(this).attr('id').split("_");
			get_message(mid[1]);
		});

		$(".message-list-item").mouseover(function(){
			var mid = $(this).attr('id').split("_");
			$('#div_arc__'+mid[1]).show();
			$('#div_del__'+mid[1]).show();		
		});

		$(".message-list-item").mouseout(function(){
			var mid = $(this).attr('id').split("_");
			$('#div_arc__'+mid[1]).hide();
			$('#div_del__'+mid[1]).hide();		
		});
	});
</script>

<div style="padding:0px;background:#FFFFFF;height:100%">
<?php foreach($list as $row){ ?>
	<div class="message-list-item" id="message-list-item_<?php echo $row['mid'] ?>" style="position:relative;width:95%">
		<div class="message-list-item-inside" id="message-list-item-inside_<?php echo $row['mid'] ?>">
			<div style="font-size:11px;position:relative;float:right;padding-right:20px"><?php echo date("d M",$row['dtime']) ?></div>
			<div style="width:320px;font-size:14px;<?php echo ($row['unread']>0 ? "font-weight:bold" : "")?>"><?php echo $row['msubject'] ?></div>
			<div style="font-size:11px;width:310px;overflow:hidden;<?php echo ($row['unread']>0 ? "font-weight:bold" : "")?>"><?php echo $row['mmessage'] ?></div>
		</div>
		<div class="div_del" id="div_del__<?php echo $row['mid'] ?>" style="float:right;position:absolute;top:2px;right:10px;display:none;width:10px;z-index:999">x</div>
		<div class="div_arc" id="div_arc__<?php echo $row['mid'] ?>" style="float:right;position:absolute;top:15px;right:10px;display:none;width:10px;z-index:999">o</div>
	</div>
<?php } ?>
</div>
