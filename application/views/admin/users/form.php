<div class="row">
   <div class="col-md-12">
     <div class="register-logo">
        <b>Form </b>Pendaftaran
      </div>
    </div>
</div>
<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div class="row">
  <form action="<?php echo base_url()?>admin_user/add" method="post">
  <div class="col-md-6">
    <p class="login-box-msg">Silahkan tentukan data login:</p>
      <div class="form-group has-feedback">
         <input type="text" class="form-control" placeholder="Kode" name="code" readonly value="<?php 
                      if(set_value('code')=="" && isset($code)){
                        echo $code;
                      }else{
                        echo  set_value('code');
                      }
                      ?>"/>
        <span class="glyphicon glyphicon-qrcode form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username" value="<?php 
                if(set_value('username')=="" && isset($username)){
                  echo $username;
                }else{
                  echo  set_value('username');
                }
                ?>"/>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" value="<?php 
                if(set_value('password')=="" && isset($usepasswordrname)){
                  echo $password;
                }else{
                  echo  set_value('password');
                }
                ?>"/>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Retype password" name="cpassword" value="<?php 
                if(set_value('cpassword')=="" && isset($cpassword)){
                  echo $cpassword;
                }else{
                  echo  set_value('cpassword');
                }
                ?>"/>
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>    
      <br>
      <div class="input-group">
        <span class="input-group-addon">
          <div style="width:80px">Level Akses</div>
        </span>
        <select class="form-control" name="level"/>
          <option value="administrator" <?php 
                if(isset($level) && $level=="administrator")  echo "selected";
                ?>>Administrator</option>
          <option value="ketukpintu" <?php 
                if(isset($level) && $level=="ketukpintu")  echo "selected";
                ?>>Ketukpintu</option>
          <option value="guest" <?php 
                if(isset($level) && $level=="guest")  echo "selected";
                ?>>Guest</option>
          <option value="inventory" <?php 
                if(isset($level) && $level=="inventory")  echo "selected";
                ?>>Inventory</option>
          <option value="kepegawaian" <?php 
                if(isset($level) && $level=="kepegawaian")  echo "selected";
                ?>>Kepegawaian</option>
          <option value="keuangan" <?php 
                if(isset($level) && $level=="keuangan")  echo "selected";
                ?>>Keuangan</option>
          <option value="sms" <?php 
                if(isset($level) && $level=="sms")  echo "selected";
                ?>>Sms</option>
        </select>
      </div>
      <br>
      <div class="row">
        <div class="col-xs-6 pull-right">
          <button type="submit" class="btn btn-danger btn-block btn-flat">Simpan</button>
          <button type="button" id="btn_back" class="btn btn-primary btn-block btn-flat">Kembali</button>
        </div><!-- /.col -->
      </div>
  </div><!-- /.form-box -->

  <div class="col-md-6">
    <p class="login-box-msg">Silahkan lengkapi data profil :</p>
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
          <i class="fa fa-envelope" style="width:20px"></i>
        </span>
        <input type="text" class="form-control" placeholder="** Email" name="email" value="<?php 
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
    </form>        

  </div><!-- /.form-box -->
</div><!-- /.register-box -->
<script type="text/javascript">
$(function(){
    $('#btn_back').click(function(){
        window.location.href="<?php echo base_url()?>admin_user";
    });

    $("#menu_admin_panel").addClass("active");
    $("#menu_admin_user").addClass("active");

  });
</script>
