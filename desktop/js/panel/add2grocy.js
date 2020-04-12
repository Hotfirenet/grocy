$(document).ready(function() {
    var error = false;
	$.ajax({
		type: "GET",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
			action   : "getGrocyLocations"
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
            if(data.state=='ok') {
                var locations = data.result.locations;
                var s_location = $('#location');
                s_location.empty();
                for (var i = 0; i < locations.length; i++) {
                    s_location.append('<option value=' + locations[i].id + '>' + locations[i].name + '</option>');
                }
            } else {
                error = true;
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
		}
    });
	$.ajax({
		type: "GET",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
			action   : "getGrocyQuantityUnits"
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
            if(data.state=='ok') {
                var qteunits = data.result.quantityUnits;
                var s_qteUnitsPurchase = $('#qteUnitsPurchase');
                var s_qteUnitsStock = $('#qteUnitsStock');
                s_qteUnitsPurchase.empty();
                s_qteUnitsStock.empty();
                for (var i = 0; i < qteunits.length; i++) {
                    s_qteUnitsPurchase.append('<option value=' + qteunits[i].id + '>' + qteunits[i].name + '</option>');
                    s_qteUnitsStock.append('<option value=' + qteunits[i].id + '>' + qteunits[i].name + '</option>');
                }
            } else {
                error = true;
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
		}
	});
});

$('#bt_createProductInGrocy').on('click', function () {    
    $.ajax({
		type: "POST",
		url: "plugins/grocy/core/ajax/grocy.ajax.php",
		data: {
            action   : "createProductInGrocy",
            formdata : $('#createProductInGrocy').serialize()
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
            var win = window.open(data.result.url, '_blank');
            if (win) {
                win.focus();
            } else {
                alert('Please allow popups for this website');
                $('#md_modal').dialog('close');
            }    
		}
    });
})

