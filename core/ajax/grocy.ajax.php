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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');
    include_file('core', 'grocy.inc', 'php', 'grocy');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    
    ajax::init();

	if (init('action') == 'checkGrocyInstance') {
        $result = grocy::checkGrocyInstance();
        if( is_json( $result ) ) {
            ajax::success();
        } else {
            if( is_object() ) {
                ajax::error(displayException($result), $result->getCode());
            } else {
                ajax::error('Erreur de connexion');
            }
            
        }
        log::add('grocy','debug','check : ' . $result );
    }
   
    if( init('action') == 'startScanMode') {
        $type = init('type');
        if( grocy::startScanMode( 'scan', $type, MESSAGE_MODE['JGROCY-A'] ) ) { 
            ajax::success();
        } else {
            ajax::error('Erreur de connexion');
        }       
    }    

    if( init('action') == 'stopScanMode') {
        if( grocy::stopScanMode() ) { 
            ajax::success();
        } else {
            ajax::error('Erreur de connexion');
        }       
    }

    if( init('action') == 'syncGrocy') {
        if( grocy::syncGrocy() ) {
            ajax::success();
        } else {
            ajax::error( __('Erreur lors de la création des emplacements, voir les logs.', __FILE__) );
        }
    }

    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}

