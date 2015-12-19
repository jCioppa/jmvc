<?php

    namespace App\Models;
    use PDO;
    use App\Config;

    trait ModelTraits {

        public function saveStmt() { return $this->saveStmt; }
        public function findStmt() { return $this->findStmt; }
        public function deleteStmt() {return $this->deleteStmt;}
        public function updateStmt() {return $this->updateStmt;}
        public function allStmt() {return $this->allStmt; } 

        public static function all() {

            self::initDB();

            $classtype = self::classType();
            $collectiontype = $classtype . 'Collection'; 
            $collection = new $collectiontype;
           
            $stmt = self::connection()->prepare('select * from ' . self::table());
            $stmt->execute();
            $array = $stmt->fetchAll();
            $stmt->closeCursor();

            foreach ($array as $data) {
                $model = new $classtype;
                $model->setProperties($data);
                $collection->add($model);
            }

            return $collection;
        
        }

        public static function find($id) {
            $type = self::classType();
            $model = new $type();
            $model->findModel($id);
            return $model;
        }

        public static function where($array) {
            $type = self::classType();
            $model = new $type;

            $found = $model->whereModel($array);
            if ($found == true) return $model;
            return null; 
        }

    }

    abstract class Model {

        protected static $PDO;

        protected abstract function saveStmt();
        protected abstract function allStmt();
        protected abstract function findStmt();
        protected abstract function deleteStmt();
        protected abstract function updateStmt();
        protected abstract function properties();
        protected abstract function setProperties(array $array);
        protected abstract function whereStmt(array $array);

        public function __construct() { 
            self::initDB(); 
        } 

        public static function initDB() {
            if (! self::$PDO) {

                $driver = Config::get('database.default');

                if ($driver == "mysql") {
                    $host = Config::get('database.connections')[$driver]['host'];
                    $name = Config::get('database.connections')[$driver]['database'];
                    $dsn = 'mysql:host=' . $host . ';dbname=' . $name;
                    $user = Config::get('database.connections')[$driver]['username'];
                    $pass = Config::get('database.connections')[$driver]['password'];
                    self::$PDO = new PDO($dsn, $user, $pass);
                }

                 if ($driver == "sqlite") {
                    $db_file = Config::get('database.connections')[$driver]['database'];
                    $dsn = 'sqlite:' . $db_file;
                    self::$PDO = new PDO($dsn);
                }

                self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }

        } 

        public static function connection() {
            self::initDB();
            return self::$PDO;
        }

        public function save() {
               $this->saveStmt()->execute($this->properties());
        }

        public static function classType() {
            return 'Model';
        }

        public function findModel($id) {
 			$this->findStmt()->execute(['id' => $id]);
			$array = $this->findStmt()->fetch();
			$this->findStmt()->closeCursor();
			if (!is_array($array)) return false;
			if (!isset($array['id'])) return false;
            $this->setProperties($array);
            return true;
        }

        public function whereModel($array) {
            $stmt = $this->whereStmt($array);
            $stmt->execute($array);
            $result = $stmt->fetch(); 
            $stmt->closeCursor();
            if (!is_array($result) || !isset($result['id'])) return false;
            $this->setProperties($result);
            return true;
        }

        public function delete() {
            $this->deleteStmt()->execute(['id' => $this->id]);
        }

        public function update() {
            $this->updateStmt()->execute($this->properties()); 
        }

    } 
