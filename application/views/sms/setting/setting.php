
<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<form action="<?php echo base_url()?>sms/setting/doupdate" method="POST" name="frmUsers">
  <div class="row">
    <!-- left column -->
    <div class="col-md-4">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Modem {title_form}</h3>
        </div><!-- /.box-header -->

        <!-- form start -->
          <div class="box-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Device Port</label>
              <input type="text" class="form-control" name="com" placeholder="COM" value="{com}">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Card Number (+62)</label>
              <input type="text" class="form-control" name="cardnumber" placeholder="Number" value="{cardnumber}">
            </div>
          </div><!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary"><i class="icon fa fa-save"></i> Save</button>
            <button type="reset" class="btn btn-warning"><i class="icon fa fa-repeat"></i> Reset</button>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->
    <div class="col-md-8">
      <!-- general form elements -->
      <div class="box box-warning">
        <div class="box-header">
          <h3 class="box-title">Modem Testing</h3>
        </div><!-- /.box-header -->

        <!-- form start -->
          <div class="box-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Request USSD</label>
              <input type="text" class="form-control" name="ussd" placeholder="*123#" value="{ussd}">
            </div>
          <div class="box-footer">
            <button type="button" id="ussd" class="btn btn-primary"><i class="icon fa fa-share-square"></i> Request</button>
            <button type="button" id="identify" class="btn btn-success"><i class="icon fa fa-credit-card"></i> Identify</button>
          </div>
          <div class="box-footer">
            <button type="button" id="clean-inbox" class="btn btn-danger"><i class="icon fa fa-trash"></i> Clean Inbox</button>
            <button type="button" id="clean-sent" class="btn btn-danger"><i class="icon fa fa-trash"></i> Clean Sent Items</button>
            <button type="button" id="clean-outbox" class="btn btn-danger"><i class="icon fa fa-trash"></i> Clean Outbox</button>
            <button type="button" id="restart-services" class="btn btn-warning"><i class="icon fa fa-caret-square-o-right"></i> Restart Service</button>
          </div>
          <div class="box-footer">
              <b>Informasi :</b>
              <ul>
                <li>Contoh request USSD adalah *123#.</li>
                <li>Jika ada pesan "Error opening device, it is already opened by other application." pada saat request USSD artinya sedang ada proses background dan silahkan tunggu beberapa saat dan coba lagi.</li>
                <li>Jika request USSD gagal, silahkan tunggu beberapa saat dan coba lagi.</li>
                <li>Clean Inbox / Sent Items / Outbox digunakan untuk membersihkan SMS di database aplikasi.</li>
                <li>Restart Service digunakan untuk mengulang service jika terjadi masalah pada service.</li>
              </ul>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->  
  </div><!-- /.box -->
</form>
<div id="popup" style="display:none">
  <div id="popup_title">SMS Gateway Result</div>
  <div id="popup_content" style="float:center;text-align:center;padding:20px;">&nbsp;</div>
</div>
<script>
	$(function () {	
		$("#menu_admin_panel").addClass("active");
		$("#menu_sms_setting").addClass("active");

    $("#clean-inbox").click(function(){
      if(confirm("Bersihkan SMS Inbox?")){
        box_result();

        $.get("<?php echo base_url().'sms/setting/cleaninbox'; ?>" , function(data) {
          $("#popup_content").html(data);
        });
      }
    });

    $("#clean-sent").click(function(){
      if(confirm("Bersihkan SMS Terkirim?")){
        box_result();

        $.get("<?php echo base_url().'sms/setting/cleansent'; ?>" , function(data) {
          $("#popup_content").html(data);
        });
      }
    });

    $("#clean-outbox").click(function(){
      if(confirm("Bersihkan SMS Belum Terkirim?")){
        box_result();

        $.get("<?php echo base_url().'sms/setting/cleanoutbox'; ?>" , function(data) {
          $("#popup_content").html(data);
        });
      }
   });

    $("#restart-services").click(function(){
      if(confirm("Restart SMS Service?")){
        box_result();

        $.get("<?php echo base_url().'sms/setting/restart'; ?>" , function(data) {
          $("#popup_content").html(data);
        });
      }
    });

    $("#ussd").click(function(){
      box_result();

      $.post("<?php echo base_url().'sms/setting/ussd/'; ?>" , 'ussd='+$("[name='ussd']").val() , function(data) {
        $("#popup_content").html(data);
      });
    });

    $("#identify").click(function(){
      box_result();

        $.get("<?php echo base_url().'sms/setting/identify'; ?>" , function(data) {
          $("#popup_content").html(data);
        });
    });

	});

  function box_result(){
    $("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
    $("#popup").jqxWindow({
      theme: theme, resizable: false,
      width: 420,
      height: 200,
      isModal: true, autoOpen: false, modalOpacity: 0.2
    });
    $("#popup").jqxWindow('open');
  }
</script>