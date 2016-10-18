<script type="text/javascript" language="javascript" src="<?php echo base_url()?>plugins/js/jqwidgets/jqxcheckbox.js"></script>

<?php if(validation_errors()!=""){ ?>
<div class="alert alert-warning alert-dismissable">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4>	<i class="icon fa fa-check"></i> Information!</h4>
  <?php echo validation_errors()?>
</div>
<?php } ?>

<?php if($this->session->flashdata('alert_form')!=""){ ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <h4>  <i class="icon fa fa-check"></i> Information!</h4>
  <?php echo $this->session->flashdata('alert_form')?>
</div>
<?php } ?>


<section class="content">
<form action="<?php echo base_url()?>sms/bc/{action}/{id}" method="POST" name="">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header">
          <h3 class="box-title">{title_form}</h3>
        </div><!-- /.box-header -->
          <div class="box-body">
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <?php 
                if(set_value('status')=="" && isset($status)){
                  $status = $status;
                }else{
                  $status = set_value('status');
                }
                ?>
                <?php foreach ($statusoption as $row=>$val ) { ;?>
                  <option value="<?php echo $row; ?>"  <?php echo ($status==$row ? "selected":"") ?>><?php echo $val; ?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label>Kategori</label>
              <select name="id_sms_tipe" class="form-control">
                <?php 
                if(set_value('id_sms_tipe')=="" && isset($id_sms_tipe)){
                  $tipe = $id_sms_tipe;
                }else{
                  $tipe = set_value('id_sms_tipe');
                }
                ?>
                <?php foreach ($tipeoption as $row ) { ;?>
                  <option value="<?php echo $row->id_tipe; ?>"  <?php echo ($tipe==$row->id_tipe ? "selected":"") ?>><?php echo $row->nama; ?></option>
                <?php }?>
              </select>
            </div>
            <div class="form-group">
              <label>Pesan</label>
              <textarea class="form-control" rows="8" name="pesan" id="Pesan" placeholder="Pesan" ><?php 
                if(set_value('pesan')=="" && isset($pesan)){
                  echo $pesan;
                }else{
                  echo  set_value('pesan');
                }
                ?></textarea>
              <label id="counting" style="text-align:right;padding:2px;width:100%">160</label>
            </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Periode SMS Aktif </label>
                    <?php 
                    if(set_value('tgl_mulai')=="" && isset($tgl_mulai)){
                      $tgl_mulai = $tgl_mulai;
                    }else{
                      $tgl_mulai = strtotime(set_value('tgl_mulai'));
                    }
                    if(set_value('tgl_akhir')=="" && isset($tgl_akhir)){
                      $tgl_akhir = $tgl_akhir;
                    }else{
                      $tgl_akhir = strtotime(set_value('tgl_akhir'));
                    }
                    ?>
                    <div id='periode'></div>
                    <input type="hidden" name="tgl_mulai" value="<?php echo date("d-m-Y",$tgl_mulai) ?>">
                    <input type="hidden" name="tgl_akhir" value="<?php echo date("d-m-Y",$tgl_akhir) ?>">
                    <div>
                      <br>
                      <label>Tanggal Berlaku : </label>
                      <br>
                      <span id="tgl_mulai"><?php echo date("d-m-Y",$tgl_mulai) ?></span> 
                      <span style="padding-left:10px;padding-right:10px">s/d </span>
                      <span id="tgl_akhir"><?php echo date("d-m-Y",$tgl_akhir) ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Jadwal</label>
                    <select name="is_loop" id="is_loop" class="form-control">
                      <?php 
                      if(set_value('is_loop')=="" && isset($is_loop)){
                        $is_loop = $is_loop;
                      }else{
                        $is_loop = set_value('is_loop');
                      }
                      ?>
                      <?php foreach ($jenisoption as $row=>$val ) { ;?>
                        <option value="<?php echo $row; ?>"  <?php echo ($is_loop==$row ? "selected":"") ?>><?php echo $val; ?></option>
                      <?php }?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Jam </label>
                      <?php 
                      if(set_value('is_harian')=="" && isset($is_harian)){
                        $is_harian = $is_harian;
                      }else{
                        $is_harian = strtotime($this->input->post('is_harian'));
                      }
                      ?>
                    <div id='is_harian' name="is_harian"></div>
                  </div>
                  <div class="form-group">
                    <label>Hari </label>
                    <select name="is_mingguan" id="is_mingguan" class="form-control">
                      <?php 
                      if(set_value('is_mingguan')=="" && isset($is_mingguan)){
                        $is_mingguan = $is_mingguan;
                      }else{
                        $is_mingguan = set_value('is_mingguan');
                      }
                      ?>
                      <?php foreach ($harioption as $row=>$val ) { ;?>
                        <option value="<?php echo $row; ?>"  <?php echo ($is_mingguan==$row ? "selected":"") ?>><?php echo $val; ?></option>
                      <?php }?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Tanggal </label>
                    <select name="is_bulanan" id="is_bulanan" class="form-control">
                      <option value="" >-</option>
                      <?php 
                      if(set_value('is_bulanan')=="" && isset($is_bulanan)){
                        $is_bulanan = $is_bulanan;
                      }else{
                        $is_bulanan = set_value('is_bulanan');
                      }
                      ?>
                      <?php 
                        for($i=1;$i<=31;$i++){ 
                          $str = $i<10 ? "0".$i : $i;
                      ?>
                        <option value="<?php echo $str; ?>"  <?php echo ($str==$is_bulanan ? "selected":"") ?>><?php echo $str; ?></option>
                      <?php }?>
                    </select>
                  </div>
              </div>
            </div>
         </div><!-- /.box-body -->
      </div><!-- /.box -->
  	</div><!-- /.box -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-success" style="min-height:350px">
          <div class="box-footer pull-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-warning">Ulang</button>
            <button type="button" class="btn btn-success" onClick="document.location.href='<?php echo base_url()?>sms/bc'">Kembali</button>
          </div>
          <div class="box-body" style="margin-top:10px">
          <?php if($action=="edit"){?>
            <div class="form-group">
              <label>Daftar Penerima</label>
            </div>
            <div class="div-grid">
                <div id="jqxgrid_penerima"></div>
            </div>
          <?php } ?>
          </div><!-- /.box-body -->
      </div><!-- /.box -->
      <?php if($action=="edit"){?>
      <div class="box box-warning" style="min-height:350px">
          <div class="box-body">
            <div class="form-group">
              <label>Pilih Nomor Penerima</label>
              <select id="id_sms_grup" class="form-control pull-right" style="width:40%">
                <option value="">-- Pilih Grup --</option>
                <?php foreach ($grupoption as $row ) { ;?>
                  <option value="<?php echo $row->id_grup; ?>" ><?php echo $row->nama; ?></option>
                <?php }?>
              </select>
            </div>
            <div class="div-grid">
                <div id="jqxgrid_pilih"></div>
            </div>
          </div><!-- /.box-body -->
      </div><!-- /.box -->
      <?php } ?>
    </div><!-- /.box -->
  </div><!-- /.box -->
</form>
</section>

<script>
	$(function () {	
    $("#menu_esms").addClass("active");
    $("#menu_sms_bc").addClass("active");

    $("#Pesan").keyup(function(){
      var max = 160;
      var len = $(this).val().length;
      $("#counting").html(max-len);
    }).keyup();

    $("#is_loop").change(function(){
      var is_loop = $(this).val();
      if(is_loop=="tidak"){
        $("#is_mingguan").attr("disabled","true");
        $("#is_bulanan").attr("disabled","true");
      }
      else if(is_loop=="harian"){
        $("#is_mingguan").attr("disabled","true");
        $("#is_bulanan").attr("disabled","true");
      }
      else if(is_loop=="mingguan"){
        $("#is_mingguan").removeAttr("disabled");
        $("#is_bulanan").attr("disabled","true");
      }
      else{
        $("#is_mingguan").attr("disabled","true");
        $("#is_bulanan").removeAttr("disabled");
      }

    }).change();

    var time1 = new Date();
    time1.setHours(<?php echo date("H",$is_harian) ?>);
    time1.setMinutes(<?php echo date("i",$is_harian) ?>);
    time1.setSeconds(<?php echo date("s",$is_harian) ?>);
    $("#is_harian").jqxDateTimeInput({ width: '100%', height: '31px', formatString: 'T', showTimeButton: true, showCalendarButton: false});
    $("#is_harian").jqxDateTimeInput('setDate', time1);
  

    var date1 = new Date();
    date1.setFullYear(<?php echo date("Y",$tgl_mulai)?>,<?php echo (date("m",$tgl_mulai)-1)?>,<?php echo date("d",$tgl_mulai)?>);
    var date2 = new Date();
    date2.setFullYear(<?php echo date("Y",$tgl_akhir)?>,<?php echo (date("m",$tgl_akhir)-1)?>,<?php echo date("d",$tgl_akhir)?>);
    $("#periode").jqxCalendar({ width: 220, height: 220, selectionMode: 'range' });
    $("#periode").jqxCalendar('setRange', date1, date2);
    $("#periode").on('change', function (event) {
        var selection = event.args.range;
        var tgl_mulai = selection.from.toLocaleDateString().split("/");
        var tgl_akhir = selection.to.toLocaleDateString().split("/");

        $("[name='tgl_mulai']").val(tgl_mulai[1]+"-"+tgl_mulai[0]+"-"+tgl_mulai[2]);
        $("#tgl_mulai").html(tgl_mulai[1]+"-"+tgl_mulai[0]+"-"+tgl_mulai[2]);
        $("[name='tgl_akhir']").val(tgl_akhir[1]+"-"+tgl_akhir[0]+"-"+tgl_akhir[2]);
        $("#tgl_akhir").html(tgl_akhir[1]+"-"+tgl_akhir[0]+"-"+tgl_akhir[2]);
    });


    $("#id_sms_grup").change(function(){
      $.post("<?php echo base_url().'sms/pbk/filter' ?>", 'id_sms_grup='+$(this).val(),  function(){
        $("#jqxgrid_pilih").jqxGrid('updatebounddata', 'cells');
      });
    });

	});


<?php if($action=="edit"){?>
     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id', type: 'string'},
      { name: 'nomor', type: 'string'},
      { name: 'nama', type: 'string'},
      { name: 'grup', type: 'string'}
        ],
    url: "<?php echo site_url('sms/bc/json_penerima/'.$id); ?>",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgrid_penerima").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgrid_penerima").jqxGrid('updatebounddata', 'sort');
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
      $("#jqxgrid_penerima").jqxGrid('clearfilters');
    });

    $("#jqxgrid_penerima").jqxGrid(
    {   
      width: '100%',
      selectionmode: 'singlerow',
      source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25'],
      showfilterrow: false, filterable: false, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
      selectionmode: 'checkbox',
      rendergridrows: function(obj)
      {
        return obj.data;    
      },
      columns: [
        { text: 'Nomor', datafield:'nomor', columntype: 'textbox', filtertype: 'textbox', width: '40%' },
        { text: 'Nama', datafield:'nama', columntype: 'textbox', filtertype: 'textbox', width: '27%' },
        { text: 'Grup', datafield:'grup', columntype: 'textbox', filtertype: 'textbox', width: '27%' }
      ]
    });

    $("#jqxgrid_penerima").on('rowselect', function (event) {
      var args = event.args;
      var row = args.rowindex;

      if(row.length== undefined){
        var datarow = $("#jqxgrid_penerima").jqxGrid('getrowdata', row);
        unselected(datarow.id);
      }else{
        $.each( row, function( rows ) {
        var datarow = $("#jqxgrid_penerima").jqxGrid('getrowdata', rows);
          unselected(datarow.id);
        });        
      }

      $("#jqxgrid_pilih").jqxGrid('updatebounddata', 'filter');
      $("#jqxgrid_penerima").jqxGrid('updatebounddata', 'filter');
      $("#jqxgrid_penerima").jqxGrid('clearselection');
    });






     var source = {
      datatype: "json",
      type  : "POST",
      datafields: [
      { name: 'id', type: 'string'},
      { name: 'nomor', type: 'string'},
      { name: 'nama', type: 'string'},
        ],
    url: "<?php echo site_url('sms/bc/json_pbk/'.$id); ?>",
    cache: false,
    updaterow: function (rowid, rowdata, commit) {
      },
    filter: function(){
      $("#jqxgrid_pilih").jqxGrid('updatebounddata', 'filter');
    },
    sort: function(){
      $("#jqxgrid_pilih").jqxGrid('updatebounddata', 'sort');
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
      $("#jqxgrid_pilih").jqxGrid('clearfilters');
    });

    $("#jqxgrid_pilih").jqxGrid(
    {   
      width: '100%',
      selectionmode: 'singlerow',
      source: dataadapter, theme: theme,columnsresize: true,showtoolbar: false, pagesizeoptions: ['10', '25'],
      showfilterrow: true, filterable: true, sortable: true, autoheight: true, pageable: true, virtualmode: true, editable: false,
      selectionmode: 'checkbox',

      rendergridrows: function(obj)
      {
        return obj.data;    
      },
      columns: [
        { text: 'Nomor', datafield:'nomor', columntype: 'textbox', filtertype: 'textbox', width: '40%' },
        { text: 'Nama', datafield:'nama', columntype: 'textbox', filtertype: 'textbox', width: '54%' }
      ]
    });

    $("#jqxgrid_pilih").on('rowselect', function (event) {
      var args = event.args;
      var row = args.rowindex;

      if(row.length== undefined){
        var datarow = $("#jqxgrid_pilih").jqxGrid('getrowdata', row);
        selected(datarow.id);
      }else{
        $.each( row, function( rows ) {
        var datarow = $("#jqxgrid_pilih").jqxGrid('getrowdata', rows);
          selected(datarow.id);
        });        
      }

      $("#jqxgrid_penerima").jqxGrid('updatebounddata', 'filter');
      $("#jqxgrid_pilih").jqxGrid('updatebounddata', 'filter');
      $("#jqxgrid_pilih").jqxGrid('clearselection');
    });

    function unselected(number){
      $.post("<?php echo base_url().'sms/bc/remove_number/'.$id ?>/"+number,  function(){
      });
    }

    function selected(number){
      $.post("<?php echo base_url().'sms/bc/add_number/'.$id ?>/"+number,  function(){
      });
    }
<?php }?>

</script>
