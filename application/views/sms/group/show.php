<div id="popup" style="display:none;">
  <div id="popup_title">eSMS Gateway</div><div id="popup_content">{popup}</div>
</div>
<div id="popup1" style="display:none;">
  <div id="popup_title1">eSMS Gateway</div><div id="popup_content1">{popup}</div>
</div>
<div id="popup_del" style="display:none;">
  <div id="popup_title_del">eSMS Gateway</div><div id="popup_content_del">{popup}</div>
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
				 		<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>sms/group/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah</button>
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
		$("#menu_sms_group").addClass("active");
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
			{ name: 'anggota', type: 'number'},
			{ name: 'id', type: 'string'},
			{ name: 'nomor', type: 'string'},
			{ name: 'nama', type: 'string'},
			{ name: 'nama_grup', type: 'string'},
			{ name: 'created_on', type: 'date'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('sms/group/json'); ?>",
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
				{ text: 'Nama Grup', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '60%' },
				{ text: 'Anggota', align: 'center', cellsalign: 'center', datafield: 'anggota', columntype: 'textbox', filtertype: 'textbox', width: '15%' },
				{ text: 'Tanggal Dibuat', align: 'center', cellsalign: 'center', datafield: 'created_on', columntype: 'date', filtertype: 'date', cellsformat: 'dd-MM-yyyy HH:mm:ss', width: '20%' },
            ]
		});

		$("#jqxgrid").on('rowselect', function (event) {
				var args = event.args;
				var rowData = args.row;
				console.log('click');

				$("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+
				"</br><br><div style='text-align:center'><input class='btn btn-success' style='width:100px' type='button' value='Edit' onClick='edit(\""+
				rowData.no+"\")'>&nbsp;&nbsp;<input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='btn_del(\""+
				rowData.no+"\")'><br><br><input class='btn btn-warning' style='width:205px' type='button' value='Close' onClick='close_popup();'></div></div>");

				$("html, body").animate({ scrollTop: 0 }, "slow");
				$("#popup").jqxWindow('open');
		});

	function edit(id){
		document.location.href="<?php echo base_url().'sms/group/edit';?>/" + id;
	}

	function del(id){
		var confirms = confirm("Hapus Data ?");
		if(confirms == true){
			$.post("<?php echo base_url().'sms/group/dodel' ?>/" + id,  function(){
				alert('Nomor berhasil dihapus');

				$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
			});
		}
	}

  function close_popup(){
		$("#jqxgrid").jqxGrid('clearselection');
		$("#popup").jqxWindow('close');
		$("#popup1").jqxWindow('close');
    $("#popup_del").jqxWindow('close');
	}

  function btn_del(id){
		$("#popup").hide();
		$("#popup_content_del").html("<div style='padding:5px'><br><div style='text-align:center'>Hapus Data?<br><br><input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='del_senditm("+id+")'>&nbsp;&nbsp;<input class='btn btn-success' style='width:100px' type='button' value='Batal' onClick='close_popup()'></div></div>");
    $("#popup_del").jqxWindow({
      theme: theme, resizable: false,
      width: 250,
      height: 150,
      isModal: true, autoOpen: false, modalOpacity: 0.2
    });
    $("#popup_del").jqxWindow('open');
	}

  function del_senditm(id){
		$.post("<?php echo base_url().'sms/group/dodel' ?>/" +id,  function(){
		  $("#popup_content_del").html("<div style='padding:5px'><br><div style='text-align:center'>Data berhasil dihapus<br><br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'></div></div>");
          $("#popup_del").jqxWindow({
            theme: theme, resizable: false,
            width: 250,
            height: 150,
            isModal: true, autoOpen: false, modalOpacity: 0.2
          });

			$("#popup_del").jqxWindow('open');
			$("#popup").jqxWindow('close');
			$("#popup1").jqxWindow('close');
			$("#jqxgrid").jqxGrid('updatebounddata', 'cells');
		});
	}

</script>
