<section class="content">
<div id="notice" class="alert alert-warning alert-dismissable" style="display:none">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <div id="notice-content"></div>
</div>
<form method="POST" id="form-move">
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
              <label>File</label>
              <input type="file" name="filename" id="filename_import"/>
            </div>
          </div>
          <div class="box-footer">
            <button type="button" id="btn_doimport" class="btn btn-danger"><i class='fa fa-download'></i> &nbsp; Import</button>
            <button type="button" id="btn_download" class="btn btn-success"><i class='fa fa-file-excel-o'></i> &nbsp; Download Format</button>
            <button type="reset" id="btn-close" class="btn btn-warning"><i class='fa fa-times-circle-o'></i> &nbsp; Tutup</button>
          </div>
      </div><!-- /.box -->
    </div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>
<script type="text/javascript">
  $(function () { 
    $("#btn-close").click(function(){
      close_popup();
    });

    $("#btn_download").click(function(){
        window.location.href="<?php echo base_url()."sms/pbk/download" ?>";
    });

    $("#btn_doimport").click(function(){
        var data = new FormData();
        $('#notice-content').html('<div class="alert">Mohon tunggu....</div>');
        $('#notice').show();

        jQuery.each($('#filename_import')[0].files, function(i, file) {
            data.append('filename', file);
        }); 
        
        $.ajax({
            cache : false,
            contentType : false,
            processData : false,
            type : 'POST',
            url : '<?php echo base_url()."sms/pbk/doimport/"?>',
            data : data,
            success : function(response){
              var res  = response.split("|");
              if(res[0]=="OK"){
                  $('#notice').hide();
                  $('#notice-content').html(response);
                  $('#notice').show();

                  close_popup();
              }
              else{
                  $('#notice').hide();
                  $('#notice-content').html(response);
                  $('#notice').show();
              }
          }
        });

        return false;
    });    
  });
</script>
