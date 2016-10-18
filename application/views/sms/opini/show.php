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
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
    		<div class="col-md-6">
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			 </div>
    		<div class="col-md-3">
	     		<select id="tipe" class="form-control">
	     			<option value="">-- Pilih Kategori --</option>
					<?php foreach ($tipeoption as $row ) { ;?>
						<option value="<?php echo $row->id_tipe; ?>" ><?php echo $row->nama; ?></option>
					<?php }?>
	     		</select>
			 </div>
    		<div class="col-md-3">
	     		<select id="status" class="form-control">
					<?php foreach ($statusoption as $row=>$val ) { ;?>
						<option value="<?php echo $row; ?>" <?php if($statusoption_active==$row) echo "selected" ?>><?php echo $val; ?></option>
					<?php }?>
	     		</select>
			</div>
	     </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid"></div>
			</div>
	    </div>
	  </div>
	</div>
  </div>
</section>
<div id="popup" style="display:none">
	<div id="popup_title">SMS</div>
	<div id="popup_content">&nbsp;</div>
</div>

<script type="text/javascript">
	$(function () {	
		$("#menu_esms").addClass("active");
		$("#menu_sms_opini").addClass("active");

		$("#tipe").change(function(){
			$.post("<?php echo base_url().'sms/opini/filter' ?>", 'tipe='+$(this).val(),  function(){
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		});

		$("#status").change(function(){
			$.get("<?php echo base_url().'sms/opini/get_tipe' ?>/" + $(this).val(),  function(response){
		    	var data = eval(response);
		      	$("#tipe").html(data.tipe);
		    }, "json");

			$.post("<?php echo base_url().'sms/opini/filter' ?>", 'status='+$(this).val(),  function(){
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		});
	});

	function close_popup(){
		$("#popup").jqxWindow('close');
	}

	function move(id){
		$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'sms/opini/move/'; ?>" + id , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 420,
			height: 450,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup").jqxWindow('open');
	}

	function reply(id){
		$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'sms/opini/reply/'; ?>" + id , function(data) {
			$("#popup_content").html(data);
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		});
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 420,
			height: 480,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup").jqxWindow('open');
	}

	function detail(id){
		$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'sms/opini/detail/'; ?>" + id , function(data) {
			$("#popup_content").html(data);
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		});
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 420,
			height: 440,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup").jqxWindow('open');
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'sms/opini/dodel' ?>/" + id,  function(){
				alert('SMS berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_opini', type: 'number'},
			{ name: 'nomor', type: 'string'},
			{ name: 'pesan', type: 'string'},
			{ name: 'status', type: 'string'},
			{ name: 'created_on', type: 'date'},
			{ name: 'reply', type: 'number'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('sms/opini/json'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		},
		sort: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'sort');
		},
		root: 'Rows',
        pagesize: 10,
        beforeprocessing: function(data){		
			if (data != null){
				source.totalrecords = data[0].TotalRows;					
			}
		}
		};		
		var dataadapter = new $.jqx.dataAdapter(source, {
			loadError: function(xhr, status, error){
				alert(error);
			}
		});
     
		$('#btn-refresh').click(function () {
			$("#jqxgrid").jqxGrid('clearfilters');
		});

		$("#jqxgrid").jqxGrid(
		{		
			width: '100%',
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;    
			},
			columns: [
				{ text: 'Reply', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.reply==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_add.gif' onclick='reply(\""+dataRecord.id_opini+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Detail', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.edit==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_view.gif' onclick='detail(\""+dataRecord.id_opini+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Del', align: 'center', filtertype: 'none', sortable: false, width: '5%', cellsrenderer: function (row) {
				    var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
				    if(dataRecord.delete==1){
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_del.gif' onclick='del(\""+dataRecord.id_opini+"\");'></a></div>";
					}else{
						return "<div style='width:100%;padding-top:2px;text-align:center'><a href='javascript:void(0);'><a href='javascript:void(0);'><img border=0 src='<?php echo base_url(); ?>media/images/16_lock.gif'></a></div>";
					}
                 }
                },
				{ text: 'Nomor Pengirim', align: 'center', cellsalign: 'center', datafield: 'nomor', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Isi Pesan', datafield: 'pesan', columntype: 'textbox', filtertype: 'textbox', width: '45%' },
				{ text: 'Status', datafield: 'status', align: 'center', cellsalign: 'center', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Waktu Diterima', align: 'center', cellsalign: 'center', datafield: 'created_on', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy HH:mm:ss', width: '15%' }
            ]
		});


</script>