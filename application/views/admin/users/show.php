<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>admin_user/dodel_multi" method="POST" name="frmUsers">
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
            <button type="button" class="btn btn-primary" onclick="document.location.href='<?php  echo base_url()?>index.php/admin_user/add'">Tambah</button>
            <button type="submit" class="btn btn-danger" onClick="if(!confirm('Hapus semua data yang dipilih?'))return false;">Hapus</button>
          </div>
	    </div>

        <div class="box-body">
              <table id="dataTable" class="table table-bordered table-hover">
                <thead>
                  <tr>
					<th>&nbsp;</th>
					<th>NO</font></th>
					<th>Email / Name</font></th>
					<th>Level</th>
					<th>Last Login / Las Activity</th>
					<th>Active</th>
					<th>Online</th>
	                <th>Ubah</th>
	                <th>Hapus</th>
                  </tr>
                </thead>
                <tbody>
				<?php 
				$start=1;
				foreach($query as $row):?>
					<tr>
						<?php 
		                	if(($row->username=="puskesmas")||($row->username=="sms")||($row->username=="inventory")||($row->username=="keuangan")||($row->username=="kepegawaian")){
		                ?>
		                <td align="center"></td>
		                
		                <?php
		                	}else{
		                ?>
		                <td><input type="checkbox" name="id[]" value="<?php  echo $row->username?>" /></td>
		                <?php } ?>
						<td><?php  echo $start++?>&nbsp;</td>
						<td><?php  echo $row->username?>&nbsp;</td>
						<td><?php  echo ucwords($row->level)?>&nbsp;</td>
						<td><?php  echo date("d-m-Y h:i:s",$row->last_login)?><br><?php  echo date("d-m-Y h:i:s",$row->last_active)?></td>
						<td align="center"><img src="<?php  echo base_url()?>media/images/status_<?php  echo intval($row->status_active)?>.gif"></td>
						<td align="center"><img src="<?php  echo base_url()?>media/images/status_<?php  echo intval($row->online)?>.gif"></td>
						<td align="center"><a href="<?php  echo base_url()?>index.php/admin_user/edit/<?php  echo $row->username?>/<?php  echo $row->code?>" title="Detail Account"><img src="<?php  echo base_url()?>media/images/16_edit.gif" /></a></td>
		                <?php 
		                	if(($row->username=="puskesmas")||($row->username=="sms")||($row->username=="inventory")||($row->username=="keuangan")||($row->username=="kepegawaian")){
		                ?>
		                <td align="center"><a href="#" title="Delete Account" ><img src="<?php  echo base_url()?>media/images/16_lock.gif"></a></td>
		                
		                <?php
		                	}else{
		                ?>
		                <td align="center"><a href="<?php  echo base_url()?>index.php/admin_user/dodel/<?php  echo $row->username?>/<?php  echo $row->code?>" title="Delete Account" onclick="return confirm_delete()" ><img src="<?php  echo base_url()?>media/images/16_del.gif"></a></td>
		                <?php } ?>
					</tr>
				<?php endforeach;?>                   
				</tbody>
                <tfoot>
                  <tr>
					<th>&nbsp;</th>
					<th>NO</font></th>
					<th>Email / Name</font></th>
					<th>Level</th>
					<th>Last Login / Las Activity</th>
					<th>Active</th>
					<th>Online</th>
	                <th>Ubah</th>
	                <th>Hapus</th>
                  </tr>
                </tfoot>
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
		$("#menu_admin_user").addClass("active");
		$("#menu_admin_panel").addClass("active");
	});
	function confirm_delete() {
	  return confirm('Apakah yakin data akan dihapus?');
	}
</script>
