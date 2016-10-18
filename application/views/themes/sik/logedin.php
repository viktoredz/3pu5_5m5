  <li class="user-header">
    <i class="fa fa-user" style="font-size:50px;color:white"></i>
    <p>
      <?php echo $this->session-> userdata('username')?> ( <?php echo ucfirst($this->session->userdata('level'))?> )
      <br><?php echo $this->session-> userdata('nama')?> <br>
      <?php echo $this->session-> userdata('perusahaan')?>
        <?php echo anchor(base_url()."morganisasi/profile"," Profil Anda ","style='color:white;font-size:14px'")?>
    </p>
  </li>
  <!-- Menu Footer-->
  <li class="user-footer">
    <div class="pull-left">
    	<?php echo anchor(base_url()."morganisasi/"," <i class='fa fa-info-circle'></i> Panduan ","class='btn btn-warning btn-flat'")?>
    </div>
    <div class="pull-right">
    	<?php echo anchor(base_url()."morganisasi/logout"," <i class='fa fa-sign-out'></i> Keluar ","class='btn btn-warning btn-flat'")?>
    </div>
  </li>
