$('#bt_stopScanMode').on('click', function () {
	$.ajax({
		type: "POST",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
			action: "stopScanMode",
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			location.reload();
		}
	});
});

$('.bt_startScanMode').on('click', function () {
	$.ajax({
		type: "POST",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
			action: "startScanMode",
			type: 'JGROCY-' + $(this).data('mode'),
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			location.reload();
		}
	});
});

$('#bt_inventaire').on('click', function () {
	alert('je ne faire rien pour le moment!')
});