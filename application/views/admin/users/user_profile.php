<!-- JS & CSS for Galley Photo -->
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>plugins/js/image_crud/image_crud/css/fineuploader.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>plugins/js/image_crud/image_crud/css/photogallery.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>plugins/js/image_crud/image_crud/css/colorbox.css" />
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/jquery-ui-1.9.0.custom.min.js"></script>
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/fineuploader-3.2.min.js"></script>
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/jquery.colorbox-min.js"></script>
<script src="<?php echo base_url()?>plugins/js/image_crud/image_crud/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btn_updatePassword').click(function(){
          $('#notification').hide();
          $.ajax({ 
            type: "POST",
            url: "<?php echo base_url()?>admin_user/update_password/{username}",
            data: $('#updatePassword').serialize(),
            success: function(response){
              console.log(response);
               if (response == "1") {
                  $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
                  $('#notification span').html("Password berhasil disimpan");
                  $('#password').html("");
                  $('#npassword').html("");
                  $('#cpassword').html("");
                      $('html, body').animate({
                          scrollTop: $("#top").offset().top
                      }, 300);
               } else {
                  $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
                  $('#notification').show('slow');
                  $('#notification span').html(response);
                      $('html, body').animate({
                          scrollTop: $("#top").offset().top
                      }, 300);
               }
            }
           });    
        });

        $('#btn_back,#btn_back2').click(function(){
            window.location.href="<?php echo base_url()?>admin_user";
        });

        $('#btn_updateProfile').click(function(){
          $('#notification').hide();
          $.ajax({ 
            type: "POST",
            url: "<?php echo base_url()?>admin_user/update_profile/{username}",
            data: $('#updateProfile').serialize(),
            success: function(response){
              $('#notification').html('<div id="information" class="alert alert-warning alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h4>  <i class="icon fa fa-check"></i> Information!</h4><span></span></div>');
              $('#notification').show('slow');
              $('#notification span').html(response);
                  $('html, body').animate({
                      scrollTop: $("#top").offset().top
                  }, 300);
            }
           });    
        });
    });
</script>

 <div id="notification">
</div>
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>
<div class="row" style="background:#FAFAFA">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab">Profil Pengguna</a></li>
      <li><a href="#tab_2" data-toggle="tab">Akun Pengguna</a></li>
    </ul>
    <div class="tab-content">


      <div class="tab-pane " id="tab_2">    
        <!-- <form action="<?php echo base_url()?>disbun/profile_dopasswd" method="post"> -->
        <form name="updatePassword" id="updatePassword">
        <div class="row">
        <div class="col-md-6 col-md-offset-1">
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:90px"><b>Username</b></div>
              </span>
                <input type="text" class="form-control" placeholder="Username" name="username" readonly value="<?php 
                      if(set_value('username')=="" && isset($username)){
                        echo $username;
                      }else{
                        echo  set_value('username');
                      }
                      ?>"/>
              </span>
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:90px"><b>Level Akses</b></div>
              </span>
              <select class="form-control" name="level"/>{level_option}</select>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:90px">Password Lama</div>
              </span>
              <input type="password" class="form-control" placeholder="Password Lama" name="password" id="password"/>
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:90px">Password Baru</div>
              </span>
              <input type="password" class="form-control" placeholder="Password Baru" name="npassword" id="npassword"/>
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <div style="width:90px">Retype password</div>
              </span>
              <input type="password" class="form-control" placeholder="Retype password" name="cpassword" id="cpassword"/>
              <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
        </div>
        <div class="col-xs-2 col-md-offset-1">
            <button type="button" id="btn_updatePassword" class="btn btn-danger btn-block btn-flat">Simpan</button>
            <button type="button" id="btn_back2" class="btn btn-primary btn-block btn-flat">Kembali</button>
        </div>
        </div>
        </form>
      </div>


      <div class="tab-pane active" id="tab_1">    
        <!-- <form action="<?php echo base_url()?>disbun/profile_doupdate" method="post"> -->
        <form name="updateProfile" id="updateProfile">
        <div class="row">
        <div class="col-md-6 col-md-offset-1">
             <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-qrcode" style="width:20px"></i>
              </span>
              <input type="text" class="form-control" placeholder="** Kode" name="code" readonly value="<?php 
                      if(set_value('code')=="" && isset($code)){
                        echo $code;
                      }else{
                        echo  set_value('code');
                      }
                      ?>"/>
            </div>
            <br>
             <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-envelope" style="width:20px"></i>
              </span>
              <input type="text" class="form-control" placeholder="Email" name="email" value="<?php 
                      if(set_value('email')=="" && isset($email)){
                        echo $email;
                      }else{
                        echo  set_value('email');
                      }
                      ?>"/>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-user" style="width:20px"></i>
              </span>
              <input type="text" class="form-control" placeholder="** Nama Lengkap" name="nama" value="<?php 
                      if(set_value('nama')=="" && isset($nama)){
                        echo $nama;
                      }else{
                        echo  set_value('nama');
                      }
                      ?>"/>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-phone" style="width:20px"></i>
              </span>
              <input type="text" class="form-control" placeholder="** No. Tlp" name="phone_number" value="<?php 
                      if(set_value('phone_number')=="" && isset($phone_number)){
                        echo $phone_number;
                      }else{
                        echo  set_value('phone_number');
                      }
                      ?>"/>
            </div>
            <br>
            <div class="row">
            </div>
        </div>
          <div class="col-xs-2 col-md-offset-1">
            <button type="button" id="btn_updateProfile" class="btn btn-warning btn-block btn-flat">Simpan</button>
            <button type="button" id="btn_back" class="btn btn-primary btn-block btn-flat">Kembali</button>
          </div><!-- /.col -->
        </div>
        </form>        
      </div>
  </div><!-- /.form-box -->
</div><!-- /.register-box -->

<script type="text/javascript">
$(function(){
    $("#menu_admin_panel").addClass("active");
    $("#menu_admin_user").addClass("active");

  });
</script>
