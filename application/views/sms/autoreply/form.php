<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
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

<section class="content">
<form action="<?php echo base_url()?>sms/autoreply/{action}/{id}" method="POST" name="">
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
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>sms/autoreply'">Kembali</button>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label>Menu</label>
              <select name="code_sms_menu" class="form-control">
                <?php 
                if(set_value('code_sms_menu')=="" && isset($code_sms_menu)){
                  $menu = $code_sms_menu;
                }else{
                  $menu = set_value('code_sms_menu');
                }
                ?>
                <?php foreach ($menuoption as $row ) { ;?>
                  <option value="<?php echo $row->code; ?>" <?php echo ($menu==$row->code ? "selected":"") ?>><?php echo strtoupper($row->code); ?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label>Kata Kunci</label>
              <input type="text" class="form-control" name="katakunci" placeholder="Kata Kunci" value="<?php 
                if(set_value('katakunci')=="" && isset($katakunci)){
                  echo $katakunci;
                }else{
                  echo  set_value('katakunci');
                }
                ?>">
            </div>
            <div class="form-group">
              <label>Pesan</label>
              <textarea class="form-control" name="pesan" id="Pesan" placeholder="Pesan" ><?php 
                if(set_value('pesan')=="" && isset($pesan)){
                  echo $pesan;
                }else{
                  echo  set_value('pesan');
                }
                ?></textarea>
              <label id="counting" class="pull-right" style="padding:2px">160</label>
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select name="id_sms_tipe" class="form-control">
                <?php 
                if(set_value('id_sms_tipe')=="" && isset($id_sms_tipe)){
                  $tipe = $id_sms_tipe;
                }else{
                  $tipe = set_value('id_sms_tipe');
                }
                ?>
                <?php foreach ($tipeoption as $row ) { ;?>
                  <option value="<?php echo $row->id_tipe; ?>"  <?php echo ($tipe==$row->id_tipe ? "selected":"") ?>><?php echo $row->nama; ?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label>Periode SMS Aktif </label>
              <div class="row">
                <div class="col-md-6">
                  <?php 
                  if(set_value('tgl_mulai')=="" && isset($tgl_mulai)){
                    $tgl_mulai = $tgl_mulai;
                  }else{
                    $tgl_mulai = strtotime(set_value('tgl_mulai'));
                  }
                  if(set_value('tgl_akhir')=="" && isset($tgl_akhir)){
                    $tgl_akhir = $tgl_akhir;
                  }else{
                    $tgl_akhir = strtotime(set_value('tgl_akhir'));
                  }
                  ?>
                  <div id='tgl_mulai' name="tgl_mulai" value="<?php echo date("Y-m-d",$tgl_mulai); ?>"></div>
                </div>
                <div class="col-md-6">
                  <div id='tgl_akhir' name="tgl_akhir" value="<?php echo date("Y-m-d",$tgl_akhir); ?>"></div>
                </div>
              </div>
            </div>
          </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>

<script>
  $(function () { 
    $("#menu_esms").addClass("active");
    $("#menu_sms_autoreply").addClass("active");

    $("#tgl_mulai").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});
    $("#tgl_akhir").jqxDateTimeInput({ formatString: 'dd-MM-yyyy', theme: theme});

    $("#Pesan").keyup(function(){
      var max = 160;
      var len = $(this).val().length;
      $("#counting").html(max-len);
    }).keyup();

  });
</script>
