<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{title}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" href="<?php echo base_url()?>public/themes/login/img/favicon.ico">
    <style type="text/css">
        @import url(<?php echo base_url()?>public/themes/sik/bootstrap/css/bootstrap.min.css);
        @import url(<?php echo base_url()?>public/themes/sik/bootstrap/css/font-awesome.min.css);
        @import url(<?php echo base_url()?>public/themes/sik/bootstrap/css/ionicons.min.css);
        @import url(<?php echo base_url()?>public/themes/sik/plugins/datatables/dataTables.bootstrap.css);
        @import url(<?php echo base_url()?>public/themes/sik/dist/css/sik.css);
        @import url(<?php echo base_url()?>public/themes/sik/dist/css/skins/skin-green.min.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.base.css);
        @import url(<?php echo base_url()?>plugins/js/jqwidgets/styles/jqx.bootstrap.css);
    </style>
	
    <script src="<?php echo base_url()?>plugins/js/jQuery-2.1.3.min.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxtooltip.js"></script>
	
	  <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdatatable.js"></script>
	  <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxtreegrid.js"></script>
	
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxwindow.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.selection.js"></script>    
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.edit.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.filter.js"></script>   
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.sort.js"></script>     
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.pager.js"></script>        
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxgrid.columnsresize.js"></script>        
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxdatetimeinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/globalization/globalize.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxmaskedinput.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxnavigationbar.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxtabs.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcombobox.js"></script>
    <link rel="stylesheet" href="<?php echo base_url()?>plugins/js/jquery-ui.css" />
    <script src="<?php echo base_url()?>plugins/js/jquery-ui.js"></script>
	 
    <script type="text/javascript">
        var theme = "bootstrap";
    </script>
  </head>
  <body class="skin-green sidebar-mini wysihtml5-supported">

    <div class="wrapper">
    <div id="top">
      <img id="logo_pus" src="<?php echo base_url()?>public/themes/sik/dist/img/logo.gif">
      <div id="pus_name">PUSKESMAS {puskesmas}<br>{district}</div>
      <img id="logo_epus" class="hidden-xs" src="<?php echo base_url()?>public/themes/sik/dist/img/sms.png">
    </div>
      
      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url()?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>M</b>O</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Manajemen</b> Organisasi</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i>
                  <span class="hidden-xs"><?php echo ucwords($this->session-> userdata('username'));?></span>
                </a>
                <ul class="dropdown-menu">
                  {login}
                </ul>
              </li>
              <li class="dropdown notifications-menu">
                <a href="<?php echo base_url()."morganisasi/logout" ?>">
                  <i class="fa fa-sign-out"></i>
                  <span class="hidden-xs">Keluar</span>
                </a>
              </li>
            </ul>
          </div>

          <div class="hidden-md hidden-lg hidden-sm">
            <a href="<?php echo base_url()."morganisasi/logout" ?>" class="sidebar-link">
              <i class="fa fa-sign-out"></i>
              <span>Keluar</span>
            </a>
            <a href="<?php echo base_url()."morganisasi/profile" ?>" class="sidebar-link">
              <i class="fa fa-user"></i>
              <span><?php echo ucwords($this->session-> userdata('username'));?></span>
            </a>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
    		  {menu}
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            {title_group}
            <small>{title_form}</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo base_url()?>"><i class="fa fa-dashboard"></i> {title_group}</a></li>
            <li class="active">{title_form}</li>
          </ol>
        </section>

        <section class="content">
          {content}
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="row">
          <div class="hidden-xs col-md-6">
            Copyright PT.Infokes Indonesia © 2006-2016 - All Right Reserved<br>
            InfoKes Manajemen Organisasi - ePuskesmas
          </div>
          <div class="hidden-xs col-md-6 pull-right" style="text-align:right">
            <img height="30" src="<?php echo base_url()?>public/themes/sik/dist/img/logo.png">
          </div>
        </div>
      </footer>
    </div><!-- ./wrapper -->

    <script src="<?php echo base_url()?>public/themes/sik/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src='<?php echo base_url()?>public/themes/sik/plugins/fastclick/fastclick.min.js'></script>
    <script src="<?php echo base_url()?>public/themes/sik/dist/js/app.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()?>public/themes/sik/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
  </body>
</html>