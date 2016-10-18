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
                  <table id="dataTable" class="table table-bordered table-hover">
                    <thead>
						<tr>
							<th rowspan=2>No</font></th>
							<th rowspan=2>Level</th>
							<th colspan=5>Total Privilege</th>
							<th rowspan=2>Detail</th>
						</tr>
						<tr>
							<th>Modules</th>
							<th>Show</th>
							<th>Add</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
                    </thead>
                    <tbody>
					<?php 
					$start=1;
					foreach($query as $row):?>
						<tr>
							<td align=center><?php echo $start++?>&nbsp;</td>
							<td><?php echo ucfirst($row->level) ?>&nbsp;</td>
							<td><?php echo ucfirst($row->total) ?></td>
							<td><?php echo ucfirst($row->total_show) ?></td>
							<td><?php echo ucfirst($row->total_add) ?></td>
							<td><?php echo ucfirst($row->total_edit) ?></td>
							<td><?php echo ucfirst($row->total_del) ?></td>
							<td  align=center><a href="<?php echo base_url()?>index.php/admin_role/detail/<?php echo $row->level?>" title="Ubah"><img src="<?php echo base_url()?>media/images/16_edit.gif" /></a></td>
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
        $("#dataTable").dataTable({"bPaginate": false});
		$("#menu_admin_panel").addClass("active");
		$("#menu_admin_role").addClass("active");
	});
</script>
