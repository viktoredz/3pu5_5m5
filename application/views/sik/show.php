
<!-- Info boxes -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fa fa-ambulance"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pus Kecamatan</span>
        <span class="info-box-number" style="font-size:14px;"><?php echo $j_puskesmas ;?><br>Puskesmas</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><i class="fa fa-bank"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Jumlah Ruangan </span>
        <span class="info-box-number" style="font-size:14px;"><?php foreach ($j_ruangan as $row) { 
        echo number_format($row->jml);  
        }?><br>Ruangan</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix visible-sm-block"></div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Nilai Aset</span>
        <span class="info-box-number" style="font-size:14px;">Rp.<br><?php foreach ($j_asset as $row) { 
        echo number_format($row->nilai,2);  
        }?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="fa fa-archive"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Jumlah Aset</span>
        <span class="info-box-number" style="font-size:14px;">
        <?php foreach ($j_asset as $row) { 
        echo number_format($row->jml);  
        }?>
        <br>Barang</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
</div><!-- /.row -->

<div class="row">
  <div class="col-md-7">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Kondisi Aset Puskesmas </h3>
          <select name="bar_tipe" id="type" class="form-control" style="width:25%;float:right;margin-top:30px">
            <option value="jml">Jumlah Aset</option>
            <option value="nilai">Nilai Aset</option>
          </select>
          <br>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!-- /.box-header -->
        <div class="box-body">
        <div class="row" id="row_dim">
          <div class="chart">
            <canvas id="barChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
          </div>
        </div>
        <div class="row" id="row_dim2" >
          <div class="chart">
            <canvas id="barChart1" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
              <div class="bux"></div> &nbsp; <label>Baik</label>
          </div>
          <div class="col-md-3">
              <div class="bux1"></div> &nbsp; <label>Rusak Ringan</label>
          </div>
          <div class="col-md-3">
              <div class="bux2"></div> &nbsp; <label>Rusak Berat</label>
          </div>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div><!-- /.col -->
  <div class="col-md-5">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Nilai Aset Puskesmas </h3>        
          <select name="pie_tioe" id="type1" class="form-control" style="width:40%;float:right;margin-top:30px">
            <option value="jml">Jumlah Aset </option>
            <option value="nilai" >Nilai Aset</option>
          </select>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!-- /.box-header -->
        <div class="box-body">
        <div class="row" id="row1">
          <div class="chart">
            <canvas id="pieChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
          </div>
        </div>
        <div class="row" id="row2">
          <div class="chart">
            <canvas id="pieChart1" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
          </div>
        </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php  base_url()?>public/themes/disbun/dist/js/pages/dashboard2.js" type="text/javascript"></script> -->
<script>
  $(function () { 
    $("#menu_dashboard").addClass("active");
    $("#menu_morganisasi").addClass("active");


      var areaChartData = {
        labels: [<?php 
        $i=0;
       // print_r($bar);  
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".str_replace(array("KEC. ","KEL. "),"", $row['puskesmas'])."\"";
          $i++;
        } ?>],
        datasets: [
          {
            label: "Baik",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_barang_baik']))  $x = ($row['j_barang_baik']);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Kurang Baik",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_barang_rr']))  $x = ($row['j_barang_rr']);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Rusak Berat",
            fillColor: "#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_barang_rb']))  $x = ($row['j_barang_rb']);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          }
        ]
      };

      var areaChartOptions = {
        showScale: true,
        scaleShowGridLines: false,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve: true,
        bezierCurveTension: 0.3,
        pointDot: false,
        pointDotRadius: 4,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,
        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: true,
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        maintainAspectRatio: false,
        responsive: true
      };


      var areaChartData1 = {
        labels: [<?php 
        $i=0;
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
          echo "\"".str_replace(array("KEC. ","KEL. "),"", $row['puskesmas'])."\"";
          $i++;
        } ?>],
        datasets: [
          {
            label: "Baik",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_barang_baik1']))  $x = ($row['j_barang_baik1']);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Kurang Baik",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_barang_rr1']))  $x = ($row['j_barang_rr1']);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Rusak Berat",
            fillColor: "#e02a11",
            strokeColor: "#e02a11",
            pointColor: "#e02a11",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_barang_rb1']))  $x = ($row['j_barang_rb1']);
              else                              $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          }
        ]
      };

      var areaChartOptions1 = {
        //Boolean - If we should show the scale at all
        showScale: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: false,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,
        //Boolean - Whether the line is curved between points
        bezierCurve: true,
        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.3,
        //Boolean - Whether to show a dot for each point
        pointDot: false,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a color
        datasetFill: true,
        //String - A legend template
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
        maintainAspectRatio: false,
        //Boolean - whether to make the chart responsive to window resizing
        responsive: true
      };


        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['nilai_aset'])) $x = ($row['nilai_aset']);
              else                          $x = 0;
              if($i>0) echo ",";
              echo "
              {
              value: ".$x.",
              color: \"".$color[$i]."\",
              highlight: \"".$color[$i]."\",
              label: \"".$row['puskesmas']."\"
              }";
              $i++;
            }
            ?>
        ];
        var pieOptions = {
          segmentShowStroke: true,
          segmentStrokeColor: "#fff",
          segmentStrokeWidth: 2,
          percentageInnerCutout: 40, // This is 0 for Pie charts
          animationSteps: 100,
          animationEasing: "easeOutBounce",
          animateRotate: true,
          animateScale: false,
          responsive: true,
          maintainAspectRatio: false,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        pieChart.Doughnut(PieData, pieOptions);


        var pieChartCanvas1 = $("#pieChart1").get(0).getContext("2d");
        var pieChart1 = new Chart(pieChartCanvas1);
        var PieData1 = [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['nilai_aset1'])) $x = ($row['nilai_aset1']);
              else                          $x = 0;
              if($i>0) echo ",";
              echo "
              {
              value: ".$x.",
              color: \"".$color[$i]."\",
              highlight: \"".$color[$i]."\",
              label: \"".$row['puskesmas']."\"
              }";
              $i++;
            }
            ?>
        ];

        var pieOptions1 = {
          segmentShowStroke: true,
          segmentStrokeColor: "#fff",
          segmentStrokeWidth: 2,
          percentageInnerCutout: 30, // This is 0 for Pie charts
          animationSteps: 100,
          animationEasing: "easeOutBounce",
          animateRotate: true,
          animateScale: false,
          responsive: true,
          maintainAspectRatio: false,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        pieChart1.Doughnut(PieData1, pieOptions1);



        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        var barChartOptions = {
          scaleBeginAtZero: true,
          scaleShowGridLines: true,
          scaleGridLineColor: "rgba(0,0,0,.05)",
          scaleGridLineWidth: 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines: true,
          barShowStroke: true,
          barStrokeWidth: 2,
          barValueSpacing: 5,
          barDatasetSpacing: 1,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          responsive: true,
          maintainAspectRatio: false
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);



        var barChartCanvas1 = $("#barChart1").get(0).getContext("2d");
        var barChart1 = new Chart(barChartCanvas1);
        var barChartData1 = areaChartData1;
        var barChartOptions1 = {
          scaleBeginAtZero: true,
          scaleShowGridLines: true,
          scaleGridLineColor: "rgba(0,0,0,.05)",
          scaleGridLineWidth: 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines: true,
          barShowStroke: true,
          barStrokeWidth: 2,
          barValueSpacing: 5,
          barDatasetSpacing: 1,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          responsive: true,
          maintainAspectRatio: false
        };

        barChartOptions1.datasetFill = false;
        barChart1.Bar(barChartData1, barChartOptions1);

      
      });
    </script>
    <style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #20ad3a;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #ffb400;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #e02a11;
        margin: 0;
        float: left;
      }
    </style>

    <script>
    $(function() {

      $('#type').change(function(){
        if($(this).val() == 'jml') {
          $('#row_dim').show(); 
          $('#row_dim2').hide(); 
        } else {
          $('#row_dim').hide(); 
          $('#row_dim2').show(); 
        } 
      });
      $('#row_dim2').hide(); 


      $('#type1').change(function(){
        if($(this).val() == 'jml') {
          $('#row1').show(); 
          $('#row2').hide(); 
        } else {
          $('#row1').hide(); 
          $('#row2').show(); 
        } 

      });
      $('#row2').hide(); 
      
  });
</script>
  