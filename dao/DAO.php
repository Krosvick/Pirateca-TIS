<?php

namespace DAO;

interface DAOInterface {
    public function get_some($limit, $offset);
    public function get_all();
    public function find($id);
    public function register($data);
    public function update($id, $data);
    public function delete($id);
}