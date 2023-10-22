<?php

interface DAOInterface {
    public function get_some($limit, $offset);
    public function get_all();
    public function find($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function count();
    public function findByField($field, $value);
    public function findAllByCriteria(array $criteria);
    public function batchInsert(array $data);
    public function batchUpdate(array $data);
    public function deleteAll();
    public function truncate();
    public function executeQuery($sql, array $params = []);
}