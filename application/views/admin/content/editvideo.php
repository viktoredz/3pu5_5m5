<link href="<?php echo base_url()?>plugins/js/kartik/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url()?>plugins/js/kartik/js/fileinput.js" type="text/javascript"></script>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>admin_content/doeditvideo/file_id/{file_id}/id/{id}" method="POST" name="frmFiles">
  <div class="row">
    <!-- left column -->
    <div class="col-md-10">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
          <div class="pull-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>admin_content/editvideo/file_id/{file_id}'">Kembali</button>
          </div>
        </div><!-- /.box-header -->

      	<div class="box-body">
			<?php 
			foreach($lang as $row):
				eval("\$title_content= (isset(\$title_content_".$row['kode'].") ? \$title_content_".$row['kode']." : '');");
			?>
	            <div class="form-group">
	              <label for="exampleInputEmail1">Title <i><?php echo $row['lang']?></i></label>
	              <input type="text" class="form-control" name="title_content_<?php echo $row['kode']?>" value="<?php echo $title_content ?>">
	            </div>
			<?php endforeach;?>

            <div class="form-group">
              <label for="exampleInputEmail1">Author : </label>
              {author}
            <br>
              <label for="exampleInputEmail1">Time : </label>
              {dtime}
            <br>
              <label for="exampleInputEmail1">Views : </label>
              {hits}
            <br>
              <input class=input type="checkbox" name="published" value="1" <?php if($published) echo "checked"; ?>>
              <label for="exampleInputEmail1">Publish / Unpublish</label>
            </div>
          
			<?php 
			foreach($lang as $row):
				eval("\$headline= (isset(\$headline_".$row['kode'].") ? \$headline_".$row['kode']." : '');");
			?>
	            <div class="form-group">
	              <label for="exampleInputEmail1">Headline <i><?php echo $row['lang']?></i></label>
	              <textarea name="headline_<?php echo $row['kode']?>" class="form-control" rows=4 cols=80><?php echo $headline?></textarea>
	            </div>
			<?php endforeach;?>
            <div class="form-group">
              <label for="exampleInputEmail1">Embed Code</label>
              <textarea name="content" class="form-control" rows=4 cols=80><?php echo $content?></textarea>
              <br>
				<input type="button" name="preview" class=btn value="Preview" id="preview">
				<input type="button" name="hide" class=btn value="Hide" id="hide">
				<br><br>
				<div id="video_frame"></div>
            </div>
          <div class="pull-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>admin_content/editvideo/file_id/{file_id}'">Kembali</button>
          </div>
      </div><!-- /.box -->
  	</div><!-- /.box -->
</form>
<?php if($id>0){?>
<form enctype="multipart/form-data">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">Upload Thumbnail Video</h3>
          <div class="pull-right">
      	</div>
        <div class="box-body">
          <input id="uploadfile" name="uploadfile" type="file" accept="image/*" class="file-loading">
      	</div><!-- /.box -->
      </div><!-- /.box -->
    </div><!-- /.box -->
  </div><!-- /.box -->
</form>
<script type="text/javascript">
{filelist_kartik}
</script>
</div><!-- /.box -->
<?php }?>
</section>

<script type="text/javascript">
$(function () {	
	$("#menu_admin_content").addClass("active");
	$("#menu_admin").addClass("active");
});
</script>
<script type="text/javascript">
	$(function() {

		$("#preview").click(function(){
			var x = $("textarea[name='content']").val();
			$("#video_frame").html(x);
		});
		$("#hide").click(function(){
			$("#video_frame").html("");
		});


	});
</script>
