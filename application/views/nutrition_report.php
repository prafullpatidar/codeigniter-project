<?php
$crewBymonth = [];
if(!empty($member_list)){
    foreach ($member_list as $row) {
        $crewBymonth[$row->month.'-'.$row->year][] = $row;
    }
}
?>

<style>
    .controls-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ccc;
      padding: 15px 20px;
      border-radius: 8px;
      max-width: 600px;
      margin: auto;
      background-color: #f9f9f9;
    }

    .select-box label {
      margin-right: 10px;
      font-weight: bold;
    }

    .view-icons {
      display: flex;
      gap: 10px;
    }

    .icon-button {
      border: none;
      background: none;
      cursor: pointer;
      font-size: 24px;
      transition: color 0.3s;
    }

    .icon-button:hover {
      color: #007BFF;
    }

    /* Optional: Active view styling */
    .icon-button.active {
      color: #007BFF;
    }
  </style>
<script src="<?php echo base_url(); ?>assets/js/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/highcharts/funnel.js"></script>
<script src="<?php echo base_url(); ?>assets/js/highcharts/exporting.js"></script>

<script src="<?php echo base_url() ?>assets/js/custom_graph.js"></script>  
</script>

<!-- Start page header -->
<div id="tour-11" class="header-content">
  <div class="dt-buttons pull-right" style="margin-top:-5px;">
    </div>
  <h2><span class="icon"><i class="fas fa-user"></i></span> <span class="oblc">/</span><?php echo $heading; ?></h2>
  <div class="clr"></div>
</div>
<!-- /.header-content -->
<!-- Start body content -->
<?php
$succMsg = $this->session->flashdata('succMsg');
if (isset($succMsg) && !empty($succMsg)){
    ?><div class="custom_alert alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $succMsg; ?></div><?php
}
$errMsg = $this->session->flashdata('errMsg');
if (isset($errMsg) && !empty($errMsg)){
    ?><div class="custom_alert alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><?php echo $errMsg; ?></div><?php
}
?>    
<style>
     .parent-row {
      background-color: #e6f2ff;
      cursor: pointer;
    }

    .child-row {
      display: none;
      background-color: #f9f9f9;
    }

    .caret-icon {
      margin-right: 8px;
    }

    .child-table-wrapper {
      text-align: center;
    }

    .nested-table {
      display: inline-table;
      width: 90%; /* Set to your desired width */
      margin: 10px auto;
    }

    .nested-table th,
    .nested-table td {
      text-align: center;
      vertical-align: middle;
      background-color: #fff;
    }

    .nested-table th {
      background-color: #eee;
    }
  </style>
<div class="body-content animated fadeIn body-content-flex">  
    <form class="h-100 d-flex flex-column flex-no-wrap" id="role_list" name="role_list" method="POST" action="<?php echo base_url(); ?>user/getAllroleList">
            <!--Filter head start -->
            <div class="flex-heading panel panel-default shadow no-overflow mt-10 mb-10">
                <div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="selecr-row">
                                <ul class="leadHeader hideBottomLine">
                                    <li class="ship">
                                      
                                        <label>Vessel Name</label><br>
                                         <select class="form-control customFilter" name="ship_id" id="ship_id">
                                           <option value="">Select Vessel</option>
                                             <?php 
                                             if(!empty($ships)){
                                                foreach ($ships as $row) {
                                                 ?>
                                               <option <?php echo ($ship_id == $row->ship_id) ? 'selected' : '';?> value="<?php echo $row->ship_id;?>"><?php echo ucwords($row->ship_name)?></option>
                                                <?php 
                                                }
                                             }
                                            ?>
                                         </select>
                                        
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                <div class="clearfix"></div>
                </div>
            </div>
            <!-- filter head end -->
            <!-- Start table advanced -->
            
    
    <div class="flex-content mb-10">
    <input type="hidden" name="page" value="" id="page" />
        
            <div class="d-flex flex-column h-100 p-10 panel pt-0">
                
                    <input type="hidden" name="sort_column" id="sort_column" value="" />
                    <input type="hidden" name="sort_type" id="sort_type" value="ASC" />
                    <input type="hidden" name="empty_sess" id="empty_sess" value="" />
                    <input type="hidden" name="download" id="download" value="0">
                    <table id="pre_req_name_table" class="table table-bordered">
                     <thead class="t-header">
                            <tr>
                                <th>Month - Year</th>
                                <th width="2%" style="text-align:center;"></th>
                            </tr>
                     </thead>
                     <tbody >
                        <?php
                            $counter = 0;
                            if(!empty($report_data)){
                                foreach ($report_data as $row) {
                                    $counter++;
                                    $monthNum  = $row->month;
                                    $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                                    $monthName = $dateObj->format('F');

                                    $crewMonth = $row->month.'-'.$row->year;
                                  ?>
                                  <tr class="parent-row" data-toggle="child-<?= $counter ?>" data-row_id="<?= $counter ?>" data-id="<?= $row->month_stock_id ?>" data-id2="<?= $row->month.'/'.$row->year ?>" data-ship_id = "<?php echo $row->ship_id;?>">
                                    <td><?php echo $monthName.' - '.$row->year?></td>
                                    <td><i class="fa fa-caret-down" id="icon-<?= $counter;?>"></i></td>
                                 </tr>

                                  <!-- Child Row 1 -->
                                  <tr class="child-row child-<?php echo $counter;?>" id="child-<?php echo $counter;?>">
                                   <?php
                                   if(count($crewBymonth[$crewMonth]) > 0){
                                   ?>
                                    <td colspan="2">
                                    <div class="controls-container">
                                        <div class="select-box">
                                           <select onchange="getMemberWiseData(this.value)">
                                            <option value="<?= $row->month_stock_id ?>">Select Member</option>';
                                            <?php 
                                               if($crewBymonth[$crewMonth]){
                                                    foreach ($crewBymonth[$crewMonth] as $crew) {
                                                      echo '<option value="'.$row->month_stock_id.'-'.$crew->crew_food_habits_id.'">'.ucwords($crew->given_name).' ('.$crew->rank.')</option>';  
                                                    }
                                               } 
                                           ?>
                                                </select>
                                                </div>
                                                <div class="view-icons">
                                                    <button type="button" class="icon-button active graphViewBtn" id="graphViewBtn<?= $row->month_stock_id ?>" title="Graph View<?= $row->month_stock_id ?>" data-id="<?= $row->month_stock_id ?>">
                                                      <i class="fas fa-chart-line"></i>
                                                    </button>
                                                    <button type="button" class="icon-button tableViewBtn" id="tableViewBtn<?= $row->month_stock_id ?>" title="Table View" data-id="<?= $row->month_stock_id ?>">
                                                      <i class="fas fa-table"></i>
                                                    </button>
                                                  </div>
                                            </div> 
                                            <div class="child-table-wrapper  hide" id="table-report-<?php echo $row->month_stock_id ?>">
                                            </div>
                                            <br>
                                            <div class="child-table-wrapper" id="graph-report-<?php echo $row->month_stock_id ?>">
                                            </div>
                                        </td>
                                        <?php
                                        }
                                        else{
                                            ?>
                                            <td colspan="2" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td>
                                        <?php } 
                                        ?>
                                  </tr>
                                <?php }
                            }else{
                                ?>
                                <tr><td colspan="2" align="center" style="font-weight:bold;font-size:15px;">No Data Available</td></tr>
                            <?php } 
                        ?>


                    </tbody>
                    </table>
                <div class="new-style-pagination">
                <div class="total_entries"></div>
                <ul class="pagination pagination-sm">
                    <li class="new_pagination"></li>
                </ul>
                </div>
            </div>
        
    </div><!-- /.panel-body -->                  
  </form>
</div>

<script>
   document.querySelectorAll('.parent-row').forEach(function(row) {
    row.addEventListener('click', function() {
      var rowId = this.getAttribute('data-row_id');
      var childRows = document.querySelectorAll('.child-' + rowId);
      var icon = document.getElementById('icon-' + rowId);

      childRows.forEach(function(childRow) {
        if (childRow.style.display === 'table-row') {
          childRow.style.display = 'none';
          icon.classList.remove('fa-caret-up');
          icon.classList.add('fa-caret-down');
        } else {
          childRow.style.display = 'table-row';
          icon.classList.remove('fa-caret-down');
          icon.classList.add('fa-caret-up');
        }
      });
    });
  });


   $('.parent-row').click(function(){
     var month_stock_id = $(this).data('id');
      if(month_stock_id){
        $.ajax({
            beforeSend : function(){
                $("#customLoader").show();
            },
            type: "POST",
            url: base_url + 'report/getNutritionReportData',
            cache:false,
            data: {'month_stock_id':month_stock_id},
            success : function(msg){
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                if(obj.status=='200'){
                     $('#table-report-'+month_stock_id).html(obj.data);
                        initializeGraph(obj,month_stock_id);
                }
            }
        });
      }
   })

  function initializeGraph(object,month_stock_id){
      var seriesData = object.seriesData;
      var categories = object.categories;
      var chartSeries = [];
        $.each(seriesData, function(nut, data) {
            chartSeries.push({
                name: nut,
                data: data
            });
        }); 

      var $graph = {};
      $graph.div_id = 'graph-report-'+month_stock_id;
      $graph.title = '';
      $graph.xAxis_category = categories;
      $graph.chart_type = 'spline'; 
      $graph.yAxis_title = 'Nutritional Report';
      $graph.series_data = chartSeries;;
      stackedAndGroupedGraph($graph);
   }


   $('#ship_id').change(function(){
        var ship_id = $(this).val();
          window.location.href = base_url + 'report/nutrition_report/'+btoa(ship_id);
   })


   function getMemberWiseData(ids){
        let main_id = ids.split("-");
        let month_stock_id = main_id[0];
        let crew_food_habits_id = main_id[1];
        $.ajax({
                beforeSend : function(){
                    $("#customLoader").show();
                },
                type: "POST",
                url: base_url + 'report/getNutritionReportData',
                cache:false,
                data: {'month_stock_id':month_stock_id,'crew_food_habits_id':crew_food_habits_id},
                success : function(msg){
                    $("#customLoader").hide();
                    var obj = jQuery.parseJSON(msg);
                    if(obj.status=='200'){
                        $('#table-report-'+month_stock_id).html(obj.data);
                        initializeGraph(obj,month_stock_id);
                    }
                }
        }); 
   }


   $(document).ready(function(){
        $('.tableViewBtn').click(function(){
            var id = $(this).data('id');
            $('#tableViewBtn'+id).addClass('active');
            $('#graphViewBtn'+id).removeClass('active');
            $('#graph-report-'+id).addClass('hide');
            $('#table-report-'+id).removeClass('hide');
        })

        $('.graphViewBtn').click(function(){
            var id = $(this).data('id');
            $('#graphViewBtn'+id).addClass('active');
            $('#tableViewBtn'+id).removeClass('active');
            $('#graph-report-'+id).removeClass('hide');
            $('#table-report-'+id).addClass('hide');
        })

        
   })

</script>