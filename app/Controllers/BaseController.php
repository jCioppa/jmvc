<?php

    namespace App\Controllers;
    use App\Http\Request\Request; 
    use App\Http\Validation\Validator;

    class BaseController {

        public function validate(Request $request, $rules) {

            $valid = true;

            foreach($request->input() as $key => $val) {
                if (isset($rules[$key]))
                    $valid = $valid && Validator::accepts($val, $rules[$key]);
            }  
            
            return $valid;

        }

    }
