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
              <input readonly type="text" class="form-control" name="SendingDateTime" placeholder="SendingDateTime" value="<?php echo $SendingDateTime;?>">
            </div>
            <div class="form-group">
              <label>Tujuan</label>
              <input readonly type="text" class="form-control" name="DestinationNumber" placeholder="DestinationNumber" value="<?php echo $DestinationNumber;?>">
            </div>
            <div class="form-group">
              <label>Pesan</label>
              <textarea readonly class="form-control" id="TextDecoded"><?php echo ($TextDecoded);?></textarea>
            </div>
          </div>
          <div class="box-footer pull-right">
            <button type="button" id="btn-resend" class="btn btn-primary">Re-Send</button>
            <button type="button" id="btn-close" class="btn btn-warning">Tutup</button>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</section>
<script type="text/javascript">
  $(function () { 
    $("#btn-resend").click(function(){
      if(confirm("Kirim ulang SMS ini?")){
          var data = new FormData();

          data.append('TextDecoded', $('#TextDecoded').val());
          $.ajax({
              cache : false,
              contentType : false,
              processData : false,
              type : 'POST',
              url : '<?php echo base_url()."sms/sentitems/resend/".$id?>',
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

    $("#btn-close").click(function(){
      close_popup();
    });
  });
</script>