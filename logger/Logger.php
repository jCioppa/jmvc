<?php

    namespace Logger;

    class Logger implements LoggerInterface {

        const DEBUG = 100;
        const INFO = 200;
        const NOTICE = 250;
        const WARNING = 300;
        const ERROR = 400;
        const CRITICAL = 500;
        const ALERT = 550;
        const EMERGENCY = 600;

        protected static $levels = array(
            100 => 'DEBUG',
            200 => 'INFO',
            250 => 'NOTICE',
            300 => 'WARNING',
            400 => 'ERROR',
            500 => 'CRITICAL',
            550 => 'ALERT',
            600 => 'EMERGENCY',
        );

        protected $name;

        public function __construct($name) {
            $this->name = $name;
        }

        public function getName() {
            return $this->name;
        }
        
        public function addRecord($level, $message, $console = false, array $context = array()){
        
            $levelName = static::getLevelName($level);

            $record = array(
                'message' => (string) $message,
                'context' => $context,
                'level' => $level,
                'level_name' => $levelName,
                'channel' => $this->name,
                'extra' => array()
            );

            $logfile = \App\Config::get("logging.logfile"); 

            $out = $record['channel'] . "\t";
            $out .= "[" . $record["level_name"] . "(" . $record["level"] . ")]" . "\t";
            $out .= $record["message"] . "\t";
            $out .= (new \DateTime())->format('Y-m-d H:i:s');
            $out .= "\n";

            if ($console == true) {
                echo "<script>console.log('" . $out . "');</script>";
            }
            else {
                $myfile = file_put_contents($logfile, $out, FILE_APPEND);
            }
            
            return true;
        }

        // add logs at various levels {{{
        
        public function addDebug($message, array $context = array()){
            return $this->addRecord(static::DEBUG, $message, $context);
        }

        public function addInfo($message, array $context = array()){
            return $this->addRecord(static::INFO, $message, $context);
        }

        public function addNotice($message, array $context = array()){
            return $this->addRecord(static::NOTICE, $message, $context);
        }
        
        public function addWarning($message, array $context = array()){
            return $this->addRecord(static::WARNING, $message, $context);
        }

        public function addError($message, array $context = array()){
            return $this->addRecord(static::ERROR, $message, $context);
        }

        public function addCritical($message, array $context = array()){
            return $this->addRecord(static::CRITICAL, $message, $context);
        }

        public function addAlert($message, array $context = array()){
            return $this->addRecord(static::ALERT, $message, $context);
        }

        public function addEmergency($message, array $context = array()){
            return $this->addRecord(static::EMERGENCY, $message, $context);
        }

        //}}}
        
        public static function getLevels(){
            return array_flip(static::$levels);
        }

        public static function getLevelName($level)
        {
            if (!isset(static::$levels[$level])) {
                throw new \Exception('Level "'.$level.'" is not defined, use one of: '.implode(', ', array_keys(static::$levels)));
            }

            return static::$levels[$level];
        }

        public static function toMonologLevel($level)
        {

            if (is_string($level) && defined(__CLASS__.'::'.strtoupper($level))) {
                return constant(__CLASS__.'::'.strtoupper($level));
            }

            return $level;
        }


    // add logs at various levels {{{
        public function log($level, $message, array $context = array()){
            $level = static::toMonologLevel($level);
            return $this->addRecord($level, $message, $context);
        }

        public function debug($message, array $context = array()){
            return $this->addRecord(static::DEBUG, $message, $context);
        }

        public function info($message, array $context = array()){
            return $this->addRecord(static::INFO, $message, $context);
        }
        
        public function notice($message, array $context = array()){
            return $this->addRecord(static::NOTICE, $message, $context);
        }

        public function warn($message, array $context = array()){
            return $this->addRecord(static::WARNING, $message, $context);
        }
        
        public function warning($message, array $context = array()){
            return $this->addRecord(static::WARNING, $message, $context);
        }

        public function err($message, array $context = array()){
            return $this->addRecord(static::ERROR, $message, $context);
        }

        public function error($message, array $context = array()){
            return $this->addRecord(static::ERROR, $message, $context);
        }

        public function crit($message, array $context = array()){
            return $this->addRecord(static::CRITICAL, $message, $context);
        }

        public function critical($message, array $context = array()){
            return $this->addRecord(static::CRITICAL, $message, $context);
        }

        public function alert($message, array $context = array()){
            return $this->addRecord(static::ALERT, $message, $context);
        }

        public function emerg($message, array $context = array()){
            return $this->addRecord(static::EMERGENCY, $message, $context);
        }

        public function emergency($message, array $context = array()){
            return $this->addRecord(static::EMERGENCY, $message, $context);
        }

    // }}}
        
        public static function setTimezone(\DateTimeZone $tz){
            self::$timezone = $tz;
        }

    }
