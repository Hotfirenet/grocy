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

$msgScanModeType = config::byKey( 'msg_scan_mode_type', 'grocy' );
$tmpQueue         = config::byKey( 'tmp_queue', 'grocy');
$buttonStop       = '<a class="btn btn-danger btn-sm" id="bt_stopScanMode" style="position:relative;top:-2px;"><i class="fas fa-times"></i> {{Désactiver}}</a>';
$buttonA          = '<a class="btn btn-success btn-sm bt_startScanMode" data-mode="A" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode achat</a>';
$buttonC          = '<a class="btn btn-success btn-sm bt_startScanMode" data-mode="C" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode consommation</a>';
$buttonO          = '<a class="btn btn-success btn-sm bt_startScanMode" data-mode="O" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode ouverture</a>';
$buttonResetQueue = '<a class="btn btn-danger btn-sm" id="bt_supAllInQueue" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Réinitialiser la file d\'attente</a>';

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
		<?php if( count( $tmpQueue ) >= 1 ) echo ' | ' . $buttonResetQueue; ?> 
	</div>
</div>
<div class="row" style="background-color: white">
   <div class="col-xs-4 text-center bt_startScanMode" data-mode="A"><img src="plugins/grocy/data/images/modes/JGROCY-A.png" title="{{Mode de scan: Achat}}" alt="JGROCY-A"></div>
   <div class="col-xs-4 text-center bt_startScanMode" data-mode="C"><img src="plugins/grocy/data/images/modes/JGROCY-C.png" title="{{Mode de scan: Consomation}}" alt="JGROCY-C"></div>
   <div class="col-xs-4 text-center bt_startScanMode" data-mode="O"><img src="plugins/grocy/data/images/modes/JGROCY-O.png" title="{{Mode de scan: Ouverture}}" alt="JGROCY-O"></div>
</div>
<br />
<div class="row" id="queueRow">
	<div class="col-xs-12">
		<h2>File d'attente</h2>
		<table class="table table-striped table-bordered table-responsive table-sortable" id="queueTable">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Nom</th>
					<th scope="col">Code barre</th>
					<th scope="col">Quantité</th>
					<th scope="col">Produit dans Grocy</th>
					<th scope="col" class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$i = 0;
				foreach( $tmpQueue as $eqLogicId ) {
					$eqLogicQueue = eqLogic::byId( $eqLogicId );
					if( is_object( $eqLogicQueue ) ) {
						$jStock = grocyCmd::byEqLogicIdAndLogicalId( $eqLogicId, 'stock' );
						if( is_object($jStock) ) {
							$stockValue = $jStock->execCmd();
						}
						$i++;
					?>
						<tr>
							<th scope="row"><?php echo $i; ?></th>
							<td><?php echo $eqLogicQueue->getName(); ?></td>
							<td><?php echo $eqLogicQueue->getConfiguration('barcode'); ?></td>
							<td><input type="text" name="qte" value="<?php echo $stockValue; ?>" size="5"></td>
							<td>
								<select id="grocyProduct" name="grocyProduct">
									<option name="">Aucun</option>
								</select>
							</td>
							<td class="text-center">
								<a class="btn btn-info btn-sm product"  data-action="" style="margin-right:2%">Ajouter à Grocy</a>
								<a class="btn btn-warning btn-sm product" data-action="" style="margin-right:2%">Consommer</a>
								<a class="btn btn-danger btn-sm product" data-action="supProductInQueue" data-eqlogicid="<?php echo $eqLogicId; ?>">Supprimer</a>
							</td>
						</tr>
					<?php
					}
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<div id="debug" class="row" style="background-color: white">
	<pre><?php  ?></pre>
</div>
<?php

include_file('desktop', 'panel', 'js', 'grocy');?>