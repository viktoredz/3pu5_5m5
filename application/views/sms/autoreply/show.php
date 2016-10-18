<div id="popup" style="display:none;">
  <div id="popup_title">eSMS Gateway</div><div id="popup_content">{popup}</div>
</div>
<div id="popup1" style="display:none;">
  <div id="popup_title1">eSMS Gateway</div><div id="popup_content1">{popup}</div>
</div>

<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<section class="content">
<form action="<?php echo base_url()?>sms/autoreply/dodel_multi" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
    		<div class="col-md-7">
			 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>sms/autoreply/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah Info Baru</button>
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			 </div>
    		<div class="col-md-2">
	     		<select id="menu" class="form-control">
	     			<option value="">-- Pilih Menu --</option>
					<?php foreach ($menuoption as $row ) { ;?>
						<option value="<?php echo $row->code; ?>" ><?php echo strtoupper($row->code); ?></option>
					<?php }?>
	     		</select>
			</div>
    		<div class="col-md-3">
	     		<select id="tipe" class="form-control">
	     			<option value="">-- Pilih Kategori --</option>
					<?php foreach ($tipeoption as $row ) { ;?>
						<option value="<?php echo $row->id_tipe; ?>" ><?php echo $row->nama; ?></option>
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
</form>
</section>

<script type="text/javascript">
	$(function () {
		$("#menu_esms").addClass("active");
		$("#menu_sms_autoreply").addClass("active");
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 250,
			height: 200,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});

		$("#tipe").change(function(){
			$.post("<?php echo base_url().'sms/autoreply/filter' ?>", 'tipe='+$(this).val(),  function(){
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		});

		$("#menu").change(function(){
			$.post("<?php echo base_url().'sms/autoreply/filter' ?>", 'menu='+$(this).val(),  function(){
				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		});
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'id_info', type: 'number'},
			{ name: 'katakunci', type: 'string'},
			{ name: 'tgl_mulai', type: 'date'},
			{ name: 'tgl_akhir', type: 'date'},
			{ name: 'pesan', type: 'string'},
			{ name: 'code_sms_menu', type: 'string'},
			{ name: 'id_sms_tipe', type: 'number'},
			{ name: 'tipe', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('sms/autoreply/json'); ?>",
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
				{ text: 'Menu', align: 'center', cellsalign: 'center', datafield:'code_sms_menu', columntype: 'textbox', filtertype: 'textbox', width: '10%' },
				{ text: 'Kata Kunci', align: 'center', cellsalign: 'center', datafield:'katakunci', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Informasi', datafield:'pesan', columntype: 'textbox', filtertype: 'textbox', width: '40%' },
				{ text: 'Kategori', datafield:'tipe', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Aktif', align: 'center', cellsalign: 'center', datafield:'tgl_mulai', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '10%' },
				{ text: 'Non Aktif', align: 'center', cellsalign: 'center', datafield:'tgl_akhir', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy', width: '10%' }
            ]
		});

	$("#jqxgrid").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;
			console.log('click');

			$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.pesan+
			"</br><br><div style='text-align:center'><input class='btn btn-success' style='width:100px' type='button' value='Edit' onClick='edit(\""+
			rowData.id_info+"\")'>&nbsp;&nbsp;<input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='del(\""+
			rowData.id_info+"\")'><br><br><input class='btn btn-warning' style='width:205px' type='button' value='Close' onClick='close_popup();'></div></div>");

			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
	});

	function edit(id){
		document.location.href="<?php echo base_url().'sms/autoreply/edit';?>/" + id;
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'sms/autoreply/dodel' ?>/" + id,  function(){
				alert('data berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

	function close_popup(){
		$("#jqxgrid").jqxGrid('clearselection');
		$("#popup").jqxWindow('close');
		$("#popup1").jqxWindow('close');
	}

</script>
