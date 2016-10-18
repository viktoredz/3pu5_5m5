<input type="hidden" id="location" value="{location}">
<input type="hidden" id="stack">
<link rel="stylesheet" href="<?php echo base_url()?>plugins/js/fcbkcomplete/style.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="<?php echo base_url()?>plugins/js/fcbkcomplete/jquery.fcbkcomplete.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>plugins/js/tiny_mce/tiny_mce.js"></script>
<script>
	$(function() {
		$("#message-list").niceScroll({touchbehavior:false,cursorcolor:"#999",cursoropacitymax:0.6,cursorwidth:8});		
		setInterval("get_message_list($('#location').val());",10000);
		setInterval("get_message_recent();",10000);

		$('.btn_users').click(function(){
			$('#popup').dialog({
				width: 400,
				height: 320,
				show: "blind",
				modal: true
			});
		});

		$('.btn_new').click(function(){
			 $('#title').html("Kirim Pesan Baru");
			 $(".btn_users").hide();
			 $(".btn_reply").hide();

			$.ajax({ 
				type: "GET",
				url: "<?php echo base_url()?>index.php/msg/compose/",
				success: function(response){
					 if(response==0){}
					 else{
						 $('#message-content').html(response);
						 $(".btn_save").show();
					 }
				}
			}); 		
		});

		$('.btn_reply').click(function(){
			var mid = $(this).attr('id').split("__");
			$.ajax({ 
				type: "POST",
				url: "<?php echo base_url()?>index.php/msg/doreply/"+mid[1],
				data: "mmessage="+tinyMCE.get('mmessage').getContent(),
				success: function(response){
					 if(response>0){
						 $.notific8('Notification', {
						  life: 3000,
						  message: 'Save data succesfully.',
						  heading: 'Saving data',
						  theme: 'lime2'
						});
						
						$.ajax({ 
							type: "GET",
							url: "<?php echo base_url()?>index.php/msg/get_message_row/"+mid[1]+"/"+response,
							success: function(response_reply){
								$('#reply').append(response_reply);
								 var scrollheight = $('#reply')[0].scrollHeight < 230 ? 230 : $('#reply')[0].scrollHeight;
								 $('#message-contentx').animate({ scrollTop:scrollheight}, 1000);
							}
						});
						$('#frmMsg')[0].reset();
						$(".btn_users").show("fade");

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

		$(".div_location").click(function(){
			$('#location').val($(this).attr("id"));
			get_message_list($(this).attr("id"));
		});

	});

	function get_message_recent(){
		if($("#stack").val()!=""){
			var mid = $("#stack").val().split("__");
			$.ajax({ 
				type: "GET",
				url: "<?php echo base_url()?>index.php/msg/get_message_recent/"+mid[0]+"/"+mid[1],
				success: function(response){
					 if(response==0){}
					 else{
						$('#reply').append(response);
						 var scrollheight = $('#reply')[0].scrollHeight < 230 ? 230 : $('#reply')[0].scrollHeight;
						 $('#message-contentx').animate({ scrollTop:scrollheight}, 1000);
					 }
				}
			}); 		
		}
	}

	function get_message(mid){
		$.ajax({ 
			type: "GET",
			url: "<?php echo base_url()?>index.php/msg/get_subject/"+mid,
			success: function(response){
				 $('#title').html(response);
			}
		}); 		

		$.ajax({ 
			type: "GET",
			url: "<?php echo base_url()?>index.php/msg/get_message/"+mid,
			success: function(response){
				 $('#message-content').html(response);
			}
		}); 		
		$.ajax({ 
			type: "GET",
			url: "<?php echo base_url()?>index.php/msg/get_users_list/"+mid,
			success: function(response){
				$("#popup").html(response);
			}
		}); 		
		$.ajax({ 
			type: "GET",
			url: "<?php echo base_url()?>index.php/msg/get_users_count/"+mid,
			success: function(response){
				$(".btn_users").html(response);
				$(".btn_users").show("fade");
			}
		}); 		
		$("#btn_save").hide();
		$(".btn_reply").attr('id','btn_reply__'+mid);
		$(".btn_reply").show();
		$('#frmMsg')[0].reset();
		get_message_list($('#location').val());
	}

	function get_message_list(location){
		$.ajax({ 
			type: "GET",
			url: "<?php echo base_url()?>index.php/msg/get_message_list/"+location,
			success: function(response){
				 $('#message-content-list').html(response);
				 $('#message-content-list').height($('#message-content-list')[0].scrollHeight);
			}
		 }); 	

		$(".div_location").css('font-weight','normal');
		$(".div_location[id='"+location+"']").css('font-weight','bold');
	}

	function init_mce(elm,heig,widt){
		tinyMCE.init({
			mode : "exact",
			elements : elm,
			theme : "simple",
			height: heig,
			width:widt
		});
	}

	init_mce("mmessage",170,740);
	get_message_list($('#location').val());


</script>
<div id="popup" style="display:none"></div>
<div style="width:99%;background-color:#FFFFFF;-moz-border-radius:5px;border-radius:5px;border:4px solid #ebebeb;position:relative;float:left">
	<form id="frmMsg" method="post">
	<table cellspacing="0" cellpadding="0" width="90%">
		<tr height="40">
			<td width="30%" style="border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;height:30px;">
				<div style="font-weight:bold" class="div_location" id="inbox">
					Inbox 
				</div>
				<div class="div_location" id="trash">
					 Trash 
				</div>
				<div class="div_location" id="archived">
					Archived
				</div>
			</td>
			<td style="border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;">
				<div style="position:relative;float:left;font-size:15px;font-weight:bold;color:#666;padding:15px" id="title">
					{title}
				</div>
				<div style="position:relative;float:right;padding:10px" id="option">
					<button type="button" id="btn_users" class="btn_users" style="display:none">{users} Users</button>
					<button type="button" id="btn_new" class="btn_new">Kirim Pesan Baru</button>
				</div>
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;">
				<div style="width:100%;height:450px;font-size:11px;color:#666;" id="message-list">
					<div id="message-content-list" class="message-content-list" style="height:451px"></div>
				</div>
			</td>
			<td  style="border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;">
				<div style="width:100%;height:250px;font-size:11px;color:#666;padding-bottom:5px;background:#EFEFEF" id="message-content">
					<div id="message-content-content">
						{message-content}   
					</div>
				</div>
				<div style="border-top:1px solid #DDDDDD;width:100%;height:200px;font-size:11px;color:#666;background:#EFEFEF;" id="message-compose">
					<div style="padding:10px;position:relative;float:left;width:700px">
						<textarea id="mmessage" name="mmessage"></textarea>
					</div>
					<div style="padding:10px;position:relative;float:right;width:60px">
						<button type="button" id="btn_save">Kirim Pesan</button>
						<button type="button" class="btn_reply" id="btn_reply" style="display:none">Kirim Pesan</button>
					</div>
				</div>
			</td>
		</tr>
	</table>
	</form>
</div>
