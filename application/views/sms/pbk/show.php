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
<form>
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
			 	<button type="button" class="btn btn-primary" onclick="document.location.href='<?php echo base_url()?>sms/pbk/add'"><i class='fa fa-plus-square-o'></i> &nbsp; Tambah Nomor</button>
			 	<button type="button" class="btn btn-success" id="btn-refresh"><i class='fa fa-refresh'></i> &nbsp; Refresh</button>
			 	<button type="button" class="btn btn-danger" id="btn-export"><i class='fa fa-save'></i> &nbsp; Export</button>
			 </div>
    		<div class="col-md-3">
	     		<select id="id_sms_grup" class="form-control">
	     			<option value="">-- Pilih Grup --</option>
					<?php foreach ($grupoption as $row ) { ;?>
						<option value="<?php echo $row->id_grup; ?>" ><?php echo $row->nama; ?></option>
					<?php }?>
	     	</select>
			</div>
	     </div>
        <div class="box-body">
		    <div class="div-grid">
		        <div id="jqxgrid_pbk"></div>
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
		$("#menu_sms_pbk").addClass("active");

		$("#id_sms_grup").change(function(){
			$.post("<?php echo base_url().'sms/pbk/filter' ?>", 'id_sms_grup='+$(this).val(),  function(){
				$("#jqxgrid_pbk").jqxGrid('updatebounddata', 'cells');
			});
		});

		$("#btn-export").click(function(){
			var post = "";
			var filter = $("#jqxgrid_pbk").jqxGrid('getfilterinformation');
			for(i=0; i < filter.length; i++){
				var fltr 	= filter[i];
				var value	= fltr.filter.getfilters()[0].value;
				var condition	= fltr.filter.getfilters()[0].condition;
				var filteroperation	= fltr.filter.getfilters()[0].operation;
				var filterdatafield	= fltr.filtercolumn;

				post = post+'&filtervalue'+i+'='+value;
				post = post+'&filtercondition'+i+'='+condition;
				post = post+'&filteroperation'+i+'='+filteroperation;
				post = post+'&filterdatafield'+i+'='+filterdatafield;
				post = post+'&'+filterdatafield+'operator=and';
			}
			post = post+'&filterscount='+i;

			var sortdatafield = $("#jqxgrid_pbk").jqxGrid('getsortcolumn');
			if(sortdatafield != "" && sortdatafield != null){
				post = post + '&sortdatafield='+sortdatafield;
			}
			if(sortdatafield != null){
				var sortorder = $("#jqxgrid_pbk").jqxGrid('getsortinformation').sortdirection.ascending ? "asc" : ($("#jqxgrid_pbk").jqxGrid('getsortinformation').sortdirection.descending ? "desc" : "");
				post = post+'&sortorder='+sortorder;
			}

			$.post("<?php echo base_url()?>sms/pbk/export",post  ,function(response){
				window.location.href=response;
			});
		});

		$("#popup").jqxWindow({
			theme: theme, resizable: false,
			width: 250,
			height: 180,
			isModal: true, autoOpen: false, modalOpacity: 0.2
		});
	});

      var btn_confirm = "</br></br><input class='btn btn-danger' style='width:100px' type='button' value='Ya' onClick='sync()'> <input class='btn btn-success' style='width:100px' type='button' value='Tidak' onClick='close_popup()'>";
      var btn_ok = "</br></br><input class='btn btn-success' style='width:100px' type='button' value='OK' onClick='close_popup()'>";

	   var source = {
			datatype: "json",
			type	: "POST",
			datafields: [
			{ name: 'no', type: 'number'},
			{ name: 'id', type: 'string'},
			{ name: 'nomor', type: 'string'},
			{ name: 'nama_grup', type: 'string'},
			{ name: 'nama', type: 'string'},
        ],
		url: "<?php echo site_url('sms/pbk/json'); ?>",
		cache: false,
		updaterow: function (rowid, rowdata, commit) {
			},
		filter: function(){
			$("#jqxgrid_pbk").jqxGrid('updateBoundData', 'filter');
		},
		sort: function(){
			$("#jqxgrid_pbk").jqxGrid('updateBoundData', 'sort');
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
			$("#jqxgrid_pbk").jqxGrid('clearfilters');
		});

		$("#jqxgrid_pbk").jqxGrid(
		{
			width: '100%', autoheight: true,autorowheight: true,
			selectionmode: 'singlerow',
			source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25', '50', '100'],
			showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
			rendergridrows: function(obj)
			{
				return obj.data;
			},
			columns: [
				{ text: 'Nomor', datafield: 'nomor', columntype: 'textbox', filtertype: 'textbox', width: '25%' },
				{ text: 'Nama', datafield: 'nama', columntype: 'textbox', filtertype: 'textbox', width: '45%' },
				{ text: 'Group', datafield: 'nama_grup', columntype: 'textbox', filtertype: 'textbox', width: '30%' }

            ]
		});

		$("#jqxgrid_pbk").on('rowselect', function (event) {
			var args = event.args;
			var rowData = args.row;

	        $("#popup_content").html("<div style='padding:5px' align='center'><br>"+rowData.nama+"</br><br><div style='text-align:center'><input class='btn btn-primary' style='width:100px' type='button' value='Edit' onClick='btn_edit(\""+rowData.id+"\")'> <input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='btn_del(\""+rowData.id+"\")'><br><br><input class='btn btn-warning' style='width:204px' type='button' value='Close' onClick='close_popup()'></div></div>");
 			$("html, body").animate({ scrollTop: 0 }, "slow");
			$("#popup").jqxWindow('open');
		});

	function btn_edit(id){
		document.location.href="<?php echo base_url().'sms/pbk/edit/'; ?>" + id;
	}

	function btn_del(id){
		$("#popup").hide();
		$("#popup_content_del").html("<div style='padding:5px'><br><div style='text-align:center'>Hapus Data?<br><br><input class='btn btn-danger' style='width:100px' type='button' value='Delete' onClick='del_pasien("+id+")'>&nbsp;&nbsp;<input class='btn btn-success' style='width:100px' type='button' value='Batal' onClick='close_popup_del()'></div></div>");
          $("#popup_del").jqxWindow({
            theme: theme, resizable: false,
            width: 250,
            height: 150,
            isModal: true, autoOpen: false, modalOpacity: 0.2
          });
        $("#popup_del").jqxWindow('open');
	}

	function del_pasien(id){
		$.post("<?php echo base_url().'sms/pbk/dodel' ?>/" +id,  function(){
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
			$("#jqxgrid_pbk").jqxGrid('updatebounddata', 'cells');
		});
	}

    function close_popup(){
        $("#jqxgrid_pbk").jqxGrid('clearselection');
        $("#popup").jqxWindow('close');
        $("#popup1").jqxWindow('close');
    }

    function close_popup_del(){
        $("#jqxgrid_pbk").jqxGrid('clearselection');
        $("#popup").jqxWindow('close');
        $("#popup1").jqxWindow('close');
        $("#popup_del").jqxWindow('close');
    }

</script>
