<?php

$data = $this->franklin->storage($Test)->getLatestValues(30);

if (empty($data)) {
	echo 'no-data';
	return true;
}
foreach($data as $index => $line) {
	$data[$index] = array(
		$line[0]->format('c'),
		$line[1],
		0
	);
	if (isset($data[$index - 1])) {
		$data[$index][2] = $data[$index - 1][1] - $data[$index][1];
	}
}

$chartId = 'chart-'.$Test->uniqueId();

?>
<script type="text/javascript">
	onChartsReady(function() {
		var chartData = <?php echo json_encode($data); ?>;
		for(i = 0; i < chartData.length; i++) {
			chartData[i][0] = new Date(chartData[i][0]);
		}
		var data = new google.visualization.DataTable();
		data.addColumn('date', 'Date');
		data.addColumn('number', 'Value');
		data.addColumn('number', 'Delta');
		data.addRows(chartData);
		var options = {
			strictFirstColumnType: false,
			titlePosition: 'none',
			lineWidth: 2,
			pointSize: 3,
			chartArea: {
				left: '15%',
				width: '85%',
				height: '80%'
			},
			seriesType: 'line',
			series: {
				1: {
					type: 'bars'
				}
			},
			// theme: 'maximized',
			legend: {
				position: 'none'
			}
		};
		var chart = new google.visualization.ComboChart(document.getElementById('<?php echo $chartId; ?>'));
		chart.draw(data, options);
	});
</script>
<div class="chart line-chart" id="<?php echo $chartId; ?>" style="width: 100%; height: 100%;"></div>
