<?php
class UserTable {
    public static $instance;
    public static $file = '/xmls/users.xml';

    private $_database;

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new UserTable();
        }
        return static::$instance;
    }

    private function __construct() {
        $this->_database = Database::openFromFile(static::$file);
    }

    public function save() {
        $this->_database->saveDatabase();
    }

    public function size() {
        return $this->_database->size();
    }

    public function getAllUser() {
        $result = $this->_database->searchNodes("/list/user");
        $arr = [];
        foreach ($result as $user) {
            $arr[] = new User($user);
        }
        return $arr;
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

    public function addUser($id, $name) {
        $user = $this->_database->putIfAbsent("user", NULL, array("id" => $id, "name" => $name));
        if ($user) {
            $user->addChild("requestlist");
            $user->addChild("receivedlist");
            $user->addChild("notification")->addAttribute("count", 0);
        }
        return new User($user);
    }

    public function removeAllRequest($id) {
        $arr = $this->getAllUser();
        foreach ($arr as $user) {
            $user->removeRequest($id);
        }
    }

    public function removeAllReceived($id) {
        $arr = $this->getAllUser();
        foreach ($arr as $user) {
            $user->removeReceived($id);
        }
    }
}

?>