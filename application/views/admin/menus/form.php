<div class="title">{title_form}</div>
<div class="clear">&nbsp;</div>
<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert" id="alert">
<div align=right onClick="$('#alert').hide('fold',1000);" style="color:red;font-weight:bold">X</div>
<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>
<div class="clear">&nbsp;</div>
<?php
$data = $this->uri->ruri_to_assoc();
$x = "";
if(!empty($data['parent_id'])){
	$x="/parent_id/".$data['parent_id'];	
}
?>
<form action="<?php echo base_url()?>index.php/admin_menu/doadd/id_theme/{id_theme}/position/{position}/sub_id/{sub_id}<?=$x?> " method="POST" name="frmFiles">

	<div class="col-md-12">
		
		<div class="box box-primary">
		<div class="box-header">
		  <h3 class="box-title">Select Filename</h3>
		</div><!-- /.box-header -->		
		
		  <div class="box-body">
			<div class="form-group">
				<button type="submit" class="btn btn-success btn-sm">Simpan</button>
				<button type="reset" class="btn btn-warning btn-sm">Ulang</button>
				<button type="button" class="btn btn-primary btn-sm" onclick="document.location.href='<?php echo base_url()?>index.php/admin_menu/index/id_theme/{id_theme}/position/{position}';">Kembali</button>
			</div> 
			<div class="form-group">
			  <label for="exampleInputEmail1">Filename</label>
			  
			  <?php echo form_dropdown('file_id', $file_option, $file_id," class=form-control id=exampleInputEmail1");?>
			</div>   
			<div class="form-group">
				<button type="submit" class="btn btn-success btn-sm">Simpan</button>
				<button type="reset" class="btn btn-warning btn-sm">Ulang</button>
				<button type="button" class="btn btn-primary btn-sm" onclick="document.location.href='<?php echo base_url()?>index.php/admin_menu/index/id_theme/{id_theme}/position/{position}';">Kembali</button>
			</div>  					
		
		</div><!-- /.box -->                                
		</div><!-- /.box-body -->
	</div><!-- /.box -->
</form>
<script>
	$(function () {	
		$("#menu_admin_menu").addClass("active");
		$("#menu_admin_panel").addClass("active");
	});
</script>
