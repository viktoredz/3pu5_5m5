<?php if($this->session->flashdata('alert')!=""){ ?>
<div class="alert alert-success alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
	<?php echo $this->session->flashdata('alert')?>
</div>
<?php } ?>

<div id="popup" style="display:none;">
  <div id="popup_title">eSMS Gateway</div><div id="popup_content">{popup}</div>
</div>

<div id="popup1" style="display:none;">
  <div id="popup_title1">eSMS Gateway</div><div id="popup_content1">{popup}</div>
</div>

<div id="popup_del" style="display:none;">
  <div id="popup_title_del">eSMS Gateway</div><div id="popup_content_del">{popup}</div>
</div>

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
    		<div class="col-md-9">
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
		$("#menu_esms").addClass("active");
		$("#menu_sms_sentitems").addClass("active");
	});

	function close_popup(){
        $("#jqxgrid").jqxGrid('clearselection');
		$("#popup").jqxWindow('close');
		$("#popup1").jqxWindow('close');
	}

	function close_popup1(){
        $("#jqxgrid").jqxGrid('clearselection');
		$("#popup1").jqxWindow('close');
	}

	function close_popup_del(){
        $("#jqxgrid").jqxGrid('clearselection');
        $("#popup").jqxWindow('close');
				$("#popup1").jqxWindow('close');
        $("#popup_del").jqxWindow('close');
    }

	function btn_del(id){
		$("#popup1").hide();
		$("#popup_content_del").html("<div style='padding:5px'><br><div style='text-align:center'>Hapus Data?<br><br><input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='del_senditm("+id+")'>&nbsp;&nbsp;<input class='btn btn-success' style='width:100px' type='button' value='Batal' onClick='close_popup_del()'></div></div>");
          $("#popup_del").jqxWindow({
            theme: theme, resizable: false,
            width: 250,
            height: 150,
            isModal: true, autoOpen: false, modalOpacity: 0.2
          });
        $("#popup_del").jqxWindow('open');
	}

	function del_senditm(id){
		$.post("<?php echo base_url().'sms/sentitems/dodel' ?>/" +id,  function(){
		  $("#popup_content_del").html("<div style='padding:5px'><br><div style='text-align:center'>Data berhasil dihapus<br><br><input class='btn btn-danger' style='width:100px' type='button' value='OK' onClick='close_popup_del()'></div></div>");
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

	function detail(id){
		$("#popup_content").html("<div style='text-align:center'><br><br><br><br><img src='<?php echo base_url();?>media/images/indicator.gif' alt='loading content.. '><br>loading</div>");
		$.get("<?php echo base_url().'sms/sentitems/detail/'; ?>" + id , function(data) {
			$("#popup_content").html(data);
		});
		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 420,
			height: 440,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
		$("#popup1").hide();
		$("#popup").jqxWindow('open');
	}

		$("#popup1").jqxWindow({
			theme: theme, resizable: false,
			width: 250,
			height: 180,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'ID', type: 'number'},
			{ name: 'DestinationNumber', type: 'string'},
			{ name: 'TextDecoded', type: 'string'},
			{ name: 'Status', type: 'string'},
			{ name: 'SendingDateTime', type: 'string'},
			{ name: 'edit', type: 'number'},
			{ name: 'delete', type: 'number'}
        ],
		url: "<?php echo site_url('sms/sentitems/json'); ?>",
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
			autoheight: true,autorowheight: true,
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;
			},
			columns: [
				{ text: 'Tujuan', align: 'center', cellsalign: 'center', datafield: 'DestinationNumber', columntype: 'textbox', filtertype: 'textbox', width: '28%' },
				{ text: 'Isi Pesan', datafield: 'TextDecoded', columntype: 'textbox', filtertype: 'textbox', width: '45%' },
				{ text: 'Pengiriman', align: 'center', cellsalign: 'center', datafield: 'SendingDateTime', columntype: 'date', filtertype: 'none', width: '27%', cellsrenderer: function (row) {
							var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', row);
							return "<div style='width:100%;padding:14px;text-align:center'>"+dataRecord.SendingDateTime+"<br>Status: <b>"+dataRecord.Status+"</b></div>";
						}
				}
            ]
		});

		$("#jqxgrid").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

	        $("#popup_content1").html("<div style='padding:5px' align='center'><br>"+rowData.DestinationNumber+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Detail' onClick='detail(\""+rowData.ID+"\")'> <input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='btn_del(\""+rowData.ID+"\")'><br><br><input class='btn btn-warning' style='width:204px' type='button' value='Close' onClick='close_popup1()'></div></div>");
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup1").jqxWindow('open');
		});
</script>
