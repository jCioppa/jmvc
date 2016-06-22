<?php

    namespace App\Controllers;

    use App\Models\Model;
    use App\Models\ModelCollection;
    use App\Http\Request\Request;
    
    class ErrorController extends BaseController {

        public function index() {
            return error(404, "page not found");
        }

    }
