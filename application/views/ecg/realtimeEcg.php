
<ul id="file-list" class="file-list" >
</ul>
<div class="demo-container">
	<div id="placeholder" class="demo-placeholder"></div>
	<input name="time-slider" id="time-slider" data-highlight="true" min="0" max="1" step="0.01" value="0">  
</div>
<br/>
	<p>Time milliseconds between updates: </p>
	<input id="updateInterval" type="text" value="">
	<p>User Name: </p>
	<input id="username" type="text" value="">
	<p>record id</p>
	<input id="record_id" type="text" value="">

	<button id="start" type="button" value="start">Start</button>
	<button id="print" type="button" value="print">Show All(print)</button>

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

		var data = [];
		var res = [];


		var pointInWindow = $('#placeholder').width()/2 || 300;
		for (var i = 0; i < pointInWindow; ++i) {
			data.push(0);
		}

		for (var i = 0; i < data.length; ++i) {
			res.push([i, data[i]])
		}

		var username = 'user1';
		$("#username").val(username).change(function() {
			username = $(this).val();
		})
		var record_id = '1';
		$('#record_id').val(record_id).change(function() {
			record_id = $(this).val();
		})

		var timecount = 0;

		var lastTimestamp = 0;
		var isEnd = false;
		var timePerBlock = 15;
		var totalPoint = pointInWindow;
		var totalBlock = 1;
		
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
							totalBlock++;
							var ys = record.split(/\s/);
							totalPoint += ys.length; 
							for (var iy in ys) {
								
								var num = parseInt(ys[iy]);
								if (!isNaN(num)) {
									data.push(num);
								}
							} 
						});

					};
					ecgDataTimer = setTimeout(updateEcgData, timePerBlock*1000);
				}, "json");
			}
			// Zip the generated y values with the x values
		}

		// Set up the control widget

		var updateInterval = 40;
		$("#updateInterval").val(updateInterval).change(function () {
			var v = $(this).val();
			if (v && !isNaN(+v)) {
				updateInterval = +v;
				if (updateInterval < 1) {
					updateInterval = 1;
				} else if (updateInterval > 1000) {
					updateInterval = 1000;
				}
				$(this).val("" + updateInterval);
			}
		});

		var slidFlg = true;
		var pointPerSec = 10;
		var precent = $("#time-slider");
		
		precent.slider({
			start: function(e) {
				slidFlg = false;
			},
			stop: function(e) {
				var newstep = $("#time-slider").val() * data.length / pointPerSec;
				timecount = Math.floor(newstep);
				slidFlg = true;
			}
		});


		var plot = $.plot("#placeholder", [ res ], {
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

		

		function updatePlot() {
			pointPerSec = Math.floor(totalPoint/totalBlock/timePerBlock*updateInterval/1000);
			var ans = [];
			if (data.length > 0) {
				for (var i = 0; i < Math.min(data.length-timecount*pointPerSec, pointInWindow); ++i) {
					ans.push([i, data[i+timecount*pointPerSec]]);
				}
			}

			if (slidFlg) {
				precent.val(timecount*pointPerSec/data.length);
				precent.slider("refresh");
			}

			timecount++;
			plot.setData([ ans ]);
			plot.draw();
			setTimeout(updatePlot, updateInterval);
		}


		updatePlot();
		updateEcgData();

		$('#start').click(function() {
			data = [];
			for (var i = 0; i < pointInWindow; ++i) {
				data.push(0);
			}
			clearTimeout(ecgDataTimer);
			timecount = 0;
			lastTimestamp = 0;
			isEnd = false;
			$("#file-list").empty();
			updateEcgData();
		});

		$('#print').click(function() {
			var url = "printview?username="+username + "&record_id=" + record_id;
			window.open(url);

		});
	});

	</script>