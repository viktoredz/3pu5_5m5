<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" href="<?php echo base_url()?>public/themes/login/img/favicon.ico">
    <style type="text/css">
      /* <![CDATA[ */    
        @import url(<?php echo base_url()?>public/themes/login/css/style.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.base.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.orange.css);
      /* ]]> */
    </style>
    <script src="<?php echo base_url()?>plugins/js/jquery-1.6.2.min.js"></script>
    <script src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcore.js"></script>
    <script src="<?php echo base_url()?>plugins/js/jqwidgets/jqxwindow.js"></script>
    <script src="<?php echo base_url()?>plugins/js/autocomplete.js"></script>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="skin-green layout-top-nav fixed">
<table class="login-bg" border="0" height="100%" width="100%">
<tbody>
<tr><td valign="top">
<table id="Table_01" style="margin:0 auto;" align="center" border="0" cellpadding="0" cellspacing="0">
  <tbody>
  <tr>
    <td class="head-login-table" align="center">
      <img src="<?php echo base_url()?>public/themes/sik/dist/img/sms.png" height="60">
    </td>
  </tr>
  <tr>
    <td class="body-login-table">{content}</td>
  </tr>
  </tbody>
</table>
</td>
</tr>
<tr>
  <td style="font-family:Calibri;font-size:10pt;color:#FFFFFF;padding-top: 30px" align="center">Powered by</td>
</tr>
<tr>
  <td align="center"><img src="<?php echo base_url()?>public/themes/sik/dist/img/logo_white.png" height="50"></td>
</tr>
</tbody></table>
  </body>
</html>
