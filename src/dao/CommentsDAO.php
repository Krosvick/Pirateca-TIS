<?php

namespace DAO;

use Core\DAO;


class commentsDAO extends DAO {


    public function __construct() {
        $this->table = 'comments';
        parent::__construct();
    }


    public function getComments($id, $quantity, $desc = false) {
        $order = $desc ? 'DESC' : 'ASC';
        $sql = "SELECT * FROM {$this->table} WHERE rating_id = :id ORDER BY id {$order} LIMIT {$quantity}";
        $params = [
            'id' => [$id, \PDO::PARAM_INT]
        ];
        $stmt = $this->connection->query($sql,$params);
        $result = $stmt->get();
        return $result;
    }

}