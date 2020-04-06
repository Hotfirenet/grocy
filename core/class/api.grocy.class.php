<?php
/* 
 * Author: Johan VIVIEN
 * Since: 1.0
 *
*/

class grocyAPI {

    private $url                    = '';
    private $apikey                 = '';

    private $apiLocations           = 'api/objects/locations';
    private $apiProductGroups       = 'api/objects/product_groups';
    private $apiProducts            = 'api/objects/products';
    private $apiChores              = 'api/objects/chores';
    private $apiStock               = 'api/stock';
    private $apiStockProductAdd     = 'api/stock/products/%d/add';
    private $apiStockProductConsume = 'api/stock/products/%d/consume';
    private $apiShoppingList        = 'api/stock/shoppinglist/';
    private $apiChoresExex          = 'api/chores/';
    private $apiSystemInfo          = 'api/system/info';
    
    private $apiGrocyMinVersion = "2.5.1";

    function __construct( $_url = null, $_apikey = null) {
        $this->apikey = $_apikey;

        if ( substr( $_url, -1, 1 ) == '/') {
            $this->url = $_url;
        } else {
            $this->url = $_url . '/';
        }
    }

    public function checkInstance() {
        $url = $this->url . $this->apiSystemInfo;
        return $this->sendCommand( $url );
    }

    public function getLocations() {
        $url = $this->url . $this->apiLocations;
        return $this->sendCommand( $url );       
    }

    public function getProductGroups() {
        $url = $this->url . $this->apiProductGroups;
        return $this->sendCommand( $url );       
    }

    public function getProducts() {
        $url = $this->url . $this->apiProducts;
        return $this->sendCommand( $url );       
    }

    public function getAllProductsStock() {
        $url = $this->url . $this->apiStock;
        return $this->sendCommand( $url );           
    }

    public function purchaseProduct( $_data ) {

        $apiStockProductAdd = sprintf( $this->apiStockProductAdd, $_data['product_id'] );      
        $url = $this->url . $apiStockProductAdd;

        unset( $_data['product_id'] );

        return $this->sendCommand( $url, 'POST', $_data );         
    }

    public function consumeProduct( $_data ) {

        $apiStockProductConsume = sprintf( $this->apiStockProductConsume, $_data['product_id'] );      
        $url = $this->url . $apiStockProductConsume;

        unset( $_data['product_id'] );

        return $this->sendCommand( $url, 'POST', $_data );         
    }

    private function sendCommand($_url, $_method = 'GET', $_data = array() ) {

        try {
            $request_http = new com_http( $_url );

            $headerArray = array(
                'Content-Type: application/json',
                'GROCY-API-KEY: ' . $this->apikey 
            );

            switch ( $_method ) {
                case 'POST':
                    $data = json_encode( $_data );
                    array_push( $headerArray, 'Content-Length: ' . strlen( $data ) );
                    $request_http->setPost( $data );
                    break;
            }
            $request_http->setHeader( $headerArray );

            log::add('grocy','debug','checkGrocyInstance: ' . print_r( $request_http, true ) );

            return $request_http->exec(30); 
        } catch (\Throwable $th) {
            return $th;
        }
    }    
}