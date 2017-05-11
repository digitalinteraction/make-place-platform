<?php



/** ... */
class GeoRef extends DataObject {
    
    private static $db = [
        'Reference' => 'Int'
    ];
    
    protected static $refCache = [];
    public static $testMode = false;
    
    private static $belongs_many_many = [
        "Responses" => "SurveyResponse"
    ];
    
    
    
    public static function geoRequest($endpoint) {
        
        return CurlRequest::create(GEO_URL."/$endpoint", ["api_key" => GEO_KEY]);
    }
    
    
    
    
    public static function makeRef($type, $dataType, $value, &$errors = []) {
        
        // If in test mode, return a fake value
        if (self::$testMode) {
            $ref = GeoRef::create(["Reference" => 1]);
            $ref->write();
            return $ref;
        }
        
        
        // Add the type to the value
        $value["type"] = $type;
        
        
        
        // Create a request to create the geometry
        $req = self::geoRequest("geo");
        $req->setMethod("POST");
        $req->setJsonBody([
            "geom" => $value,
            "data_type" => $dataType
        ]);
        
        // Execute the request
        $res = $req->jsonResponse();
        
        
        // Check the request passed
        if (CurlRequest::validApiResponse($res)) {
            
            // Return a new GeoRef with the response's id
            $ref = GeoRef::create([
                "Reference" => $res["data"]
            ]);
            $ref->write();
            return $ref;
        }
        else {
            
            // TODO: need to push values individually
            $errors = CurlRequest::apiResponseErrors($res);
            return null;
        }
    }
    
    
    public function fetchValue() {
        
        // If in test mode, mock it
        if (self::$testMode) {
            return [ "ID" => "1", "Some" => "data" ];
        }
        
        // Check in the cache for a value
        // TODO: Not tested, so leaving it out for now
        // if (isset(self::$refCache[$this->Reference])) {
        //     return self::$refCache[$this->Reference];
        // }
        
        $res = self::geoRequest("geo/{$this->Reference}")
            ->jsonResponse();
        
        if (CurlRequest::validApiResponse($res)) {
            self::$refCache[$this->Reference] = $res['data'];
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
