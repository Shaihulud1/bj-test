<?php
namespace model;

class User extends \core\AppModel
{ 
    static $tableName = "user";
    static $fields = [
        'id'     => ['type' => 'int'],
        'name'   => ['type' => 'string'],
        'pass'   => ['type' => 'string'],
        'role'   => ['type' => 'string'],
    ];
}