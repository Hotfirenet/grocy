<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once __DIR__  . '/../../../../core/php/core.inc.php';

define('MESSAGE_MODE', array(
    'JGROCY-A' => __('Grocy: Passage en mode achat', __FILE__),
    'JGROCY-C' => __('Grocy: Passage en mode consommation', __FILE__),
    'JGROCY-O' => __('Grocy: Passage en mode ouverture', __FILE__),
));

function searchBarcodeInOpenFoodFactsDB( $barcode ) {

    if( empty( $barcode ) ) {
        $msg = __('Erreur: Aucun code barre', __FILE__);
        log::add('grocy','debug', $msg );
        return json_encode( array( 'error' => $msg ) );
    }
        
    $url = "https://world.openfoodfacts.org/api/v0/product/" . $barcode . ".json";

    try {

        $request_http = new com_http( $url);
        return $request_http->exec(30); 
        
    } catch (\Throwable $th) {

        $msg = __('Erreur: ', __FILE__);
        log::add('grocy','debug', $msg . $th );
        return json_encode( array( 'error' => $msg ) );
    }

    $msg = __('Erreur: Aucun produit trouvé avec ce code barre', __FILE__);
    log::add('grocy','debug', $msg . $th );
    return json_encode( array( 'error' => $msg ) );
}