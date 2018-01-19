<?php
class UserTable {
    static $instance;
    static $file = '/xmls/users.xml';
    private $_database;

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new UserTable();
        }
        return static::$instance;
    }

    private function __construct() {
        $this->_database = new Database($_SERVER['DOCUMENT_ROOT'] . static::$file);
    }

    public function getUser($id) {
        $result = $this->_database->searchNodes("/list/user", NULL, array("id" => $id));
        $len = sizeof($result);
        if ($len > 1) {
            echo "Fatal Error: More than 1 user is found";
            return NULL;
        } else if ($len == 0) {
            echo "No users can be found";
            return NULL;
        } else {
            return new User($result[0]);
        }
    }
}

?>