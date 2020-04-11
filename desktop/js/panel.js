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

$('#bt_supAllProducts').on('click', function () {
	$.ajax({
		type: "POST",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
			action: "supAllProducts"
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			} else {
				$('#div_alert').showAlert({message: 'Suppression des produits ok', level: 'success'});
				return;
			}
		}
	});
});

$('#bt_supAllInQueue').on('click', function () {
	$.ajax({
		type: "POST",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
			action: "supAllInQueue"
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			} else {
				$('#div_alert').showAlert({message: 'Réinitialisation de la file d\'attente ok', level: 'success'});
				$("#queueRow").remove();
				//$("#queueTable").find("tr").remove();
				return;
			}
		}
	});
});

$('.product[data-action=supProductInQueue]').on('click', function () {
	var bt = $(this);
	$.ajax({
		type: "POST",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
			action   : "supProductInQueue",
			eqlogicid: bt.data('eqlogicid')
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			} else {
				bt.closest('tr').remove();
				return;
			}
		}
	});
});