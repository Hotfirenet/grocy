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

$('body').on('grocy::rmProductInQueue', function (_event,_options) {

    console.log(_options.eqlogicid);
    
    // $('table#queueTable tr#3').remove();
    $('#'+_options.eqlogicid).closest('tr').fadeOut(500, function() { $(this).remove(); });
});