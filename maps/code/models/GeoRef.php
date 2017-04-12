<?php



/** ... */
class GeoRef extends DataObject {
    
    private static $db = [
        'Reference' => 'Int'
    ];
    
    protected static $refCache = [];
    public static $testMode = false;
    
    
    public function fetchValue() {
        
        // Check in the cache for a value
        if (isset(self::$refCache[$this->Reference])) {
            return self::$refCache[$this->Reference];
        }
        
        $response = CurlRequest::create(GEO_URL."/geo/1", ["api_key" => GEO_KEY])
            ->jsonResponse();
        
        if (CurlRequest::validApiResponse($response)) {
            
            self::$refCache[$this->Reference] = $response['data'];
            return self::$refCache[$this->Reference];
        }
        
        return null;
    }
    
    
    
    
    public function toJson() {
        
        return [
            'ID' => $this->ID,
            'Value' => $this->fetchValue()
        ];
    }
}
