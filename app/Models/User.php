<?php

    namespace App\Models;
    use PDO;

    class User extends Model {

        use ModelTraits;

        protected static $table = 'users';

        public function __construct() {

            parent::__construct();

            $this->saveStmt = self::$PDO->prepare('insert into users (name, email, password, salt) values (:name, :email, :password, :salt)');
            $this->findStmt = self::$PDO->prepare('select * from users where id = :id');
            $this->deleteStmt = self::$PDO->prepare('delete from users where id = :id');
            $this->updateStmt = self::$PDO->prepare('update users set name = :name where id = :id');
            $this->allStmt = self::$PDO->prepare('select * from users');

        }

        public function whereStmt(array $array) {
            $query = "select * from users where ";
            foreach ($array as $key => $val) {
                $query .= $key . " = :" . $key . ' and ';
            }
            $len = strlen($query);
            $query = substr($query, 0, $len - 5);
            return self::$PDO->prepare($query);
        }

        public function setProperties(array $array) {
            foreach($array as $key => $val) {
                $this->$key = $val;
            }
        }

        public function properties() {
            return [
                'name' => $this->name, 
                'salt' => $this->salt, 
                'email' => $this->email, 
                'password' => $this->password
            ];
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
