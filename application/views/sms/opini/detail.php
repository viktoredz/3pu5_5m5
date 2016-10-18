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
              <label>Tanggal</label>
              <input readonly type="text" class="form-control" name="created_on" placeholder="created_on" value="<?php echo $created_on;?>">
            </div>
            <div class="form-group">
              <label>Pengirim</label>
              <input readonly type="text" class="form-control" name="nomor" placeholder="nomor" value="<?php echo $nomor;?>">
            </div>
            <div class="form-group">
              <label>Pesan</label>
              <textarea readonly class="form-control" placeholder="pesan"><?php echo ($pesan);?></textarea>
            </div>
          </div>
          <div class="box-footer pull-right">
            <button type="button" id="btn-reply" class="btn btn-primary">Reply</button>
            <button type="button" id="btn-move" class="btn btn-success">Pindah</button>
            <button type="button" id="btn-close" class="btn btn-warning">Tutup</button>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</section>
<script type="text/javascript">
  $(function () { 
    $("#btn-move").click(function(){
      move({id});
    });
    $("#btn-reply").click(function(){
      reply({id});
    });

    $("#btn-close").click(function(){
      close_popup();
    });
  });
</script>