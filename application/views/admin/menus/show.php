<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jquery-ui.min.js"></script>


<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>




<section class="content">
<form action="<?php echo base_url()?>admin_menu/doorder/id_theme/{id_theme}" method="POST" name="frmFiles">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->

        <div class="box-body col-md-6">
          <div class="box-footer">
            <!--<button type="submit" class="btn btn-primary" >Simpan Urutan</button>-->
			<button type="button" class="btn btn-warning" onclick="document.location.href='<?php echo base_url()?>admin_menu/add/id_theme/{id_theme}/position/{position}'">Tambah Menu Utama</button>
	   	 </div>
	    </div>
		
        <div class="box-body col-md-3 pull-right" style="text-align:right">
            <div class="form-group">
              <label for="exampleInputEmail1">Tentukan Posisi</label>
              <?php echo form_dropdown('position', $position_option, $position," class=form-control onchange=doPosition() id=IdPosition");?>
            </div>
	    </div>
        <div class="box-body col-md-3 pull-right" style="text-align:right">

            <div class="form-group">
              <label  for="exampleInputEmail1">Themes</label>
              <?php echo form_dropdown('id_theme', $theme_option, $id_theme," class=form-control onchange=doTheme() id=IdTheme");?>
            </div>
	    </div>
		<div class="box-body col-md-12 " id="drag_menu">
		
                  <ul class="todo-list">
					<?php
						
						foreach($menu_data as $m){
					?>
                    <li id="item-<?=$m['id']?>#<?=$m['position']?>">
						<!-- drag handle -->
						<span class="handle">
							<i class="fa fa-ellipsis-v"></i>
							<i class="fa fa-ellipsis-v"></i>
						</span>                      
						<!-- menu text -->
						<span class="text"><?=$m['filename']?></span>
						<div class="pull-right">
							<a href="<?php echo base_url()?>admin_menu/add/id_theme/{id_theme}/position/{position}/sub_id/<?=$m['id']?>" class="glyphicon glyphicon-plus"></a>
							<a href="<?php echo base_url()?>admin_menu/dodelete/id_theme/{id_theme}/position/{position}/sub_id/{sub_id}/delete_id/<?=$m['id']?>" onclick="return confirm('Anda yakin ingin menghapus menu ini ?')" class="glyphicon glyphicon-trash"></a>
						</div>
							
						<ul class="todo-list" style="margin-left:25px">
						<?php
							
							foreach($submenu_data as $s){
								if($s['sub_id']==$m['id']){									
									?>
									<li id="item-<?=$s['id']?>#<?=$s['position']?>" >
										<span class="handle">
											<i class="fa fa-ellipsis-v"></i>
											<i class="fa fa-ellipsis-v"></i>
										</span>                      
										  <!-- submenu text -->
										<span class="text"><?=$s['filename']?></span>
										
										<div class="pull-right">
											
											<a href="<?php echo base_url()?>admin_menu/dodelete/id_theme/{id_theme}/position/{position}/sub_id/{sub_id}/delete_id/<?=$s['id']?>" onclick="return confirm('Anda yakin ingin menghapus menu ini ?')" class="glyphicon glyphicon-trash"></a>
										</div>
										
									</li>
									<?php
								}						
							}
						?>
						</ul>
                    </li>
                    <?php
						}
					?>
                  </ul>
                </div><!-- /.box-body -->

      	<div class="box-body">
			
		</div><!-- /.box -->

  	</div><!-- /.box -->
  </div><!-- /.box -->
</div><!-- /.box -->
</form>
</section>

<script>
	$(function () {	
		$("#menu_admin_menu").addClass("active");
		$("#menu_admin_panel").addClass("active");		
		$('#drag_menu ul').sortable({
			axis: 'y',
			update: function (event, ui) {
				var data = $(this).sortable('serialize');
				
				// POST to server using $.post or $.ajax
				$.ajax({
					data: data,
					type: 'POST',
					url: '<?php echo base_url()?>admin_menu/dosort'
				});
				
			}
		});
	});
	
	function doTheme(){
		var e = document.getElementById("IdTheme");
		var dataPosition = e.options[e.selectedIndex].value;
		location.href="<?php echo base_url(); ?>admin_menu/index/id_theme/"+dataPosition;
	}
	
	function doPosition(){
		var e = document.getElementById("IdTheme");
		var dataTheme = e.options[e.selectedIndex].value;
		
		var e = document.getElementById("IdPosition");
		var dataPosition = e.options[e.selectedIndex].value;
		
		location.href="<?php echo base_url(); ?>admin_menu/index/id_theme/"+dataTheme+"/position/"+dataPosition;
	}
	
	
	
</script>

