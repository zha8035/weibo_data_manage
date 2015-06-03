$(function() {
	var param = null;
	if ($('#topic') != null) {
		param = $("#topic_value").text().trim();
	}
	$.get("getSummary", {"topic": param} , function(data) {

		d = JSON.parse(data);

		

		var yes = [],
			no =[],
			unknow = [];
		var user = [];
		for (var i = 0; i < d.length; ++i) {
		//	ticks.push([i, d[i].user]);
			yes.push([i, d[i].yes]);
			no.push([i, d[i].no]);
			unknow.push([i, d[i].unknow]);
			user.push([i, d[i].user]);
		}
		
/*	
		var yes = d.map(function(d) {return [d.user, d.yes]});
		var no = d.map(function(d) {return [d.user, d.no]});
		var unknow = d.map(function(d) {return [d.user, d.unknow]});

*/
		var stack = 1,
			bars = true,
			lines = false,
			steps = false;

		function plotWithOptions() {
			$.plot("#summaryHolder", [ 
					{label: "Yes", data: yes},
					{label: "No", data: no}, 
					{label: "UnKnow", data: unknow}
				], {
				series: {
					stack: stack,
					lines: {
						show: lines,
						fill: true,
						steps: steps
					},
					bars: {
						show: bars,
						barWidth: 0.6
					},
				},
				xaxis: {
						mode: "categories",
						tickLength: 0,
						ticks: user
					}
			});
		}

		plotWithOptions();
	})
	var d1 = [];
		for (var i = 0; i <= 10; i += 1) {
			d1.push([i, parseInt(Math.random() * 30)]);
		}

		var d2 = [];
		for (var i = 0; i <= 10; i += 1) {
			d2.push([i, parseInt(Math.random() * 30)]);
		}

		var d3 = [];
		for (var i = 0; i <= 10; i += 1) {
			d3.push([i, parseInt(Math.random() * 30)]);
		}

		
		
})