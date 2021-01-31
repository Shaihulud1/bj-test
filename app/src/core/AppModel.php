<?php
namespace core; 

use core\Repository;

class AppModel {

    static $fields;
    static $tableName;
    public $isNew = true;

    public function save(): void
    {
        $repository = new Repository(get_class($this));
        if ($this->isNew) {
            $repository->create($this); 
            $this->isNew = false;   
        } else {
            $repository->update($this);
        }
    }
}