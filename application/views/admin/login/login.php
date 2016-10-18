<div style="padding:50px;margin-left:220px">
<script type="text/javascript" language="javascript">
/* <![CDATA[ */
$(function() {
	$('#login_email').focus(function() {
		if($(this).val()=="email")$(this).val("");
	});

	$('#login_password').focus(function() {
		if($(this).val()=="password")$(this).val("");
	});

	$('#login_email').blur(function() {
		if($(this).val()=="")$(this).val("email");
	});

	$('#login_password').blur(function() {
		if($(this).val()=="")$(this).val("password");
	});
});
/* ]]> */
</script>
<div class="title">Login Admin Panel</div>
<div class="clear">&nbsp;</div>
<?php if($this->session->flashdata('notification')!=""){ ?>
<div class="alert" id="alert">
	<div align=right onClick="$('#alert').hide('slow');" style="color:red;font-weight:bold">X</div>
	<?php echo $this->session->flashdata('notification')?>
</div>
<?php } ?>
<div class="clear">&nbsp;</div>
	<div style="width:412px;text-align:center;border:1px solid silver;padding:20px">
		<form action="<?php echo base_url()?>admin/login" method=post>
			<div id="login_email_div" style="font-size:18px">Username &nbsp; <input type="text" name="email" id="login_email" value="email"></div><br>
			<div id="login_password_div" style="font-size:18px">Password &nbsp; <input type="password" name="password" id="login_password" value="password"></div><br>
			<div class="clear">&nbsp;</div>
			<br>
			<div style="text-align:right">
				<div><input type=submit value='Masuk' class="btn" style="cursor:pointer"> <input type=button value='Lupa Kata Kunci' class="btn" onclick="document.location.href='<?php echo base_url()?>admin_user/forgot_pass';" style="cursor:pointer"></div>
			</div>
		</form>		
	</div>
</div>