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


<section class="content">
<form method="POST" id="form-ss">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

          <div class="box-body">
            <div class="form-group">
              <label>Pengirim</label>
              <input readonly type="text" class="form-control" name="nomor" placeholder="nomor" value="<?php echo $nomor;?>">
            </div>
            <div class="form-group">
              <label>Pesan</label>
              <textarea class="form-control" placeholder="Pesan" id="Pesan"><?php echo $pesan ?></textarea>
              <label class="pull-right" style="padding:4px" id="counting">
                160
              </label>
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select id="id_sms_tipe" class="form-control">
                <?php foreach ($tipeoption as $row ) { ;?>
                  <option value="<?php echo $row->id_tipe; ?>" ><?php echo $row->nama; ?></option>
                <?php }?>
              </select>
            </div>
          </div>
          <div class="box-footer pull-right">
            <button type="button" id="btn-draft" class="btn btn-success">Draft</button>
            <button type="button" id="btn-send" class="btn btn-primary">Kirim</button>
            <button type="reset" id="btn-close" class="btn btn-warning">Batal</button>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>
<script type="text/javascript">
  $(function () { 
    $("#Pesan").keyup(function(){
      var max = 160;
      var len = $(this).val().length;
      $("#counting").html(max-len);
    }).keyup();

    $("#btn-close").click(function(){
      close_popup();
    });

    $("#btn-draft").click(function(){
      proses('draft');
    });

    $("#btn-send").click(function(){
      proses('kirim');
    });

    function proses(status){
       var data = new FormData();
       $('#notice-content').html('<div class="alert">Mohon tunggu....</div>');
        $('#notice').show();

        data.append('status', status);
        data.append('id_sms_tipe', $('#id_sms_tipe').val());
        data.append('pesan', $('#Pesan').val());
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."sms/opini/reply/".$id?>',
            data : data,
            success : function(response){
              var res  = response.split("|");
              if(res[0]=="OK"){
                  $('#notice').hide();
                  $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                  $('#notice').show();

                  $("#jqxgrid").jqxGrid('updatebounddata', 'cells');
                  close_popup();
              }
              else if(res[0]=="Error"){
                  $('#notice').hide();
                  $('#notice-content').html('<div class="alert">'+res[1]+'</div>');
                  $('#notice').show();
              }
              else{
                  $('#popup_content').html(response);
              }
          }
        });

        return false;
    }    
  });
</script>