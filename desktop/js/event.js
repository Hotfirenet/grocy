$('body').on('grocy::change', function (_event,_options) {
    console.log(_event);
    console.log(_options);
    $('#md_modal').dialog({title: _options.title});
    $('#md_modal').attr('data-clink', 'modal');
    $('#md_modal').load('index.php?v=d&plugin=grocy&modal=modal.grocy&type=modal').dialog('open');
});

$('body').on('grocy::scanState', function (_event,_options) {

    //$('#div_alert').showAlert({message: _options.msg, level: 'warning'});

    var params = new window.URLSearchParams(window.location.search);
    if( params.get('m') == 'grocy' && ( params.get('p') == 'panel' || params.get('p') == 'grocy' ) ) {
        location.reload();
    }
    return;
});