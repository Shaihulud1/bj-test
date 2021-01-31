<?php
namespace core;

use core\Db;

class Repository 
{
    private $model;
    private $db;

    public function __construct($model)
    {
        $this->model = $model;
        $this->db = DB::init();
    }

    public function getByFields(array $fields): ?object
    {
        if (empty($fields)) { return null; }
        $fieldsStr = $this->getModelFields(false);
        $sql = "SELECT $fieldsStr FROM ".$this->model::$tableName;
        $sqlWhere = "";
        foreach ($fields as $key => $field) {
            $sqlWhere .= "$key = :$key AND ";
        }
        $sqlWhere = trim($sqlWhere, "AND ");
        $sql .= " WHERE $sqlWhere";
        $res = $this->db->query($sql, $fields, true);
        return isset($res[0]) ? $this->fillModel($res[0]) : null;
    }  

    public function getAll(Int $pageSize = 3, Int $page = 1, array $sorting=[]): array
    {
        $fields = $this->getModelFields(false);
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT COUNT(*) as count FROM ".$this->model::$tableName;
        $count = $this->db->query($sql, [], true);
        $count = $count[0]['count'];
        $posiblePages = ceil($count / $pageSize);
        $sql = "SELECT $fields FROM ".$this->model::$tableName;
        if ($this->isCorrectSorting($sorting)) {
            $sql .= " ORDER BY {$sorting['sortField']} {$sorting['sortDir']}";
        }
        $sql .= " LIMIT $pageSize OFFSET $offset";
        $list = $this->db->query($sql, [], true);
        $models = [];
        foreach ($list as $listItem) {
            $models[] = $this->fillModel($listItem);
        }
        return ['items' => $models, 'pages' => $posiblePages];
    }

    public function update($model): void
    {
        $sql = "UPDATE ".$this->model::$tableName;
        $fields = $model::$fields;
        $sqlUpdate = "";
        $params = [];
        foreach ($fields as $fieldName => $fieldData) {
            if ($fieldName == 'id') { continue; }
            $sqlUpdate .= "$fieldName = :$fieldName, ";
            $params[$fieldName] = $model->$fieldName;
        }
        $sqlUpdate = trim($sqlUpdate, ", ");
        $sql .= " SET $sqlUpdate";
        $sql .= " WHERE id = $model->id";
        $this->db->query($sql, $params, false);
    }

    public function create($model): void
    {
        $fields = $model::$fields;
        $sql = "INSERT INTO ".$this->model::$tableName." (".$this->getModelFields().")";
        $sql .= " VALUES (";
        $sqlValues = "";
        $params = [];
        foreach ($fields as $fieldName => $fieldValue) {
            if ($fieldName == 'id') { continue; }
            $sqlValues .= ":$fieldName, ";
            $params[$fieldName] = $model->$fieldName;
        }
        $sqlValues = trim($sqlValues, ", ");
        $sql .= "$sqlValues";
        $sql .= ")";
        $this->db->query($sql, $params, false);
    }

    private function getModelFields($withoutId=true)
    {
        $fields = $this->model::$fields;
        if ($withoutId && isset($fields['id'])) { 
            unset($fields['id']);
        }
        $fields = !empty($fields) ? implode(', ', array_keys($fields)) : "*";
        return $fields;
    }

    private function fillModel(array $values)
    {
        $model = new $this->model;
        $model->isNew = false;
        foreach ($values as $k => $v) {
            if (!isset($this->model::$fields[$k])) { continue; }
            switch ($this->model::$fields[$k]['type']) {
                case 'int':
                    $v = (int)$v;
                    break;
                case 'bool':
                    $v = (bool)$v;
                    break;                
            }
            $model->$k = $v;
        }
        return $model;
    }

    private function isCorrectSorting(array $sorting): bool
    {
        if (empty($sorting)) { return false; }
        if (!isset($sorting['sortField']) || !isset($sorting['sortDir'])) { return false; }
        if (!in_array($sorting['sortDir'], ['asc', 'desc'])) { return false; }
        if (!in_array($sorting['sortDir'], ['asc', 'desc'])) { return false; }
        if (!isset($this->model::$fields[$sorting['sortField']])) { return false; }
        return true;
    }
}