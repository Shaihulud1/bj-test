<?php

namespace core;

class Db 
{
    static $init = null; 
    private $conn;

    private function __construct()
    {
        try {
            $this->conn = new \PDO('mysql:dbname=test;host=db;charset=utf8', 'test', 'test');
        } catch (\Throwable $th) {
            echo 'DB error';
            dd($th);
            die;
        }
    }

    static function init(): object
    {
        if (self::$init == null) {
            self::$init = new Self;
        }
        return self::$init;
    }

    public function query(String $sql, Array $params = [], $fetch=false)
    {
        try {
            $sth = $this->conn->prepare($sql);  
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $sth->bindValue(":$key", $value);
                }
            }
            $sth->execute();
            if ($fetch) {
                return $sth->fetchAll();
            }
        } catch (\Throwable $th) {
            echo 'DB query error';
            echo "<pre>";print_R($th);echo "</pre>";
        }

    }
}