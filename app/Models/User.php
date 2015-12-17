<?php

    namespace App\Models;
    use PDO;

    class User extends Model {

        use ModelTraits;

        protected static $table = 'users';

        public function __construct() {
            parent::__construct();
            $this->saveStmt = self::$PDO->prepare('insert into users (name) values (:name)');
            $this->findStmt = self::$PDO->prepare('select * from users where id = :id');
            $this->deleteStmt = self::$PDO->prepare('delete from users where id = :id');
            $this->updateStmt = self::$PDO->prepare('update users set name = :name where id = :id');
            $this->allStmt = self::$PDO->prepare('select * from users');
        }

        public function setProperties(array $array) {
            $this->name = $array['name'];
            $this->id = $array['id'];
        }

        public function properties() {
            return ['name' => $this->name, 'id' => $this->id];
        }

        public static function find($id) {
            $user = new User();
            $res = $user->findModel($id);
            if ($res) 
                return $user;
            return null;
        }

        public static function classType() {
            return 'App\Models\User';
        }

        public static function table() {
            return 'users';
        }

    }
