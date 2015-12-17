<?php

    namespace App\Http\Validation;

class Validator  {

    static $rules = [
        'required', 
        'integer',
        'string'
    ];

    public static function accepts($value, $rle) {

            $rules = explode('|', $rle); 
            $valid = true;

            foreach($rules as $rule){
                if (! self::passes($value, $rule))
                    $valid = false;
            }

            return $valid;
        
        } 

        public static function passes($value, $rule) {
        
            if ($rule == "required") {
                return isset($value) && ! is_null($value) && ! empty($value);
            } 

            if ($rule == "integer") {
                return is_numeric($value) || is_null($value);
            }

            if ($rule == "string") {
                return true; 
            }
        }

    }
