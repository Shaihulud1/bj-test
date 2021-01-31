<?php
namespace model;

class Task extends \core\AppModel
{ 
    static $tableName = "task";
    static $fields = [
        'id'     => ['type' => 'int'],
        'name'   => ['type' => 'string'],
        'email'  => ['type' => 'string'],
        'text'   => ['type' => 'string'],
        'isDone' => ['type' => 'bool'],
        'updatedByAdmin' => ['type' => 'bool']
    ];


}