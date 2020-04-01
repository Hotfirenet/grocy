<?php
header('Content-type: application/json');
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
include_file('core', 'grocy.inc', 'php', 'grocy');

function returnMsg( $_type, $_msg ) {
    log::add('grocy','debug','ip: ' . network::getClientIp() . ' msg:' . $_msg );
    $msg[$_type] = $_msg;
    echo json_encode($msg);
	die();
}

if (!jeedom::apiAccess(init('apikey'), 'grocy')) {
    returnMsg( 'error', __('Clef API non valide, vous n\'êtes pas autorisé à effectuer cette action (Grocy)', __FILE__) );
}

$barCode = init('text');
$scan_mode = array( 
    'JGROCY-A',
    'JGROCY-C',
    'JGROCY-O'
);

if( empty( $barCode ) ) {
    returnMsg( 'error', __('Erreur lors de la transmission du code barre', __FILE__) );
}

log::add('grocy','debug','code barre scanné: ' . $barCode );

if (config::byKey('scan_mode', 'grocy') == 0) {

    switch ( $barCode ) {
        //Mode scan 
        case $scan_mode[0]:

            grocy::startScanMode( 'scan', 'JGROCY-A', MESSAGE_MODE[$scan_mode[0]] );

            returnMsg( 'state', 'succes' );
            break;

        //mode scan consommation
        case $scan_mode[1]:
        
            grocy::startScanMode( 'scan', 'JGROCY-C', MESSAGE_MODE[$scan_mode[1]] );

            returnMsg( 'state', 'succes' );
            break;
            
        //Mode ouverture
        case $scan_mode[2]:

            grocy::startScanMode( 'scan', 'JGROCY-O', MESSAGE_MODE[$scan_mode[2]] );

            returnMsg( 'state', 'succes' );
            break;
    }

    returnMsg( 'error', __('Type de code barre inconnu pour le passage en mode scan', __FILE__) );
} elseif (config::byKey('scan_mode', 'grocy', 0) == 1) {

    if( in_array( $barCode, $scan_mode ) ) {
        returnMsg( 'error', __('Vous êtes déjà en mode scan !', __FILE__) );
    }
    
    if( grocy::scanProduct( $barCode ) ) {
        returnMsg( 'state', 'succes' );
    } else {
        returnMsg( 'error', __('Erreur a definir !', __FILE__) );
    }
}

returnMsg( 'error', __('Erreur inconnue!', __FILE__) );