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

if (config::byKey('scan_mode', 'grocy', 0) == 1) {
	echo '<div class="alert jqAlert alert-warning" id="div_grocyScanAlert" style="padding : 7px 35px 7px 15px;">{{Vous êtes en mode scan. Recliquez sur le bouton scan pour sortir de ce mode}}</div>';
} else {
	echo '<div id="div_grocyScanAlert"></div>';
}

?> 
<div class="row row-overflow">
   <div class="col-xs-12 eqLogicThumbnailDisplay">
 <?php

if( config::byKey('scan_mode', 'grocy', 0) == 1 ) { ?>
    	<a class="btn btn-danger btn-sm togglePlugin" id="bt_stopScanMode" style="position:relative;top:-2px;"><i class="fas fa-times"></i> {{Désactiver}}</a>
<?php } else  { ?>
		<a class="btn btn-success btn-sm bt_startScanMode" data-mode="A" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode achat</a> |
		<a class="btn btn-success btn-sm bt_startScanMode" data-mode="C" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode consommation</a> | 
		<a class="btn btn-success btn-sm bt_startScanMode" data-mode="O" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Activer le mode ouverture</a>
<?php } ?>

		| <a class="btn btn-success btn-sm" id="bt_inventaire" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Inventaire</a>
		| <a class="btn btn-danger btn-sm" id="bt_supAllProducts" style="position:relative;top:-2px;"><i class="fas fa-wrench"></i> Supprimer tous les produits</a>

	</div>
</div>
<?php

include_file('desktop', 'panel', 'js', 'grocy');?>