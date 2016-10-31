<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<form action="<?php echo base_url()?>sms/pbk/{action}/{cl_pid}/{cl_phc}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->
          <div class="box-footer pull-right" style="width:100%;text-align:right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>sms/pbk'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Puskesmas</label>
              <select name="cl_phc" class="form-control">
                  <?php foreach ($phc as $row ) { ?>
                    <option value="<?php echo $row->code; ?>" <?php 
                      if(set_value('cl_phc')=="" && isset($cl_phc)){
                        $phc = $cl_phc;
                      }else{
                        $phc = set_value('cl_phc');
                      }
                      if($phc==$row->code) echo " selected";
                      ?>><?php echo $row->keyword; ?> : <?php echo $row->value; ?></option>
                  <?php }?>
              </select>
                </div>
            <div class="form-group">
              <label>No RM</label>
              <input type="text" class="form-control" name="cl_pid" placeholder="No RM" <?php if($action=="edit") echo "readonly"; ?> value="<?php 
                if(set_value('cl_pid')=="" && isset($cl_pid)){
                  echo $cl_pid;
                }else{
                  echo  set_value('cl_pid');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>NIK</label>
              <input type="text" class="form-control" name="nik" placeholder="No NIK" value="<?php 
                if(set_value('nik')=="" && isset($nik)){
                  echo $nik;
                }else{
                  echo  set_value('nik');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>No BPJS</label>
              <input type="text" class="form-control" name="bpjs" placeholder="No BPJS" value="<?php 
                if(set_value('bpjs')=="" && isset($bpjs)){
                  echo $bpjs;
                }else{
                  echo  set_value('bpjs');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Nomor (+62)</label>
              <input type="text" class="form-control" name="nomor" placeholder="+62" value="<?php 
                if(set_value('nomor')=="" && isset($nomor)){
                  echo $nomor;
                }else{
                  echo  set_value('nomor');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Nama</label>
              <input type="text" class="form-control" name="nama" placeholder="Nama" value="<?php 
                if(set_value('nama')=="" && isset($nama)){
                  echo $nama;
                }else{
                  echo  set_value('nama');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Alamat</label>
              <input type="text" class="form-control" name="alamat" placeholder="Alamat" value="<?php 
                if(set_value('alamat')=="" && isset($alamat)){
                  echo $alamat;
                }else{
                  echo  set_value('alamat');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Grup</label>
              <select  name="id_sms_grup" id="id_sms_grup" class="form-control">
                  <option value="">-- Pilih Grup --</option>
                  <?php foreach($grupoption as $row) : ?>
                    <?php 
                    if(isset($id_sms_grup) && $id_sms_grup==$row->id_grup){
                      $select = $row->id_grup == $id_sms_grup ? 'selected' : '';
                    }elseif(set_value('id_sms_grup')!=""){
                      $select = $row->id_grup == set_value('id_sms_grup') ? 'selected' : '';
                    }else{
                      $select ='';
                    } 
                    ?>
                    <option value="<?php echo $row->id_grup ?>" <?php echo $select ?>><?php echo $row->nama ?></option>
                  <?php endforeach ?>
              </select>
            </div>
          </div>
          </div><!-- /.box-body -->
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</form>
<script>
	$(function () {	
    $("#menu_esms").addClass("active");
    $("#menu_sms_pbk").addClass("active");
	});
</script>
