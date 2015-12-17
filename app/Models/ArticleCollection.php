<?php

    namespace App\Models;

    class ArticleCollection extends ModelCollection {
        public function targetClass() {
            return 'App\Models\Article';
        }
    }
