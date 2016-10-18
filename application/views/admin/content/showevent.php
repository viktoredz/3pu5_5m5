
<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>admin_file/dodel_multi" method="POST" name="frmUsers">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
          <div class="box-footer">
		 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>admin_content/editevent_form/file_id/{file_id}'">Tambah</button>
		 	<button type="button" class="btn btn-success" onclick="document.location.href='<?php echo base_url()?>admin_content'">Kembali</button>
         </div>
	    </div>

        <div class="box-body">
                  <table id="dataTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
						<th>&nbsp;</th>
						<th>NO</font></th>
						<th>ID</font></th>
						<th>Title</th>
						<th>Published</th>
						<th>Author <br> Bidang</th>
						<th>Time</th>
						<th>Views</th>
						<th>Edit</th>
						<th>Hapus</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php 
					$start=1;
					foreach($query as $row):?>
						<tr>
							<td><input type="checkbox" name="id[]" value="<?php echo $row->id?>" /></td>
							<td><?php echo $start++?>&nbsp;</td>
							<td><?php echo $row->id?>&nbsp;</td>
							<td><?php echo $row->title_content?>&nbsp;</td>
							<td><?php echo ($row->published==1 ? "yes" : "no" )?>&nbsp;</td>
							<td><?php echo $row->author?><br><?php echo $row->bidang?></td>
							<td><?php echo date("d M Y",$row->dtime)?> - <?php echo date("d M Y",$row->dtime_end)?></td>
							<td><?php echo $row->hits?>&nbsp;</td>
							<td align=center><a href="<?php echo base_url()?>admin_content/editevent_form/file_id/{file_id}/id/<?php echo $row->id?>" title="Ubah"><img src="<?php echo base_url()?>media/images/16_edit.gif" /></a></td>
							<td align="center"><a href="#" onclick="if(confirm('Hapus data ini?'))document.location.href='<?php echo base_url()?>admin_content/dodelevent/{file_id}/<?php echo $row->id?>'" title="Hapus"><img src="<?php echo base_url()?>media/images/16_del.gif" /></a></td>
						</tr>
					<?php endforeach;?>                   
				</tbody>
              </table>
	    </div>

	  </div>
	</div>
  </div>
</form>
</section>

<script>
	$(function () {	
        $("#dataTable").dataTable();
		$("#menu_admin_content").addClass("active");
		$("#menu_admin").addClass("active");
	});
</script>