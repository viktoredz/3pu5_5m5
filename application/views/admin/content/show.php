<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">&nbsp;</h3>
        </div><!-- /.box-header -->


		<div class="row">
		<?php
			foreach($query as $row):
				if($row->module=="news"){
					$icon = "fa fa-paste";
				}
				elseif($row->module=="event"){
					$icon = "fa fa-calendar";
				}
				elseif($row->module=="download"){
					$icon = "fa fa-arrow-circle-down";
				}
				elseif($row->module=="galleryevent"){
					$icon = "fa fa-picture-o";
				}
				elseif($row->module=="text"){
					$icon = "fa fa-building-o";
				}else{
					$icon = "fa fa-picture-o";
				}
		?>
		  <div class="col-md-3 col-sm-6 col-xs-12">
		    <div class="info-box">
		      <span class="info-box-icon bg-blue"><i class="<?php echo $icon?>"></i></span>
		      <div class="info-box-content">
		        <span class="info-box-text"><a href="<?php echo base_url()?>admin_content/edit<?php echo $row->module?>/file_id/<?php echo $row->id?>" ><?php echo $row->module?></a></span>
		        <span class="info-box-number"><a href="<?php echo base_url()?>admin_content/edit<?php echo $row->module?>/file_id/<?php echo $row->id?>" ><?php echo $row->filename?></a></span>
		      </div><!-- /.info-box-content -->
		    </div><!-- /.info-box -->
		  </div><!-- /.col -->
		<?php endforeach;?>                   
		</div><!-- /.row -->

        <div class="box-header">
          <h3 class="box-title">&nbsp;</h3>
        </div><!-- /.box-header -->

	  </div>
	</div>
  </div>
</section>

<script>
	$(function () {	
        $("#dataTable").dataTable();
		$("#menu_admin_content").addClass("active");
		$("#menu_admin").addClass("active");
	});
</script>
