<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>admin_file/{action}/{id}" method="POST" name="frmFiles">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

        <!-- form start -->
          <div class="box-body">
			<?php 
			foreach($lang as $row){
				eval("\$filename= (isset(\$filename_".$row['kode'].") ? \$filename_".$row['kode']." : '');");
			?>
            <div class="form-group">
              <label for="exampleInputEmail1">Filename <?php echo $row['kode']?></label>
              <input type="text" class="form-control" name="filename_<?php echo $row['kode']?>" placeholder="{filename}" value="<?php echo $filename?>">
            </div>
			<?php } ?>
            <div class="form-group">
              <label for="exampleInputEmail1">Module</label>
              <input type="text" class="form-control" name="module" placeholder="module" value="{module}">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Theme</label>
              <?php echo form_dropdown('id_theme', $theme_option, $id_theme," class=form-control");?>
            </div>
          </div><!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-warning">Reset</button>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>

<script>
	$(function () {	
		$("#menu_admin_file").addClass("active");
		$("#menu_admin_panel").addClass("active");
	});
</script>
