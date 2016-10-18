<script type="text/javascript">
	$(function() {

		$("input[name^='all__']").click(function() {
			tmp_id = $(this).prop("name").split("__");
			if(this.checked == true){
				$("input[name='show__"+ tmp_id[1] +"']").prop("checked",true);
				$("input[name='add__"+ tmp_id[1] +"']").prop("checked",true);
				$("input[name='edit__"+ tmp_id[1] +"']").prop("checked",true);
				$("input[name='del__"+ tmp_id[1] +"']").prop("checked",true);
			}else{
				$("input[name='all__all']").prop("checked",false);
				$("input[name='show__"+ tmp_id[1] +"']").prop("checked",false);
				$("input[name='add__"+ tmp_id[1] +"']").prop("checked",false);
				$("input[name='edit__"+ tmp_id[1] +"']").prop("checked",false);
				$("input[name='del__"+ tmp_id[1] +"']").prop("checked",false);
			}
		});

		$("input[name$='__all']").click(function() {
			tmp_id = $(this).prop("name").split("__");
			if(tmp_id[0] == "all"){
				if(this.checked  == true){
					$(":checkbox").prop("checked",true);
				}else{
					$(":checkbox").prop("checked",false);
				}
			}
			else if(this.checked  == true){
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",true);
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",true);
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",true);
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",true);
			}else{
				$("input[name^='all__']").prop("checked",false);
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",false);
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",false);
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",false);
				$("input[name^='"+ tmp_id[0] +"']").prop("checked",false);
			}
		});
	});
</script>
<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>admin_role/{action}/{level}" method="POST" name="frmGroup_roles">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form} - <?php echo ucfirst($level) ?></h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div class="box-footer">
            <button type="submit" class="btn btn-primary" >Simpan</button>
            <button type="reset" class="btn btn-warning" >Ulang</button>
            <button type="button" class="btn btn-danger"  onclick="document.location.href='<?php echo base_url()?>index.php/admin_role/';">Kembali</button>
         </div>
	    </div>
        <div class="box-body">
              <table id="dataTable" class="table table-bordered table-hover">
                <thead>
					<tr>
						<th rowspan=2 align=center>No</th>
						<th rowspan=2 colspan=3>Modules / Controllers / Theme</th>
						<th colspan=10 align=center>Privileges</th>
					</tr>
					<tr>
						<th>All<br><input type="checkbox" name="all__all"></th>
						<th>Show<br><input type="checkbox" name="show__all"></th>
						<th>Add<br><input type="checkbox" name="add__all"></th>
						<th>Edit<br><input type="checkbox" name="edit__all"></th>
						<th>Delete<br><input type="checkbox" name="del__all"></th>
					</tr>
                </thead>
                <tbody>
				<?php 
				$i=1;
				foreach($query as $row):?>
					<tr>
						<td align=center><?php echo $i++?>.</td>
						<td><?php echo ucwords($row->filename) ?>&nbsp;</td>
						<td><?php echo ucwords($row->module) ?>&nbsp;</td>
						<td><?php echo ucwords($row->name) ?>&nbsp;</td>
						<td align=center><input type="checkbox" name="all__<?php echo $row->id ?>" <?php if($row->doshow && $row->doadd && $row->doedit && $row->dodel) echo "checked" ?>></td>
						<td align=center><input type="checkbox" value=1 name="show__<?php echo $row->id ?>" <?php if($row->doshow) echo "checked" ?>></td>
						<td align=center><input type="checkbox" value=1 name="add__<?php echo $row->id ?>" <?php if($row->doadd) echo "checked" ?>></td>
						<td align=center><input type="checkbox" value=1 name="edit__<?php echo $row->id ?>" <?php if($row->doedit) echo "checked" ?>></td>
						<td align=center><input type="checkbox" value=1 name="del__<?php echo $row->id ?>" <?php if($row->dodel) echo "checked" ?>></td>
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
        $("#dataTable").dataTable({
          "bPaginate": false,
          "bLengthChange": false,
          "bFilter": true,
          "bSort": false,
          "bInfo": false,
          "bAutoWidth": false
        });
		$("#menu_admin_role").addClass("active");
		$("#menu_admin_panel").addClass("active");
	});
</script>

