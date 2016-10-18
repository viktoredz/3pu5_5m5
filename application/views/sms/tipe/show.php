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
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
	    </div>

	      <div class="box-footer">
    		<div class="col-md-12">
			 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>sms/tipe/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
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

<script type="text/javascript">
	$(function () {
		$("#menu_master_data").addClass("active");
		$("#menu_sms_tipe").addClass("active");
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 250,
			height: 200,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
	});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'no', type: 'number'},
			{ name: 'id_tipe', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'jenis', type: 'string'},
			{ name: 'created_on', type: 'date'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('sms/tipe/json'); ?>",
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
				{ text: 'No', align: 'center', cellsalign: 'center', datafield: 'no', columntype: 'textbox', sortable: false, filtertype: 'none', width: '5%' },
				{ text: 'Nama', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '60%' },
				{ text: 'Jenis', align: 'center', cellsalign: 'center', datafield: 'jenis', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Tanggal Dibuat', align: 'center', cellsalign: 'center', datafield: 'created_on', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy HH:mm:ss', width: '20%' },
            ]
		});

	$("#jqxgrid").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;
			console.log('click');

			$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+
			"</br><br><div style='text-align:center'><input class='btn btn-success' style='width:100px' type='button' value='Edit' onClick='edit(\""+
			rowData.no+"\")'>&nbsp;&nbsp;<input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='del(\""+
			rowData.no+"\")'><br><br><input class='btn btn-warning' style='width:205px' type='button' value='Close' onClick='close_popup();'></div></div>");

			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
	});

	function edit(id){
		document.location.href="<?php echo base_url().'sms/tipe/edit';?>/" + id;
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'sms/tipe/dodel' ?>/" + id,  function(){
				alert('Tipe berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

</script>
