$(function() {

	var opts = {
		'discountValue': {
				stepping: 0.01,
				min: 0,
				suffix: '%'
			},
		'discountDuration': {
				decimal: 1,
				min: 1,
				start: 1,
                suffix: ' Month'
			}
	};

	for (var n in opts)
		$("#"+n).spinner(opts[n]);
   
});

