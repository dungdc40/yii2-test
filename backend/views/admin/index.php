<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerJsFile('@web/plugins/chartjs/Chart.min.js', ['depends' => [\backend\assets\DashboardAsset::className()]]);

?>
<div class="row">
	<div class="col-md-6">
			<div class="callout callout-danger">
				<h4>Total number of items </h4>
				<p><?= $numberItems ?></p>
			</div>
			<div class="callout callout-warning">
				<h4>Average items price</h4>
				<p><?= $averagePrice ?></p>
			</div>
	</div>
	<div class="col-md-6">	
	<!-- DONUT CHART -->
				  <div class="box box-danger">
					<div class="box-header with-border">
					  <h3 class="box-title">Percent of item per type</h3>
					  <div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					  </div>
					</div>
					<div class="box-body">
						<canvas id="pieChart" style="height:250px"></canvas>
					</div><!-- /.box-body -->
				  </div><!-- /.box -->
	</div>
</div>
<div class="row">
	<div class="col-md-12">
	<div class="box">
		<div class="box-body table-responsive no-padding">
			<?= GridView::widget([
				'dataProvider' => $lastestItems,
				'tableOptions' => ['class' => 'table table-bordered'],
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					'item_name',
					'price',
					'tags',
					[
						'attribute' => 'item_photo',
						'format' => 'html',
						'value' => function($data) {					
							return $data['item_photo'] ? Html::img(Url::toRoute($data['item_photo'],true), ['width'=>'100']) : '';	
						},
					],
					'vandor_name',
					[
						'attribute' => 'vendor_photo',
						'format' => 'html',
						'value' => function($data) {					
							return $data['vendor_photo'] ? Html::img(Url::toRoute($data['vendor_photo'],true), ['width'=>'100']) : '';	
						},
					],
			

				],
			]); ?>
		</div>
	</div>
	</div>
</div>

<?php $this->beginBlock('customJs'); ?>
<script>
	$(document).ready(function(){
		//-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
			<?php
				foreach($percenPerType as $type):
			?>
			<?=
				'{value:'.$type['percentage'].', color:"'.$type['color'].'", highlight:"'.$type['highlight'].'", label:"'.$type['type'].'"},';
			?>
			<?php
				endforeach;
			?>
        ];
        var pieOptions = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
	});
	
</script>
<?php $this->endBlock(); ?>