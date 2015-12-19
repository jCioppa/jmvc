<?php

    namespace App\Models;
    use PDO;

    class Article extends Model {

        use ModelTraits;

        protected static $table = 'articles';

        public function __construct() {
            parent::__construct();
            $this->saveStmt = self::$PDO->prepare('insert into articles (title, body, misc) values (:title, :body, :misc)');
            $this->findStmt = self::$PDO->prepare('select * from articles where id = :id');
            $this->deleteStmt = self::$PDO->prepare('delete from articles where id = :id');
            $this->updateStmt = self::$PDO->prepare('update articles set title = :title, body = :body, misc = :misc where id = :id');
            $this->allStmt = self::$PDO->prepare('select * from articles');
        }

        public function whereStmt(array $array) {
            $query = "select * from articles where ";
            foreach ($array as $key => $val) {
                $query .= $key . " = :" . $key . ' and ';
            }
            $len = strlen($query);
            $query = substr($query, 0, $len - 5);
            return self::$PDO->prepare($query);
        }

 
        public function setProperties(array $array) {
            $this->title = $array['title'];
            $this->body = $array['body'];
            $this->misc = $array['misc'];
            $this->id = $array['id'];
        }

        public function properties() {
            return [
                'title' => $this->title, 
                'body' => $this->body, 
                'misc' => $this->misc,
                'id' => $this->id
            ];
        }

        public static function classType() {
            return 'App\Models\Article';
        }

        public static function table() {
            return 'articles';
        }


    }
