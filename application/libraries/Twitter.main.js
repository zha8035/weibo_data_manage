$(function() {
	var precent = $('#precent');
	precent.slider("disable");
	$('input[type=radio]').click( function() {
		var total = $('input[type=radio]');
		var checked = total.filter(':checked');
		precent.val(checked.size()).slider("refresh");
	});

	$('.submit').click(function() {
		var total = $('input[type=radio]');
		var checked = total.filter(':checked');
		if (total.size()/3 > checked.size()) {
			alert(total.size()/3-checked.size() + " task to be finished");
		} else {
			var para = checked.map(function(){
				var r = {};
				r['id'] = this.name;
				r['value'] = this.value;
				return r;
			}).get();
			var data = {};
			data['data'] = para;
			data['user'] = $('#username').text();
			data['topic'] = $('#topic').text();
			/// TODO POST para and insert to SQL
			$.post("acceptResult", {'data': JSON.stringify(data)}, function(data, status) {
				alert(data);
			})
		}
	})
})
