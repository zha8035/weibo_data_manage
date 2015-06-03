<ul id="file-list" class="file-list" ></ul>
<div id="container-list">
</div>

<style>
.demo-container {
	box-sizing: border-box;
	width: 850px;
	height: 450px;
	padding: 20px 15px 15px 15px;
	margin: 15px auto 30px auto;
	border: 1px solid #ddd;
	background: #fff;
	background: linear-gradient(#f6f6f6 0, #fff 50px);
	background: -o-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -ms-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -moz-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -webkit-linear-gradient(#f6f6f6 0, #fff 50px);
	box-shadow: 0 3px 10px rgba(0,0,0,0.15);
	-o-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-ms-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-moz-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-webkit-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.demo-placeholder {
	width: 100%;
	height: 100%;
	font-size: 14px;
	line-height: 1.2em;
}
.file-list {
	float: left;
}

</style>




<script type="text/javascript">

	
	$(function() {

		// We use an inline data source in the example, usually data would
		// be fetched from a server

		var username = <?php echo '"'.$username.'"' ?>;

		var record_id = <?php echo $record_id ?>;
		var timecount = 0;

		var lastTimestamp = 0;
		var isEnd = false;
		$.ajaxSetup( {
			async: false
		})
		var ecgDataTimer;

		function updateEcgData() {
			// Do a random walk
			if (isEnd == false) {
				url = 'getEcgData/' + username + '/' + record_id + '/' + lastTimestamp;
				$.get(url, function(response) {
					if (response['error'] == 1) {
						isEnd = true;
						return;
					}
					var ecg_data = response['records'];
					var data = [];
					for (var p in ecg_data) {
						var v = ecg_data[p];
						var time = v['upload_time'];
						var d = new Date(parseInt(time)*1000);
						$("#file-list").append("<li>"+d.toLocaleString()+"</li>");
						lastTimestamp = Math.max(lastTimestamp, time);
						var status = v['status'];
						if (status == 3) {
							isEnd = true;
						}
						var ajaxurl = 'ajaxEcgData/' + username + '/' + time;
						
						$.get(ajaxurl, function(record) {

							var res = [];
							var step = 1;
							var ys = record.split(/\s/);
							for (var iy in ys) {
								var num = parseInt(ys[iy]);
								if (!isNaN(num)) {
									res.push([step, num]);
									step++;
								}
							}
							data.push(res);
						})

					}
					for(var i = 0; i < data.length; ++i) {
						var v = ecg_data[i];
						var time = v['upload_time'];
						$('#container-list').append(	'<div class="demo-container"><div id="placeholder'+time%1000 + '" class="demo-placeholder"></div></div><br/>' );
						var plot = $.plot("#placeholder"+time%1000, [ data[i] ], {
						  		series: {
									shadowSize: 0	// Drawing is faster without shadows
								},
								yaxis: {
									min: 0,
									max: 550
								},
								xaxis: {
									show: false
								}
						});
					}
				}, "json");
			}
			// Zip the generated y values with the x values
		}
		updateEcgData();

		// Set up the control widge

	});

	</script>