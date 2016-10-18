<script>
	$(function() {
		$("#participant").fcbkcomplete({
			json_url: "<?php echo base_url()?>index.php/msg/get_userlist.html",
			addontab: true,                   
			maxitems: 20,
			height: 10,
			cache: true,
			filter_selected: true,
			filter_case: false
		});

		$('#btn_save').click(function(){
			$.ajax({ 
				type: "POST",
				url: "<?php echo base_url()?>index.php/msg/do{act}/",
				data: $('#frmMsg').serialize() + tinyMCE.get('mmessage').getContent(),
				success: function(response){
					 if(response>0){
						 $.notific8('Notification', {
						  life: 3000,
						  message: 'Save data succesfully.',
						  heading: 'Saving data',
						  theme: 'lime2'
						});

						$('#frmMsg')[0].reset();
						get_message(response);

					 }else{
						 $.notific8('Notification', {
						  life: 5000,
						  message: response,
						  heading: 'Saving data FAIL',
						  theme: 'red2'
						});
					 }
				}
			 }); 		
		});
	});
</script>
<table width="100%" cellspacing="2" cellpadding="2" style="background:#FFFFFF;border-bottom:1px solid #DDDDDD">
	<tr height="25">
		<td width="10%" style="padding-left:15px">Subject</td>
		<td width="1%">:</td>
		<td>
			 <input type="text" class="input2" id="msubject" name="msubject">
		</td>
	</tr>
	<tr height="25">
		<td style="padding-left:15px">To</td>
		<td width="1%">:</td>
		<td>
			 <select class="input2" id="participant" name="participant"></select>
		</td>
	</tr>
</table>
