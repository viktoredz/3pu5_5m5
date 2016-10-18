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
			@import url(<?php echo base_url()?>plugins/js/jquery/notific8/jquery.notific8.min.css);
			@import url("<?php echo base_url()?>plugins/js/jquery/jqwidgets/styles/jqx.base.css");
			@import url("<?php echo base_url()?>plugins/js/jquery/jqwidgets/styles/jqx.arctic.css");
			@import url("<?php echo base_url()?>plugins/js/jquery/jqwidgets/styles/jqx.fresh.css");
			@import url("<?php echo base_url()?>plugins/js/jquery/jqwidgets/styles/jqx.ui-smoothness.css");
		/* ]]> */
		</style>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jquery.jclock-1.2.0.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/autocomplete/jquery.autocomplete.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/ajaxupload.3.5.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/flexigrid/flexigrid.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/notific8/jquery.notific8.min.js"></script>
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
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxdropdownlist.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxwindow.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxgrid.selection.js"></script>	
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxgrid.edit.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxgrid.filter.js"></script>	
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxgrid.sort.js"></script>		
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxgrid.pager.js"></script>		
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxdatetimeinput.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/globalization/globalize.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxdata.js"></script>	
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxlistbox.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery/jqwidgets/jqxnavigationbar.js"></script>
	<script type="text/javascript">
		var theme = "arctic";
		$("button,submit,reset").jqxInput({ theme: 'fresh', height: '28px' });
		$("input,select").jqxInput({ theme: theme, height: '24px' });
		$("textarea").jqxInput({ theme: theme });
	</script>
	</body>
</html>
