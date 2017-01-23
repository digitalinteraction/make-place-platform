<?php

/**
 * Loosely based on
 * @link https://github.com/phptek/silverstripe-jsontext/blob/master/code/models/fieldtypes/JSONText.php
 *
 * @todo Modify field's queries to use native json for MYSql, https://dev.mysql.com/doc/refman/5.7/en/json.html
 * @todo Json not being stored between calls of jsonField?
 */



/** A custom field to store a blob of json in the database (stored as a string) */
class JsonText extends StringField {
    
    private static $return_types = [
        'json', 'array', 'silverstripe'
    ];
    
    protected $nullifyEmpty = false;
    protected $returnType = 'json';
    protected $jsonValue;
    
    
    /** The function Silverstripe uses to setup the field */
    public function requireField() {
        
        $parts = [
            'datatype'      => 'mediumtext',
            'character set' => 'utf8',
            'collate'       => 'utf8_general_ci',
            'arrayValue'    => $this->arrayValue
        ];
        $values = [
            'type'  => 'text',
            'parts' => $parts
        ];
        
        DB::require_field($this->tableName, $this->name, $values);
    }
    
    
    /** Override the setting of the field, if passed an array it parses its value to a string to be stored in the database */
    public function setValue($value, $record = null) {
        
        if (is_array($value)) {
            $this->jsonValue = $value;
            $value = json_encode($value);
        }
        
        parent::setValue($value, $record);
    }
    
    /** A custom function to get the json value of the field */
    public function valueAsJson() {
        
        // var_dump($this);
        
        if ($this->jsonValue == null) {
            $this->jsonValue = json_decode($this->value, true);
        }
        return $this->jsonValue;
    }
    
}
