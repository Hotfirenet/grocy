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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function grocy_install() {
    
    $scanModeType = array( 
        'JGROCY-A',
        'JGROCY-C',
        'JGROCY-O'
    );

    $msgScanModeType = array(
        'JGROCY-A' => __('Grocy: Passage en mode achat', __FILE__),
        'JGROCY-C' => __('Grocy: Passage en mode consommation', __FILE__),
        'JGROCY-O' => __('Grocy: Passage en mode ouverture', __FILE__),
    );

    config::save('scanModeType'   , $scanModeType   , 'grocy');
    config::save('msgScanModeType', $msgScanModeType, 'grocy');
}

function grocy_update() {
    
}


function grocy_remove() {

    config::remove('scanModeType'   , 'grocy');
    config::remove('msgScanModeType', 'grocy');
}


