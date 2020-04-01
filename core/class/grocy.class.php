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

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';
include_file('core', 'api.grocy', 'class', 'grocy');

class grocy extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */
    
	public static function cron15() {
		log::add('grocy','debug','Mise à jour des infos');

	}

	public static function checkGrocyInstance() {

        $url    = config::byKey('grocy_url','grocy');
        $apikey = config::byKey('grocy_apikey','grocy');

        $http   = new grocyAPI($url, $apikey);
        $result = $http->checkInstance();
        log::add('grocy','debug','checkGrocyInstance: ' . print_r( $result, true ) );
        return $result;
    }
    
    public static function startScanMode( $stateMode, $stateType, $msg ) {

        if (config::byKey('scan_mode', 'grocy') == 0) {

            config::save('scan_mode', 1, 'grocy');  
            log::add('grocy','debug','scan_mode: 1' );

            config::save('scan_type', $stateType, 'grocy');  
            log::add('grocy','debug','scan_type: ' . $stateType );

            $scanState = array(
                'mode'  => $stateMode,
                'type'  => $stateType,
                'state' => 1,
                'msg'   => $msg . ', <a href="index.php?v=d&m=grocy&p=panel">Acceder à la page</a>'
            );
            event::add('grocy::scanState', $scanState );
            log::add('grocy','debug','grocy::scanState: ' . print_r( $scanState, true ) );

            $cmd = cmd::byString( config::byKey('grocy_notif_cmd', 'grocy', 0) ); 
            if ( is_object( $cmd ) ) {
                $cmd->execCmd( $options = array( 'title' => 'Jeedom' , 'message' => $msg ), $cache = 0);
                log::add('grocy','debug','Notif message: ' . $msg );
                return true;
            } else {
                log::add('grocy','warning', 'Aucune commande de notfication trouvée' );
                return false;
            }
        } else {
            log::add('grocy','warning', 'Vous êtes déjà dans un mode de scan' );
            return false;
        }
    }

    public static function stopScanMode() {

        config::save('scan_mode', 0, 'grocy'); 
        config::save('scan_type', '', 'grocy');
        log::add('grocy','debug','Désactivation du mode scan' );
        return true;
    }

    public static function scanProduct( $_barcode ) {

        $eqLogics = eqLogic::byTypeAndSearhConfiguration('grocy','"barcode":"'.$_barcode.'"');

        if( $count = count( $eqLogics ) > 0 ) {

            log::add('grocy','debug','scanProduct > ' . $count . ' eqLogic trouvé'  );

            foreach ( $eqLogics as $eqLogic ) {
                self::newStock( $eqLogic->getId(), 1, 1 );
            }

            return true;

        } else {

            // $eqLogic = new grocy();
            // $eqLogic->setName( $product['name'] );
            // $eqLogic->setEqType_name( 'grocy' );
            // $eqLogic->setLogicalId( $logicalId );
            // $eqLogic->setObject_id( self::mapJeeObjectByLocationId( $product['location_id'] ) );
            // $eqLogic->setConfiguration('id_product', $product['id'] );
            // $eqLogic->setConfiguration('barcode', $product['barcode'] );
            // $eqLogic->setConfiguration('id_stock', $product['qu_id_stock'] );
            // // TODO $eqLogic->setConfiguration('image', $this->getImage());
            // $eqLogic->setIsEnable(1);
            // $eqLogic->setIsVisible('0');
            // $eqLogic->setDisplay('icon', '<i class="fas fa-minus"></i>');
            // $eqLogic->save();

            // self::createCmd( $eqLogic->getId(), 'add1', 'action', 'Ajouter', 'other', '1', '<i class="fas fa-plus"></i>' );
            // self::createCmd( $eqLogic->getId(), 'minus1', 'action', 'Enlever', 'other', '1', '<i class="fas fa-minus"></i>');                           
            // self::createCmd( $eqLogic->getId(), 'stock', 'stock', 'Stock actuel', 'numeric', '1', 'line' );
            // self::createCmd( $eqLogic->getId(), 'stock-terme', 'stock', 'Stock a terme', 'numeric', '1', 'line' );
            // self::createCmd( $eqLogic->getId(), 'stock-scan', 'stock', 'Quantité scanné', 'numeric', '1', 'line' );
     
            return true;
        }

  

        return false;
    }

    // public static function templateFunction() {
    //     $url             = config::byKey('grocy_url','grocy');
    //     $apikey          = config::byKey('grocy_apikey','grocy');

    //     $http            = new grocyAPI($url, $apikey);
    //     $resultLocations = $http->getLocations();

    //     if( is_json( $resultLocations ) ) {
    //         $locations = json_decode( $resultLocations, true );
    //         if( isset( $locations['error_message'] ) ) {
    //             log::add('grocy','error','createLocationsInJeedom: ' . print_r( $resultLocations, true ) );
    //             return false;
    //         } else {

    //         }
    //     } else {
    //         log::add('grocy','error','createLocationsInJeedom: ' . print_r( $resultLocations, true ) );
    //         return false;
    //     }        
    // }

    public static function syncGrocy() {
        if( self::createLocationsInJeedom() ) {
            return self::createProductsInJeedom();
        } else {
            return false;
        }
    }

    public static function syncStock() {

    }


    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    private function createLocationsInJeedom(){

        $url             = config::byKey('grocy_url','grocy');
        $apikey          = config::byKey('grocy_apikey','grocy');

        $http            = new grocyAPI($url, $apikey);
        $resultLocations = $http->getLocations();

        if( is_json( $resultLocations ) ) {

            $locations = json_decode( $resultLocations, true );

            if( isset( $locations['error_message'] ) ) {

                log::add('grocy','error','createLocationsInJeedom: ' . print_r( $resultLocations, true ) );
                return false;
            } else {

                foreach ( $locations as $location ) {
    
                    $jLocation = jeeObject::byName( $location['name'] );
                
                    if ( ! is_object( $jLocation ) ) {

                        $jLocation = new jeeObject();
                        $jLocation->setName( $location['name'] );
                        $jLocation->setIsVisible( 1 );
                        $jLocation->setFather_id( 0 );
                        $jLocation->setDisplay( 'icon','<i class="icon techno-refrigerator3"></i>' );
                        $jLocation->setConfiguration( 'location_id', $location['id'] );
                        $jLocation->save();

                        event::add('jeedom::alert', array(
                            'level' => 'warning',
                            'page' => 'grocy',
                            'message' => __('Emplacement crèe avec succès : ', __FILE__) . $location['name'],
                        ));       
                        
                        sleep(1);
                    }        
                }

                return true;
            }
        } else {

            log::add('grocy','error','createLocationsInJeedom: ' . print_r( $resultLocations, true ) );
            return false;
        }
    }

    private function mapJeeObjectByLocationId( $location_id ) {

        $jObjects = jeeObject::searchConfiguration('location_id');

        $mapObjects = array();
        foreach ($jObjects as $jObject) {
            $mapObjects[$jObject->getConfiguration('location_id')] = $jObject->getId();
        }        

        return $mapObjects[$location_id];
    }

    private function createProductsInJeedom(){

        $url                 = config::byKey('grocy_url','grocy');
        $apikey              = config::byKey('grocy_apikey','grocy');

        $http                = new grocyAPI($url, $apikey);
        $resultProductGroups = $http->getProductGroups();

        if( is_json( $resultProductGroups ) ) {

            $productGroups = json_decode( $resultProductGroups, true );

            if( isset( $productGroups['error_message'] ) ) {

                log::add('grocy','error','resultProductGroupsJson: ' . print_r( $resultProducts, true ) );
                return false;
            } else {

                $pg = array();
                foreach ( $productGroups as $productGroup ) {
                    $pg[$productGroup['id']] = $productGroup['name'];
                }

                $resultProducts = $http->getProducts();

                if( is_json( $resultProducts ) ) {

                    $products = json_decode( $resultProducts, true );

                    if( isset( $products['error_message'] ) ) {
                        log::add('grocy','error','resultProductsJson: ' . print_r( $resultProducts, true ) );
                        return false;
                    } else {
                        foreach ( $products as $product ) {

                            $logicalId = 'grocy-'.$product['id'];

                            $jProduct = grocy::byLogicalId( $logicalId, 'grocy' );
                            if (!is_object($jProduct)) {
                    
                                $eqLogic = new grocy();
                                $eqLogic->setName( $product['name'] );
                                $eqLogic->setEqType_name( 'grocy' );
                                $eqLogic->setLogicalId( $logicalId );
                                $eqLogic->setObject_id( self::mapJeeObjectByLocationId( $product['location_id'] ) );
                                $eqLogic->setConfiguration('id_product', $product['id'] );
                                $eqLogic->setConfiguration('barcode', $product['barcode'] );
                                $eqLogic->setConfiguration('id_stock', $product['qu_id_stock'] );
                                // TODO $eqLogic->setConfiguration('image', $this->getImage());
                                $eqLogic->setIsEnable(1);
                                $eqLogic->setIsVisible('0');
                                $eqLogic->setDisplay('icon', '<i class="fas fa-minus"></i>');
                                $eqLogic->save();

                                self::createCmd( $eqLogic->getId(), 'add1', 'action', 'Ajouter', 'other', '1', '<i class="fas fa-plus"></i>' );
                                self::createCmd( $eqLogic->getId(), 'minus1', 'action', 'Enlever', 'other', '1', '<i class="fas fa-minus"></i>');                           
                                self::createCmd( $eqLogic->getId(), 'stock', 'stock', 'Stock actuel', 'numeric', '1', 'line' );
                                self::createCmd( $eqLogic->getId(), 'stock-terme', 'stock', 'Stock a terme', 'numeric', '1', 'line' );
                                self::createCmd( $eqLogic->getId(), 'stock-scan', 'stock', 'Quantité scanné', 'numeric', '1', 'line' );

                                event::add('jeedom::alert', array(
                                    'level' => 'warning',
                                    'page' => 'grocy',
                                    'message' => __('Produit crèe avec succès : ', __FILE__) . $product['name'],
                                ));                                    

                                log::add('grocy','debug','eqLogic: ' . print_r( $eqLogic, true ) );

                                sleep(1);
                            }
                        }

                        return true;
                    }
                } else {
                    log::add('grocy','error','resultProducts: ' . print_r( $resultProducts, true ) );
                    return false;
                }
            }
        } else {
            log::add('grocy','error','resultProductGroups: ' . print_r( $resultProducts, true ) );
            return false;
        }
    }

    private function createCmd( $_eqLogic_id, $_logicalId, $_type, $_name, $_subtype, $_visible, $_template, $_quantity = null ) {
        $cmd = grocyCmd::byEqLogicIdAndLogicalId( $_eqLogic_id, $_id);
        if (!is_object($cmd)) {
            log::add('grocy', 'debug', 'Création de la commande ' . $_logicalId);

            $cmd = new grocyCmd();
            $cmd->setName( __( $_name, __FILE__ ) );
            $cmd->setEqLogic_id( $_eqLogic_id );
            $cmd->setEqType( 'grocy' );
            $cmd->setLogicalId( $_logicalId );

            if ($_subtype == 'numeric') {

                $cmd->setType( 'info' );
                $cmd->setSubType( 'numeric' );
                $cmd->setIsHistorized('0');
            } else {

                $cmd->setType( 'action' );
                if ($_subtype == 'other') {

                    $cmd->setSubType( 'other' );
                } else {

                    $cmd->setSubType( 'message' );
                }
            }
            if ($_visible == '1') {

                $cmd->setIsVisible('1');
            } else {

                $cmd->setIsVisible('0');
            }
            if ($_template == 'line') {

                $cmd->setTemplate("mobile",$_template );
                $cmd->setTemplate("dashboard",$_template );
            } else {

                $cmd->setDisplay('icon', $_template);
            }

            if( $_type == 'stock' ) {
                $quantite = is_null($_quantity) ? 0 : (int)$_quantity;
                $cmd->setConfiguration( 'value', $quantite );
            }

            $cmd->save();
            if ($_subtype == 'numeric') {

                $cmd->event(0);
            }
        }
    }

    public function addStock( $_value ) {
        log::add('grocy', 'debug', 'addStock : ' . $_value);
        $this->newStock( $this->getId(), 1, $_value );
    }

    public function rmStock( $_value ) {
        log::add('grocy', 'debug', 'rmStock : ' . $_value );
        $this->newStock( $this->getId(), 0, $_value );
    }

    public function newStock( $_eqLogic, $_op, $_value ) {

        log::add('grocy', 'debug', 'op: ' . $_op . ' value: ' . $_value );

        $jScanStock = grocyCmd::byEqLogicIdAndLogicalId( $_eqLogic, 'stock-scan' );
        if( is_object($jScanStock) ) {

            $ssValue = $jScanStock->execCmd();
            $value = (int)$_value ;

            if ($_op) {
                $scanStock = $ssValue + $value;
            } else {
                $scanStock = $ssValue - $value;
            }

            $jScanStock->event($scanStock);

            log::add('grocy', 'debug', 'scanStock : ' . $scanStock );
        }

        $jTermeStock = grocyCmd::byEqLogicIdAndLogicalId( $_eqLogic, 'stock-terme' );
        if( is_object($jTermeStock) ) {

            $jStock     = grocyCmd::byEqLogicIdAndLogicalId( $_eqLogic, 'stock' );
            $stockValue = $jStock->execCmd();

            $termeStock = $stockValue + $scanStock;

            $jTermeStock->event($termeStock);

            log::add('grocy', 'debug', 'termeStock : ' . $termeStock );
        }
    }

    private function demonScan() {

        sleep(60);
        self::stopScanMode();
    }

    // private function getImage( $name ) {
        
    //     //file_put_contents($img, file_get_contents($url));

	// 	// $base = dirname(__FILE__) . '/../../../../';
	// 	// $path = 'plugins/grocy/core/config/'.$type.'/'.$model.'/'.$which.$ver.'.png';
	// 	// $pathDefault = 'plugins/grocy/core/config/'.$type.'/default/'.$which.$ver.'.png';
	// 	// $pathMissing = 'plugins/grocy/core/config/'.$type.'/missing/'.$which.$ver.'.png';        

	// 	// if(file_exists($base.$path)) return $path;
	// 	// else if(file_exists($base.$pathDefault)) return $pathDefault;
	// 	// else if(file_exists($base.$pathMissing)) return $pathMissing;
	// 	// else return 'plugins/unifi/plugin_info/unifi_icon.png';

    //     return '';
    // }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class grocyCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        log::add('grocy', 'debug', 'execute >  getType: ' . $this->getType() .' getLogicalId: ' . $this->getLogicalId());

        if ($this->getType() == 'info') {

            return $this->getConfiguration('value');
        } else {

            $eqLogic = $this->getEqLogic();

            switch ( $this->getLogicalId() ) {
                case 'add1':
                    $eqLogic->addStock( 1 );
                    break;
                
                case 'minus1':
                    $eqLogic->rmStock( 1 );
                    break;
            }
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}