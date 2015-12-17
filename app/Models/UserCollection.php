<?php

    namespace App\Models;

    class UserCollection extends ModelCollection {
        public function targetClass() {
            return 'App\Models\User';
        }
    }
