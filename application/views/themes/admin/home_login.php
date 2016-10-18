<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/1998/REC-html40-19980424/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
		<title>CMS Admin {title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style type="text/css">
		/* <![CDATA[ */
			@import url(<?php echo base_url()?>public/themes/admin/css/style.css);
			@import url(<?php echo base_url()?>public/themes/admin/css/menu.css);
			@import url(<?php echo base_url()?>plugins/js/jquery-ui-1.10.1.custom/css/smoothness/jquery-ui-1.10.1.custom.min.css);
			@import url(<?php echo base_url()?>plugins/js/flexigrid/flexigrid.css);
			@import url(<?php echo base_url()?>plugins/js/jquery/autocomplete/main.css);
			@import url(<?php echo base_url()?>plugins/js/jquery/tooltip/screen.css);
			@import url(<?php echo base_url()?>plugins/js/colorbox-master/colorbox.css);
		/* ]]> */
		</style>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jquery-1.6.1.min.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jquery.jclock-1.2.0.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/tooltip/jquery.tooltip.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/autocomplete/jquery.autocomplete.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jquery.json-1.3.min.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/ajaxupload.3.5.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jquery.cycle.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/colorbox-master/jquery.colorbox.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/easySlider1.7.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/print.js"></script>
	</head>
	<body>
	<div style="height:45px;">
		<img src="<?php echo base_url()?>public/themes/admin/images/icon.png" align="right">
	</div>
	<div class="clear">&nbsp;</div>
	<div id="area">
		<div id="panel-menu">
			<div id="menu">
				<?php echo anchor("admin","Home Admin","class='link1'");?> | 
				<?php echo anchor("","Dashboard","class='link1' target='_blank'");?> | 
				<?php if($this->session->userdata('level')=="administrator" || $this->session->userdata('level')=="super administrator"){
					echo anchor("admin/logout","Logout","class='link1'");
				}?>
			</div>
		</div>

		<div class="clear">&nbsp;</div>
		<div id="panel-content">

			<div id="left">
				<div id="content">
					<table width=100% cellpadding=0 cellspacing=0 style="background:#EEEEEE">
						<tr>
							<td>
								{content}
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div id="right">
				<div id="panel1">
					{panel}
				</div>
			</div>

		</div>

		<div class="clear">&nbsp;</div>

	</div>
	<br>
	</body>
</html>
