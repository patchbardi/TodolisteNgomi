<?php

require_once('config.php');

/**
 * Todo database object.
 *
 * Global variable with the object of our TodoDB class.
 */
$todoDB = new TodoDB();

/**
 * Database handling for the todos in the FI35 demo project.
 *
 * All database functionality is defined here.
 *
 * @author  US-FI36 <post@fi36-coding.com>
 * @property object $connection PDO connection to the MariaDB
 * @property object $stmt Database statement handler object.
 */
class TodoDB {
    private $connection;
    private $stmt;

    /**
     * Constructor of the TodoDB class.
     */
    public function __construct() {
        global $host, $db, $user, $pass;
        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$db;",
                $user,
                $pass
            );
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function getTodos() {
        $statement = $this->connection->query("SELECT id, text, completed from todos");
        $todo_items = $statement->fetchAll();
        return $todo_items;
    }
}

