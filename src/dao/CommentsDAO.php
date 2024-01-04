<?php

namespace DAO;

use Core\DAO;


/**
 * Class commentsDAO
 *
 * This class represents a data access object for retrieving comments from the database.
 */
class commentsDAO extends DAO {

    /**
     * Constructor method for commentsDAO class.
     * Initializes the table name and calls the parent constructor.
     */
    public function __construct() {
        $this->table = 'comments';
        parent::__construct();
    }

    /**
     * Retrieves comments from the database based on the provided rating ID, quantity, and sorting order.
     *
     * @param int $id The rating ID to filter the comments.
     * @param int $quantity The maximum number of comments to retrieve.
     * @param bool $desc Determines the sorting order of the comments. Default is ascending order.
     * @return array An array of comments matching the provided rating ID, limited by the specified quantity, and sorted in the determined order.
     */
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