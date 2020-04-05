<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

require_once __DIR__  . '/../../core/php/grocy.inc.php';

#Si install locale
// if( config::byKey('grocy:install:local','grocy') ) {
//     #echo '<iframe title="Grocy" src="'.config::byKey('grocy_url','grocy').'"></iframe>';
// } else {
//     #redirige sur l'interface
//     header('location: ' . config::byKey('grocy_url','grocy') );
// }

if ( config::byKey('scan_mode', 'grocy') == 1 ) {
	echo '<div class="alert jqAlert alert-warning" id="div_grocyScanAlert" style="padding : 7px 35px 7px 15px;">{{Vous êtes en mode scan de type}} ' . config::byKey('scan_type', 'grocy'). '{{. Recliquez sur le bouton Désactiver pour sortir de ce mode}}</div>';
} else {
	echo '<div class="alert jqAlert alert-warning" id="div_grocyScanAlert" style="padding : 7px 35px 7px 15px; display:none;">Test</div>';
}
//$product = json_decode( grocy::searchBarcodeInOpenFoodFactsDB( '3564700283776' ), true );

$msgScanModeType = config::byKey( 'msgScanModeType', 'grocy' );
$buttonStop      = '<a class="btn btn-danger btn-sm togglePlugin" id="bt_stopScanMode" style="position:relative;top:-2px;"><i class="fas fa-times"></i> {{Désactiver}}</a>';
$buttonA         = '<a class="btn btn-success btn-sm bt_startScanMode" data-mode="A" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode achat</a>';
$buttonC         = '<a class="btn btn-success btn-sm bt_startScanMode" data-mode="C" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode consommation</a>';
$buttonO         = '<a class="btn btn-success btn-sm bt_startScanMode" data-mode="O" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode ouverture</a>';

?> 
<div class="row">
	<div class="col-xs-12" style="padding:20px 5px">
	<a class="btn btn-success btn-sm" id="bt_instanceGrocy" style="position:relative;top:-2px;" href="<?php echo config::byKey('grocy_url', 'grocy'); ?>" target="_blank"><i class="fas fa-wrench"></i> {{Accéder à Grocy}}</a> | 
 <?php

if( config::byKey('scan_mode', 'grocy') == 1 ) { 

	$stateType = config::byKey( 'scan_type', 'grocy' );

	if( $stateType == 'JGROCY-C' || $stateType == 'JGROCY-O' ) {

		if( $stateType == 'JGROCY-C' ) {
			echo $buttonO . ' | ';
		} else {
			echo $buttonC . ' | ';
		}
	}

	echo $buttonStop;

} else { 
	echo $buttonA . ' | ' . $buttonC . ' | ' . $buttonO;
} 
?>

		| <a class="btn btn-success btn-sm" id="bt_inventaire" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Inventaire</a>
		| <a class="btn btn-danger btn-sm" id="bt_supAllProducts" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Supprimer tous les produits</a>

	</div>
</div>
<div class="row" style="background-color: white">
   <div class="col-xs-4 text-center bt_startScanMode" data-mode="A"><img src="plugins/grocy/data/images/modes/JGROCY-A.png" title="{{Mode de scan: Achat}}" alt="JGROCY-A"></div>
   <div class="col-xs-4 text-center bt_startScanMode" data-mode="C"><img src="plugins/grocy/data/images/modes/JGROCY-C.png" title="{{Mode de scan: Consomation}}" alt="JGROCY-C"></div>
   <div class="col-xs-4 text-center bt_startScanMode" data-mode="O"><img src="plugins/grocy/data/images/modes/JGROCY-O.png" title="{{Mode de scan: Ouverture}}" alt="JGROCY-O"></div>
</div>
<div class="row" style="background-color: white">
	<pre><?php  ?></pre>
</div>
<?php

include_file('desktop', 'panel', 'js', 'grocy');?>